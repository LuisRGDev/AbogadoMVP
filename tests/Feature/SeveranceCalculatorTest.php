<?php

namespace Tests\Feature;

use App\Services\SeveranceCalculator;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeveranceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private SeveranceCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->calculator = new SeveranceCalculator;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function input(array $overrides = []): array
    {
        return array_merge([
            'start_date' => '2022-03-01',
            'end_date' => '2025-03-01',
            'salary_amount' => 500,
            'salary_period' => 'daily',
            'separation_type' => 'injustificado',
            'zone' => 'general',
            'vacation_days_taken' => 0,
            'aguinaldo_days' => 15,
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function amount(array $result, string $key): float
    {
        foreach ($result['items'] as $item) {
            if ($item['key'] === $key) {
                return $item['amount'];
            }
        }

        return 0.0;
    }

    public function test_unjustified_dismissal_after_three_years(): void
    {
        $result = $this->calculator->calculate($this->input());

        $this->assertSame(3.0, $result['years_of_service']);
        // Aguinaldo: 15 días × 500 × 60/365
        $this->assertEqualsWithDelta(1232.88, $this->amount($result, 'aguinaldo'), 0.01);
        // Prima de antigüedad: 12 días × 3 años × 500 (tope 2 SMG = 630.08, no aplica)
        $this->assertEqualsWithDelta(18000.00, $this->amount($result, 'prima_antiguedad'), 0.01);
        // SDI = 500 × (1 + (15 + 18 × 0.25) / 365)
        $this->assertEqualsWithDelta(526.71, $result['integrated_daily_salary'], 0.01);
        $this->assertEqualsWithDelta(47403.90, $this->amount($result, 'indemnizacion_constitucional'), 0.01);
        $this->assertEqualsWithDelta(31602.60, $this->amount($result, 'veinte_dias'), 0.01);
        $this->assertEqualsWithDelta($result['subtotal_finiquito'] + $result['subtotal_liquidacion'], $result['total'], 0.01);
    }

    public function test_resignation_only_pays_the_settlement_for_less_than_fifteen_years(): void
    {
        $result = $this->calculator->calculate($this->input(['separation_type' => 'renuncia']));

        $this->assertSame(0.0, $this->amount($result, 'indemnizacion_constitucional'));
        $this->assertSame(0.0, $this->amount($result, 'prima_antiguedad'));
        $this->assertSame(0.0, $result['subtotal_liquidacion']);
        $this->assertGreaterThan(0, $result['subtotal_finiquito']);
    }

    public function test_resignation_after_fifteen_years_pays_seniority_premium(): void
    {
        $result = $this->calculator->calculate($this->input(['separation_type' => 'renuncia', 'start_date' => '2008-03-01']));

        $this->assertGreaterThan(0, $this->amount($result, 'prima_antiguedad'));
    }

    public function test_seniority_premium_salary_is_capped_at_two_minimum_wages(): void
    {
        $result = $this->calculator->calculate($this->input(['salary_amount' => 5000]));

        $cap = 2 * $this->calculator->minimumWage('general');
        $this->assertEqualsWithDelta(12 * 3 * $cap, $this->amount($result, 'prima_antiguedad'), 0.01);
    }

    public function test_salary_periods_are_converted_to_daily_salary(): void
    {
        $this->assertSame(1000.0, $this->calculator->calculate($this->input(['salary_amount' => 30000, 'salary_period' => 'monthly']))['daily_salary']);
        $this->assertSame(1000.0, $this->calculator->calculate($this->input(['salary_amount' => 15000, 'salary_period' => 'biweekly']))['daily_salary']);
        $this->assertSame(1000.0, $this->calculator->calculate($this->input(['salary_amount' => 7000, 'salary_period' => 'weekly']))['daily_salary']);
    }

    public function test_vacation_days_follow_the_labor_law_table(): void
    {
        $expected = [1 => 12, 2 => 14, 3 => 16, 4 => 18, 5 => 20, 6 => 22, 10 => 22, 11 => 24, 15 => 24, 16 => 26];
        foreach ($expected as $year => $days) {
            $this->assertSame($days, $this->calculator->vacationDaysForServiceYear($year), "Año de servicio {$year}");
        }
    }

    public function test_pending_vacations_are_prorated_and_reduced_by_days_taken(): void
    {
        $withNone = $this->calculator->calculate($this->input(['start_date' => '2024-01-01', 'end_date' => '2025-07-01']));
        $withAll = $this->calculator->calculate($this->input(['start_date' => '2024-01-01', 'end_date' => '2025-07-01', 'vacation_days_taken' => 30]));

        $this->assertGreaterThan(0, $this->amount($withNone, 'vacaciones'));
        $this->assertSame(0.0, $this->amount($withAll, 'vacaciones'));
        $this->assertEqualsWithDelta($this->amount($withNone, 'vacaciones') * 0.25, $this->amount($withNone, 'prima_vacacional'), 0.01);
    }

    public function test_the_page_calculates_and_shows_the_breakdown(): void
    {
        $this->post(route('tools.severance.calculate'), [
            'separation_type' => 'injustificado',
            'salary_amount' => 18000,
            'salary_period' => 'monthly',
            'start_date' => '2021-01-10',
            'end_date' => now()->toDateString(),
            'zone' => 'general',
            'aguinaldo_days' => 15,
            'vacation_days_taken' => 0,
        ])->assertRedirect(route('tools.severance').'#resultado');

        $this->followingRedirects()->get(route('tools.severance'))->assertOk();
    }

    public function test_the_form_validates_dates_and_amounts(): void
    {
        $this->post(route('tools.severance.calculate'), [
            'separation_type' => 'injustificado',
            'salary_amount' => -5,
            'salary_period' => 'monthly',
            'start_date' => '2025-06-01',
            'end_date' => '2024-01-01',
            'zone' => 'general',
        ])->assertSessionHasErrors(['salary_amount', 'start_date']);
    }
}
