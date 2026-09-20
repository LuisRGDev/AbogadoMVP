<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use Filament\Widgets\ChartWidget;

class LeadsChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Solicitudes de los últimos 30 días';

    protected static ?string $maxHeight = '260px';

    protected int|string|array $columnSpan = ['md' => 2, 'xl' => 2];

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->startOfDay());
        $perDay = Contact::where('created_at', '>=', $days->first())->get()
            ->groupBy(fn (Contact $contact): string => $contact->created_at->format('Y-m-d'));

        return [
            'datasets' => [[
                'label' => 'Solicitudes',
                'data' => $days->map(fn ($day): int => $perDay->get($day->format('Y-m-d'), collect())->count())->all(),
                'backgroundColor' => '#C5A46D',
                'borderRadius' => 4,
            ]],
            'labels' => $days->map(fn ($day): string => $day->locale('es')->isoFormat('D MMM'))->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
