<?php

namespace App\Filament\Resources\NotificationRingtones\Pages;

use App\Filament\Resources\NotificationRingtones\NotificationRingtoneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNotificationRingtone extends EditRecord
{
    protected static string $resource = NotificationRingtoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Ringtone berhasil diperbarui';
    }
}