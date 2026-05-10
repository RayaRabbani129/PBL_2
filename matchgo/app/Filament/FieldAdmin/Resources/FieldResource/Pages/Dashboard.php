<?php

namespace App\Filament\FieldAdmin\Pages;

use App\Filament\FieldAdmin\Widgets\FieldOverviewWidget;
use App\Filament\FieldAdmin\Widgets\FieldStatusWidget;
use App\Filament\FieldAdmin\Widgets\ScheduleAvailabilityWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public function getWidgets(): array
    {
        return [
            FieldOverviewWidget::class,
            FieldStatusWidget::class,
            ScheduleAvailabilityWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 'full',
            'sm'      => 'full',
            'md'      => 'full',
            'lg'      => 7,
            'xl'      => 7,
            '2xl'     => 7,
        ];
    }
}