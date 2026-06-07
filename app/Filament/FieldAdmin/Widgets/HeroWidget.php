<?php

namespace App\Filament\FieldAdmin\Widgets;

use App\Models\Field;
use App\Models\VenueSchedule;
use Filament\Widgets\Widget;

class HeroWidget extends Widget
{
    protected string $view = 'filament.field-admin.widgets.hero-widget';

    protected static ?int $sort = 0;

    protected ?string $pollingInterval = '30s';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
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

        $todaySchedules     = (clone $schedules)->whereDate('date', today())->count();
        $availableSchedules = (clone $schedules)->whereDate('date', today())->where('is_available', true)->count();
        $bookedSchedules    = $todaySchedules - $availableSchedules;

        $occupancyRate = $todaySchedules > 0
            ? round(($bookedSchedules / $todaySchedules) * 100)
            : 0;

        $userName = auth()->user()->name;

        return compact(
            'totalFields',
            'activeFields',
            'todaySchedules',
            'bookedSchedules',
            'availableSchedules',
            'occupancyRate',
            'userName',
        );
    }
}