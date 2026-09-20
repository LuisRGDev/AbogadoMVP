<?php

namespace App\Filament\Widgets;

use App\Enums\ContactStatus;
use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Resumen de solicitudes';

    protected function getStats(): array
    {
        $pending = Contact::pending()->count();
        $upcoming = Contact::appointments()->whereDate('preferred_date', '>=', today())->where('status', '!=', ContactStatus::Closed)->count();
        $thisMonth = Contact::where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonth = Contact::whereBetween('created_at', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()])->count();
        $delta = $thisMonth - $lastMonth;

        $perDay = Contact::where('created_at', '>=', now()->subDays(6)->startOfDay())->get()
            ->groupBy(fn (Contact $contact): string => $contact->created_at->format('Y-m-d'));
        $spark = collect(range(6, 0))->map(fn (int $daysAgo): int => $perDay->get(now()->subDays($daysAgo)->format('Y-m-d'), collect())->count())->all();

        return [
            Stat::make('Pendientes de atender', $pending)
                ->description($pending === 0 ? 'Todo al día' : 'Sin respuesta todavía')
                ->descriptionIcon($pending === 0 ? 'heroicon-m-check-circle' : 'heroicon-m-clock')
                ->color($pending === 0 ? 'success' : 'warning')
                ->url(ContactResource::getUrl('index', ['tableFilters' => ['status' => ['value' => ContactStatus::Pending->value]]])),
            Stat::make('Citas próximas', $upcoming)
                ->description('Solicitadas para hoy o después')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->url(ContactResource::getUrl('index', ['tableFilters' => ['upcoming' => ['isActive' => true]]])),
            Stat::make('Solicitudes este mes', $thisMonth)
                ->description($delta === 0 ? 'Igual que el mes pasado' : ($delta > 0 ? "+{$delta} vs. el mes pasado" : "{$delta} vs. el mes pasado"))
                ->descriptionIcon($delta >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($delta >= 0 ? 'success' : 'gray')
                ->chart($spark),
        ];
    }
}
