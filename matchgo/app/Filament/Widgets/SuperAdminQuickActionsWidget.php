<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SuperAdminQuickActionsWidget extends Widget
{
    protected string $view = 'filament.super-admin.widgets.super-admin-quick-actions-widget';

    protected int|string|array $columnSpan = 'full';
}