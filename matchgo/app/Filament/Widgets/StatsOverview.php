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
            Stat::make('Total Teams', Team::count()),
            Stat::make('Total Venues', Venue::count()),
        ];
    }
}
