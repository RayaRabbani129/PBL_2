<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SuperAdminHeroWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SuperAdminQuickActionsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public function getWidgets(): array
    {
        return [
            SuperAdminHeroWidget::class,
            SuperAdminQuickActionsWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}