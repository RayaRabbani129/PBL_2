<?php

namespace App\Filament\Widgets;

use App\Models\Matches;
use App\Models\Team;
use App\Models\Venue;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Teams', Team::count())
                ->description('Jumlah semua tim')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([5, 10, 8, 15, 20, 18]) // dummy trend
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Total Venues', Venue::count())
                ->description('Lapangan tersedia')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info')
                ->chart([2, 5, 3, 7, 10, 12]),

            Stat::make('Total Matches', Matches::count())
                ->description('Jumlah pertandingan')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning')
                ->chart([1, 3, 5, 4, 6, 9]),
        ];
    }
}