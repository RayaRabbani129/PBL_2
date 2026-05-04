<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureAdminAccess;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Zinc,
            ])

            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => Blade::render('
                    <link rel="stylesheet" href="' . asset('css/admin-themes.css') . '">
                ')
            )
            // ->renderHook(
            //     PanelsRenderHook::HEAD_END,
            //     fn () => Blade::render('
            //         <link rel="preconnect" href="https://fonts.googleapis.com">
            //         <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

            //         <style>
            //             * {
            //                 font-family: "Plus Jakarta Sans", sans-serif;
            //             }

            //             /* Sidebar lebih clean */
            //             .fi-sidebar {
            //                 backdrop-filter: blur(12px);
            //                 background-color: rgba(255,255,255,0.85);
            //                 border-right: 1px solid #eee;
            //             }

            //             /* Topbar */
            //             .fi-topbar {
            //                 backdrop-filter: blur(12px);
            //                 background-color: rgba(255,255,255,0.85);
            //                 border-bottom: 1px solid #eee;
            //             }

            //             /* Card lebih modern */
            //             .fi-card {
            //                 border-radius: 16px;
            //                 box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            //             }

            //             /* Button lebih smooth */
            //             .fi-btn {
            //                 border-radius: 12px;
            //             }
            //         </style>
            //     ')
            // )

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Dashboard')
                    ->icon('heroicon-o-home'),

                NavigationGroup::make()
                    ->label('Team Management')
                    ->icon('heroicon-o-shield-check'),
                
                NavigationGroup::make()
                    ->label('Match Management')
                    ->icon('heroicon-o-trophy'),
                
                NavigationGroup::make()
                    ->label('Venue Management')
                    ->icon('heroicon-o-map-pin'),

                NavigationGroup::make()
                    ->label('System')
                    ->icon('heroicon-o-cog-6-tooth'),
                
                NavigationGroup::make()
                    ->label('User Management')
                    ->icon('heroicon-o-users'),
                
            ])

            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )

            ->pages([
                Dashboard::class,
            ])

            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

            ->widgets([
                // nanti bisa isi custom widget
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
                // EnsureAdminAccess::class,
            ]);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        dd('canAccessPanel called', auth()->user()?->getRoleNames());exit;
        return auth()->user()?->hasAnyRole(['super_admin', 'admin_field', 'auditor']);
    }
}