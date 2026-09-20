<?php

namespace App\Services;

use Carbon\CarbonImmutable;

/**
 * Estimación de finiquito y liquidación laboral en México (Ley Federal del Trabajo).
 *
 * Es una herramienta orientativa: no considera ISR, salarios caídos, prestaciones
 * superiores a la ley ni condiciones particulares de cada relación de trabajo.
 */
class SeveranceCalculator
{
    public const DISMISSAL_UNJUSTIFIED = 'injustificado';

    public const DISMISSAL_JUSTIFIED = 'justificado';

    public const RESIGNATION = 'renuncia';

    private const PERIOD_DIVISORS = ['daily' => 1, 'weekly' => 7, 'biweekly' => 15, 'monthly' => 30];

    /**
     * @param  array{start_date: string, end_date: string, salary_amount: float|int|string, salary_period?: string, separation_type?: string, zone?: string, vacation_days_taken?: int|string|null, aguinaldo_days?: int|string|null}  $input
     * @return array{
     *     daily_salary: float, integrated_daily_salary: float, years_of_service: float, service_label: string,
     *     items: array<int, array{key: string, group: string, label: string, detail: string, amount: float}>,
     *     subtotal_finiquito: float, subtotal_liquidacion: float, total: float, notes: array<int, string>
     * }
     */
    public function calculate(array $input): array
    {
        $start = CarbonImmutable::parse($input['start_date'])->startOfDay();
        $end = CarbonImmutable::parse($input['end_date'])->startOfDay();
        $type = $input['separation_type'] ?? self::DISMISSAL_UNJUSTIFIED;
        $period = $input['salary_period'] ?? 'monthly';
        $aguinaldoDays = (int) ($input['aguinaldo_days'] ?? config('despacho.calculator.aguinaldo_days'));
        $vacationTaken = (int) ($input['vacation_days_taken'] ?? 0);
        $minimumWage = $this->minimumWage($input['zone'] ?? 'general');

        $daily = round((float) $input['salary_amount'] / self::PERIOD_DIVISORS[$period], 2);

        $completedYears = $start->diffInYears($end, true);
        $completedYears = (int) floor($completedYears);
        $lastAnniversary = $start->addYears($completedYears);
        $daysSinceAnniversary = (int) $lastAnniversary->diffInDays($end, true);
        $yearsOfService = round($completedYears + min($daysSinceAnniversary, 365) / 365, 4);

        $vacationDays = $this->vacationDaysForServiceYear($completedYears + 1);
        $premiumRate = (float) config('despacho.calculator.vacation_premium');
        $integrationFactor = 1 + ($aguinaldoDays + $vacationDays * $premiumRate) / 365;
        $integrated = round($daily * $integrationFactor, 2);

        $items = [];

        // Aguinaldo proporcional del año en curso
        $yearStart = $end->startOfYear();
        $counted = $start->greaterThan($yearStart) ? $start : $yearStart;
        $daysInYear = (int) $counted->diffInDays($end, true) + 1;
        $items[] = $this->item(
            'aguinaldo', 'finiquito', 'Aguinaldo proporcional',
            sprintf('%d días de aguinaldo × %d/365 días trabajados en el año × salario diario', $aguinaldoDays, min($daysInYear, 365)),
            $aguinaldoDays * $daily * min($daysInYear, 365) / 365
        );

        // Vacaciones proporcionales pendientes y prima vacacional
        $entitled = $vacationDays * min($daysSinceAnniversary, 365) / 365;
        $pendingVacation = max(0.0, $entitled - $vacationTaken);
        $vacationPay = $pendingVacation * $daily;
        $items[] = $this->item(
            'vacaciones', 'finiquito', 'Vacaciones proporcionales',
            sprintf('%s días pendientes (de %d días del año de servicio) × salario diario', number_format($pendingVacation, 2), $vacationDays),
            $vacationPay
        );
        $items[] = $this->item(
            'prima_vacacional', 'finiquito', 'Prima vacacional',
            sprintf('%d%% sobre las vacaciones proporcionales', (int) round($premiumRate * 100)),
            $vacationPay * $premiumRate
        );

        // Prima de antigüedad (art. 162 LFT): 12 días por año, salario topado a 2 salarios mínimos
        $seniorityApplies = $type !== self::RESIGNATION || $yearsOfService >= 15;
        if ($seniorityApplies) {
            $cappedDaily = min($daily, 2 * $minimumWage);
            $items[] = $this->item(
                'prima_antiguedad', 'liquidacion', 'Prima de antigüedad',
                sprintf('12 días × %s años de servicio × salario diario aplicable $%s (tope: 2 salarios mínimos = $%s)', number_format($yearsOfService, 2), number_format($cappedDaily, 2), number_format(2 * $minimumWage, 2)),
                12 * $yearsOfService * $cappedDaily
            );
        }

        // Indemnización por despido injustificado (arts. 48 y 50 LFT)
        if ($type === self::DISMISSAL_UNJUSTIFIED) {
            $items[] = $this->item(
                'indemnizacion_constitucional', 'liquidacion', 'Indemnización constitucional (3 meses)',
                sprintf('90 días × salario diario integrado ($%s)', number_format($integrated, 2)),
                90 * $integrated
            );
            $items[] = $this->item(
                'veinte_dias', 'liquidacion', '20 días por año de servicios',
                sprintf('20 días × %s años × salario diario integrado', number_format($yearsOfService, 2)),
                20 * $yearsOfService * $integrated
            );
        }

        $finiquito = $this->sum($items, 'finiquito');
        $liquidacion = $this->sum($items, 'liquidacion');

        return [
            'daily_salary' => $daily,
            'integrated_daily_salary' => $integrated,
            'years_of_service' => $yearsOfService,
            'service_label' => $this->serviceLabel($completedYears, $daysSinceAnniversary),
            'items' => $items,
            'subtotal_finiquito' => $finiquito,
            'subtotal_liquidacion' => $liquidacion,
            'total' => round($finiquito + $liquidacion, 2),
            'notes' => $this->notes($type, $seniorityApplies),
        ];
    }

    /**
     * Días de vacaciones por año de servicio (art. 76 LFT, reforma «vacaciones dignas»).
     */
    public function vacationDaysForServiceYear(int $serviceYear): int
    {
        return match (true) {
            $serviceYear <= 1 => 12,
            $serviceYear <= 5 => 12 + ($serviceYear - 1) * 2,
            default => 20 + 2 * (int) ceil(($serviceYear - 5) / 5),
        };
    }

    public function minimumWage(string $zone): float
    {
        return $zone === 'frontera'
            ? (float) setting('calculator_min_wage_border', config('despacho.calculator.minimum_wage_border'))
            : (float) setting('calculator_min_wage', config('despacho.calculator.minimum_wage'));
    }

    /**
     * @param  array<int, array{group: string, amount: float}>  $items
     */
    private function sum(array $items, string $group): float
    {
        return round(array_sum(array_map(fn (array $item): float => $item['group'] === $group ? $item['amount'] : 0.0, $items)), 2);
    }

    /**
     * @return array{key: string, group: string, label: string, detail: string, amount: float}
     */
    private function item(string $key, string $group, string $label, string $detail, float $amount): array
    {
        return ['key' => $key, 'group' => $group, 'label' => $label, 'detail' => $detail, 'amount' => round($amount, 2)];
    }

    private function serviceLabel(int $years, int $days): string
    {
        $months = intdiv(min($days, 365), 30);
        $parts = [];
        if ($years > 0) {
            $parts[] = $years.' '.($years === 1 ? 'año' : 'años');
        }
        if ($months > 0 || $years === 0) {
            $parts[] = $months.' '.($months === 1 ? 'mes' : 'meses');
        }

        return implode(' y ', $parts);
    }

    /**
     * @return array<int, string>
     */
    private function notes(string $type, bool $seniorityApplies): array
    {
        $notes = [
            'Cifras brutas: no se descuenta el ISR ni otras retenciones.',
            'No incluye salarios devengados no pagados, horas extra, bonos, PTU ni prestaciones superiores a la ley.',
        ];
        if ($type === self::DISMISSAL_UNJUSTIFIED) {
            $notes[] = 'En un despido injustificado también podrían corresponder salarios vencidos (hasta 12 meses) y, en su caso, intereses. Depende de cada juicio.';
            $notes[] = 'Existen plazos breves para reclamar (generalmente 2 meses para acción por despido). Conviene consultar cuanto antes.';
        }
        if ($type === self::RESIGNATION && ! $seniorityApplies) {
            $notes[] = 'En una renuncia voluntaria la prima de antigüedad solo se paga con 15 años o más de servicios.';
        }
        $notes[] = 'Esta estimación es orientativa y no constituye asesoría legal ni crea una relación abogado-cliente.';

        return $notes;
    }
}
