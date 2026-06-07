<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class FieldAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('field-admin')
            ->path('field-admin')
            ->login(fn () => redirect('/admin/login'))
            ->brandName('MATCHGO Admin Lapangan')
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::hex('#A3B14B'),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => $this->getDashboardStyles()
            )
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
            ->widgets([])
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
            ]);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return auth()->user()?->hasRole('admin_field');
    }

    private function getDashboardStyles(): string
    {
        return <<<'HTML'
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            :root {
                --accent:         #A3B14B;
                --accent-hover:   #8f9c40;
                --accent-dim:     rgba(163,177,75,0.12);
                --accent-border:  rgba(163,177,75,0.22);
                --card-radius:    14px;
            }

            html:not(.dark) {
                --surface-1:      #FFFFFF;
                --surface-2:      #F8F8F4;
                --surface-3:      #F1F1EB;
                --surface-4:      #E8E8E0;
                --txt-primary:    #1A1A17;
                --txt-secondary:  #4A4A42;
                --txt-muted:      #6E6E64;
                --txt-faint:      #9E9E93;
                --border-subtle:  rgba(0,0,0,0.07);
                --border-medium:  rgba(0,0,0,0.11);
                --topbar-bg:      rgba(248,248,244,0.92);
                --shadow-sm:      0 1px 3px rgba(0,0,0,0.07);
                --shadow-md:      0 4px 16px rgba(0,0,0,0.09);
                --card-shadow:    0 1px 3px rgba(0,0,0,0.05), 0 4px 16px rgba(0,0,0,0.07);
                --card-shadow-h:  0 6px 24px rgba(0,0,0,0.11), 0 2px 8px rgba(0,0,0,0.07);
                --accent-current: #7A8C2E;
            }

            html.dark {
                --surface-1:      #111111;
                --surface-2:      #161616;
                --surface-3:      #1C1C1C;
                --surface-4:      #242424;
                --txt-primary:    #F5F5F0;
                --txt-secondary:  #A8A29E;
                --txt-muted:      #78716C;
                --txt-faint:      #57534E;
                --border-subtle:  rgba(255,255,255,0.06);
                --border-medium:  rgba(255,255,255,0.10);
                --topbar-bg:      rgba(12,12,12,0.88);
                --shadow-sm:      0 1px 3px rgba(0,0,0,0.5);
                --shadow-md:      0 4px 16px rgba(0,0,0,0.6);
                --card-shadow:    0 1px 3px rgba(0,0,0,0.4), 0 4px 16px rgba(0,0,0,0.5);
                --card-shadow-h:  0 6px 24px rgba(0,0,0,0.7), 0 2px 8px rgba(0,0,0,0.4);
                --accent-current: #A3B14B;
            }

            .fi-body, .fi-body * { font-family: 'Inter', system-ui, sans-serif !important; }
            .fi-header-heading, .fi-logo, .fi-wi-stats-overview-stat-value { font-family: 'Manrope', system-ui, sans-serif !important; }

            .fi-sidebar { background: var(--surface-1) !important; border-right: 1px solid var(--border-subtle) !important; }
            .fi-sidebar-header { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; border-bottom: 1px solid var(--border-subtle) !important; }
            .fi-logo { font-weight: 800 !important; letter-spacing: -.03em !important; font-size: 1rem !important; color: var(--txt-primary) !important; }
            .fi-sidebar-group { padding-inline: .75rem !important; margin-top: .9rem !important; }
            .fi-sidebar-group-label { font-size: .625rem !important; text-transform: uppercase !important; letter-spacing: .12em !important; font-weight: 700 !important; color: var(--txt-faint) !important; padding-inline: .7rem !important; margin-bottom: .55rem !important; }
            .fi-sidebar-item-button { min-height: 44px !important; border-radius: 10px !important; border: 1px solid transparent !important; margin-bottom: 1px !important; color: var(--txt-muted) !important; font-size: .875rem !important; font-weight: 500 !important; transition: background .15s, color .15s, border-color .15s, transform .15s !important; }
            .fi-sidebar-item-button:hover { background: var(--accent-dim) !important; border-color: var(--accent-border) !important; color: var(--txt-secondary) !important; transform: translateX(2px); }
            .fi-sidebar-item-active .fi-sidebar-item-button { background: var(--accent-dim) !important; border-color: var(--accent-border) !important; color: var(--accent-current) !important; position: relative; }
            .fi-sidebar-item-active .fi-sidebar-item-button::before { content: ""; position: absolute; left: -1px; top: 8px; bottom: 8px; width: 3px; border-radius: 999px; background: var(--accent-current); }
            .fi-sidebar-item-icon { color: var(--txt-muted) !important; transition: color .15s !important; }
            .fi-sidebar-item-button:hover .fi-sidebar-item-icon, .fi-sidebar-item-active .fi-sidebar-item-icon { color: var(--accent-current) !important; }
            .fi-sidebar-item-label { font-size: .875rem !important; font-weight: 500 !important; letter-spacing: 0 !important; }
            .fi-sidebar-collapse-button { border-radius: 10px !important; color: var(--txt-muted) !important; }
            .fi-sidebar-collapse-button:hover { background: var(--accent-dim) !important; color: var(--accent-current) !important; }

            .fi-topbar { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); background: var(--topbar-bg) !important; border-bottom: 1px solid var(--border-subtle) !important; }
            .fi-user-menu-trigger { border-radius: 10px !important; transition: background .15s !important; }
            .fi-user-menu-trigger:hover { background: var(--accent-dim) !important; }
            .fi-global-search-field { border-radius: 10px !important; border: 1px solid var(--border-subtle) !important; background: var(--surface-2) !important; font-size: .8rem !important; color: var(--txt-primary) !important; }

            .fi-header-heading { font-size: 1.5rem !important; font-weight: 700 !important; letter-spacing: -.02em !important; color: var(--txt-primary) !important; }
            .fi-header-subheading { font-size: .875rem !important; color: var(--txt-muted) !important; }

            .fi-wi-stats-overview-stats-ctn { gap: 1rem !important; }
            .fi-wi-stats-overview-stat { border-radius: var(--card-radius) !important; border: 1px solid var(--border-subtle) !important; background: var(--surface-1) !important; box-shadow: var(--card-shadow) !important; transition: transform .2s ease, box-shadow .2s ease, border-color .2s !important; position: relative; overflow: hidden; }
            .fi-wi-stats-overview-stat::after { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--accent-current) 0%, transparent 75%); border-radius: var(--card-radius) var(--card-radius) 0 0; }
            .fi-wi-stats-overview-stat:hover { transform: translateY(-3px) !important; box-shadow: var(--card-shadow-h) !important; border-color: var(--accent-border) !important; }
            .fi-wi-stats-overview-stat-icon { background: var(--accent-dim) !important; color: var(--accent-current) !important; border-radius: 10px !important; }
            .fi-wi-stats-overview-stat-label { font-size: .725rem !important; font-weight: 500 !important; text-transform: uppercase !important; letter-spacing: .05em !important; color: var(--txt-muted) !important; }
            .fi-wi-stats-overview-stat-value { font-size: 1.75rem !important; font-weight: 800 !important; letter-spacing: -.04em !important; line-height: 1.1 !important; color: var(--txt-primary) !important; }
            .fi-wi-stats-overview-stat-description { font-size: .75rem !important; font-weight: 500 !important; color: var(--txt-muted) !important; }

            .fi-wi-table { border-radius: var(--card-radius) !important; border: 1px solid var(--border-subtle) !important; box-shadow: var(--card-shadow) !important; overflow: hidden !important; background: var(--surface-1) !important; }
            .fi-wi-table .fi-header { padding: 1rem 1.25rem .85rem !important; border-bottom: 1px solid var(--border-subtle) !important; background: var(--surface-1) !important; }
            .fi-wi-table .fi-header-heading { font-size: .95rem !important; font-weight: 700 !important; }
            .fi-ta-header { padding: .6rem 1rem !important; background: var(--surface-2) !important; border-bottom: 1px solid var(--border-subtle) !important; }
            .fi-input { border-radius: 10px !important; font-size: .875rem !important; font-family: 'Inter', sans-serif !important; }
            .fi-ta-header-cell { font-size: .725rem !important; font-weight: 600 !important; text-transform: uppercase !important; letter-spacing: .08em !important; color: var(--txt-faint) !important; padding-top: .55rem !important; padding-bottom: .55rem !important; background: var(--surface-2) !important; border-bottom: 1px solid var(--border-subtle) !important; }
            .fi-ta-row td { padding-top: .65rem !important; padding-bottom: .65rem !important; font-size: .875rem !important; color: var(--txt-secondary) !important; border-bottom: 1px solid var(--border-subtle) !important; transition: background .12s !important; }
            .fi-ta-row:last-child td { border-bottom: none !important; }
            .fi-ta-row:hover td { background: var(--accent-dim) !important; }
            .fi-ta-row.fi-ta-row-striped td { background: var(--surface-2) !important; }

            .fi-badge { border-radius: 99px !important; font-size: .69rem !important; font-weight: 600 !important; padding: 3px 10px !important; }
            .fi-btn { border-radius: 10px !important; font-size: .825rem !important; font-family: 'Inter', sans-serif !important; font-weight: 600 !important; transition: background .15s, transform .15s !important; }
            .fi-btn:hover { transform: translateY(-1px) !important; }
            .fi-ta-actions .fi-btn { border-radius: 8px !important; font-size: .775rem !important; padding: .28rem .7rem !important; }

            .fi-fo-field-wrp label { font-size: .8rem !important; font-weight: 600 !important; color: var(--txt-secondary) !important; }

            .fi-section { background: var(--surface-1) !important; border: 1px solid var(--border-subtle) !important; border-radius: var(--card-radius) !important; }
            .fi-section:hover { border-color: var(--border-medium) !important; }
            .fi-section-header { border-bottom: 1px solid var(--border-subtle) !important; padding: 1rem 1.25rem !important; background: var(--surface-1) !important; }

            .fi-modal-window { background: var(--surface-1) !important; border: 1px solid var(--border-medium) !important; border-radius: 16px !important; box-shadow: var(--shadow-md) !important; }
            .fi-modal-header { border-bottom: 1px solid var(--border-subtle) !important; background: var(--surface-1) !important; }
            .fi-modal-footer { border-top: 1px solid var(--border-subtle) !important; background: var(--surface-2) !important; }

            .fi-ta-footer { padding: .55rem 1rem !important; border-top: 1px solid var(--border-subtle) !important; background: var(--surface-2) !important; }
            .fi-pagination-item-btn { border-radius: 8px !important; font-size: .77rem !important; font-weight: 600 !important; }
            .fi-select-input { border-radius: 8px !important; font-size: .8rem !important; }

            .fi-dropdown-panel { background: var(--surface-1) !important; border: 1px solid var(--border-medium) !important; border-radius: 14px !important; box-shadow: var(--shadow-md) !important; padding: 6px !important; }
            .fi-dropdown-list-item { border-radius: 8px !important; font-size: .825rem !important; font-family: 'Inter', sans-serif !important; color: var(--txt-secondary) !important; transition: background .15s, color .15s !important; }
            .fi-dropdown-list-item:hover { background: var(--accent-dim) !important; color: var(--txt-primary) !important; }

            .fi-ta-empty-state { padding: 3rem 1.5rem !important; }
            .fi-ta-empty-state-heading { font-size: .95rem !important; font-weight: 700 !important; font-family: 'Manrope', sans-serif !important; color: var(--txt-secondary) !important; }
            .fi-ta-empty-state-description { font-size: .85rem !important; color: var(--txt-muted) !important; }

            * { scrollbar-width: thin; scrollbar-color: rgba(163,177,75,0.2) transparent; }
            *::-webkit-scrollbar { width: 5px; height: 5px; }
            *::-webkit-scrollbar-track { background: transparent; }
            *::-webkit-scrollbar-thumb { background: rgba(163,177,75,0.2); border-radius: 3px; }
            *::-webkit-scrollbar-thumb:hover { background: rgba(163,177,75,0.4); }

            *:focus-visible { outline: 2px solid var(--accent-current) !important; outline-offset: 2px !important; }

            .fi-wi-view { background: transparent !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
        </style>
        HTML;
    }
}
