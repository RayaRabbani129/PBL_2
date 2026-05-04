<?php

namespace App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVenueSchedule extends CreateRecord
{
    protected static string $resource = VenueScheduleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Jadwal berhasil ditambahkan';
    }
}
