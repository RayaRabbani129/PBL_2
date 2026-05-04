<?php

namespace App\Filament\FieldAdmin\Widgets;

use App\Models\Field;
use App\Models\Venue;
use App\Models\VenueSchedule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class FieldOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $venueIds = auth()->user()->venues()->pluck('venues.id');

        $totalVenues = $venueIds->count();

        $totalFields = Field::whereIn('venue_id', $venueIds)->count();

        $activeFields = Field::whereIn('venue_id', $venueIds)
            ->where('status', 'active')
            ->where('is_available', true)
            ->count();

        $schedulesToday = VenueSchedule::whereIn('venue_id', $venueIds)
            ->whereDate('date', today())
            ->where('is_available', true)
            ->count();

        $schedulesThisWeek = VenueSchedule::whereIn('venue_id', $venueIds)
            ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('is_available', true)
            ->count();

        return [
            Stat::make('Total Venue', $totalVenues)
                ->description('Venue yang Anda kelola')
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('primary'),

            Stat::make('Total Lapangan', $totalFields)
                ->description("{$activeFields} lapangan aktif & tersedia")
                ->descriptionIcon('heroicon-o-rectangle-group')
                ->color('success'),

            Stat::make('Jadwal Tersedia Hari Ini', $schedulesToday)
                ->description("Total minggu ini: {$schedulesThisWeek} slot")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('warning'),
        ];
    }
}
