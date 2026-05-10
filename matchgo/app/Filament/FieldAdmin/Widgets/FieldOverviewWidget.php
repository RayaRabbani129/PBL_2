<?php

namespace App\Filament\FieldAdmin\Widgets;

use App\Models\Booking;
use App\Models\Field;
use App\Models\VenueSchedule;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FieldOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm'      => 'full',
        'md'      => 'full',
        'lg'      => 7,
        'xl'      => 7,
        '2xl'     => 7,
    ];

    protected ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $venueIds = auth()->user()
            ->managedVenues()
            ->pluck('venues.id');

        $fields    = Field::whereIn('venue_id', $venueIds);
        $schedules = VenueSchedule::whereIn('venue_id', $venueIds);

        $totalFields  = $fields->count();
        $activeFields = (clone $fields)
            ->where('status', 'active')
            ->where('is_available', true)
            ->count();

        $inactiveFields = $totalFields - $activeFields;

        $todaySchedules     = (clone $schedules)->whereDate('date', today())->count();
        $availableSchedules = (clone $schedules)->whereDate('date', today())->where('is_available', true)->count();
        $bookedSchedules    = $todaySchedules - $availableSchedules;

        $occupancyRate = $todaySchedules > 0
            ? round(($bookedSchedules / $todaySchedules) * 100)
            : 0;

        // Trend data for charts (last 7 days bookings – replace with real query if needed)
        $weeklyOccupancy = collect(range(6, 0))->map(function ($daysAgo) use ($venueIds) {
            $date  = now()->subDays($daysAgo)->toDateString();
            $total = VenueSchedule::whereIn('venue_id', $venueIds)->whereDate('date', $date)->count();
            $avail = VenueSchedule::whereIn('venue_id', $venueIds)->whereDate('date', $date)->where('is_available', true)->count();
            return $total > 0 ? round((($total - $avail) / $total) * 100) : 0;
        })->toArray();

        $weeklyBooked = collect(range(6, 0))->map(function ($daysAgo) use ($venueIds) {
            $date = now()->subDays($daysAgo)->toDateString();
            $total = VenueSchedule::whereIn('venue_id', $venueIds)->whereDate('date', $date)->count();
            $avail = VenueSchedule::whereIn('venue_id', $venueIds)->whereDate('date', $date)->where('is_available', true)->count();
            return $total - $avail;
        })->toArray();

        return [
            /**
             * TOTAL LAPANGAN
             */
            Stat::make('Total Lapangan', $totalFields)
                ->description("{$activeFields} aktif · {$inactiveFields} nonaktif")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-m-rectangle-group')
                ->chart([7, 8, 8, 9, 10, $activeFields, $totalFields]),

            /**
             * SLOT HARI INI
             */
            Stat::make('Slot Hari Ini', $todaySchedules)
                ->description("{$bookedSchedules} terisi · {$availableSchedules} tersedia")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning')
                ->icon('heroicon-m-clock')
                ->chart($weeklyBooked),

            /**
             * OCCUPANCY RATE
             */
            Stat::make('Occupancy Rate', "{$occupancyRate}%")
                ->description(
                    $occupancyRate >= 70
                        ? 'Tinggi — lapangan sangat diminati'
                        : ($occupancyRate >= 40 ? 'Sedang — masih ada ruang' : 'Rendah — perlu promosi')
                )
                ->descriptionIcon(
                    $occupancyRate >= 70
                        ? 'heroicon-m-fire'
                        : ($occupancyRate >= 40 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                )
                ->color(
                    $occupancyRate >= 70
                        ? 'success'
                        : ($occupancyRate >= 40 ? 'warning' : 'danger')
                )
                ->icon('heroicon-m-chart-bar')
                ->chart($weeklyOccupancy),

            /**
             * SLOT TERSEDIA
             */
            Stat::make('Slot Tersedia', $availableSchedules)
                ->description('Masih dapat dibooking hari ini')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info')
                ->icon('heroicon-m-check-badge')
                ->chart(array_reverse($weeklyBooked)),
        ];
    }
}