<?php

namespace App\Filament\FieldAdmin\Pages;

use App\Filament\FieldAdmin\Widgets\FieldStatusWidget;
use App\Filament\FieldAdmin\Widgets\HeroWidget;
use App\Filament\FieldAdmin\Widgets\QuickActionsWidget;
use App\Filament\FieldAdmin\Widgets\ScheduleAvailabilityWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public function getWidgets(): array
    {
        return [
            HeroWidget::class,
            FieldStatusWidget::class,
            ScheduleAvailabilityWidget::class,
            QuickActionsWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}