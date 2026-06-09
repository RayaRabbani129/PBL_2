<?php

namespace App\Filament\Resources\NotificationRingtones\Pages;

use App\Filament\Resources\NotificationRingtones\NotificationRingtoneResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNotificationRingtone extends CreateRecord
{
    protected static string $resource = NotificationRingtoneResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ringtone berhasil ditambahkan';
    }
}