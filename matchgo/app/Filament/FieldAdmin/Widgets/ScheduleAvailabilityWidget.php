<?php

namespace App\Filament\FieldAdmin\Widgets;

use App\Models\VenueSchedule;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class ScheduleAvailabilityWidget extends Widget
{
    protected string $view = 'filament.field-admin.widgets.schedule-availability-widget';

    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '10s';

    protected int|string|array $columnSpan = 'full';

    /** Filter aktif: 'all' | 'available' | 'booked' */
    public string $filter = 'all';

    protected function getViewData(): array
    {
        $venueIds = auth()->user()
            ->managedVenues()
            ->pluck('venues.id');

        $query = VenueSchedule::whereIn('venue_id', $venueIds)
            ->whereDate('date', today())
            ->with('field')
            ->orderBy('date')
            ->orderBy('start_time');

        if ($this->filter === 'available') {
            $query->where('is_available', true);
        } elseif ($this->filter === 'booked') {
            $query->where('is_available', false);
        }

        $schedules = $query->get();

        // Hitung occupancy dari semua slot hari ini (bukan cuma yang difilter)
        $allToday        = VenueSchedule::whereIn('venue_id', $venueIds)->whereDate('date', today());
        $todayTotal      = (clone $allToday)->count();
        $todayAvailable  = (clone $allToday)->where('is_available', true)->count();
        $todayBooked     = $todayTotal - $todayAvailable;
        $occupancyRate   = $todayTotal > 0 ? round(($todayBooked / $todayTotal) * 100) : 0;

        return compact('schedules', 'todayTotal', 'todayBooked', 'todayAvailable', 'occupancyRate');
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function toggleAvailable(int $scheduleId): void
    {
        $schedule = VenueSchedule::findOrFail($scheduleId);
        $schedule->update(['is_available' => ! $schedule->is_available]);
    }
}