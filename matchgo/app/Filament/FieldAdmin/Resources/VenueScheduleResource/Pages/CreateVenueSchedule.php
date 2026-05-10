<?php

namespace App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource;
use App\Models\Field;
use Filament\Resources\Pages\CreateRecord;

class CreateVenueSchedule extends CreateRecord
{
    protected static string $resource = VenueScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $field = Field::findOrFail($data['field_id']);

        $data['venue_id'] = $field->venue_id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Jadwal berhasil ditambahkan';
    }
}