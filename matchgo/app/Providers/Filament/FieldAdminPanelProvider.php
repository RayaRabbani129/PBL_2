<?php

namespace App\Providers\Filament;

use App\Filament\FieldAdmin\Widgets\FieldOverviewWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;

class FieldAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('field-admin')
            ->path('field-admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(
                in: app_path('Filament/FieldAdmin/Resources'),
                for: 'App\Filament\FieldAdmin\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/FieldAdmin/Pages'),
                for: 'App\Filament\FieldAdmin\Pages'
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/FieldAdmin/Widgets'),
                for: 'App\Filament\FieldAdmin\Widgets'
            )
            ->widgets([
                AccountWidget::class,
                FieldOverviewWidget::class,   // overview stats lapangan
            ])
            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return auth()->user()?->hasRole('admin_field');
    }
}
