<?php

namespace App\Filament\FieldAdmin\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected string $view = 'filament.field-admin.widgets.quick-actions-widget';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $panelUrl = filament()->getUrl();

        $actions = [
            [
                'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>',
                'label' => 'Tambah Lapangan',
                'url'   => $panelUrl . '/fields/create',
            ],
            [
                'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="12" y1="14" x2="12" y2="18"/><line x1="10" y1="16" x2="14" y2="16"/></svg>',
                'label' => 'Tambah Jadwal',
                'url'   => $panelUrl . '/venue-schedules/create',
            ],
            [
                'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
                'label' => 'Lihat Laporan',
                'url'   => $panelUrl . '/reports',
            ],
            [
                'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
                'label' => 'Atur Harga',
                'url'   => $panelUrl . '/pricing',
            ],
        ];

        return compact('actions');
    }
}