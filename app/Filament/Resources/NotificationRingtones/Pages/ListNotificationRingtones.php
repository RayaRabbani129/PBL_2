<?php

namespace App\Filament\Resources\NotificationRingtones\Pages;

use App\Filament\Resources\NotificationRingtones\NotificationRingtoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotificationRingtones extends ListRecords
{
    protected static string $resource = NotificationRingtoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Ringtone')
                ->icon('heroicon-o-plus'),
        ];
    }
}