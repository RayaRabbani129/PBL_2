<?php

namespace App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource;
use App\Models\Field;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVenueSchedule extends EditRecord
{
    protected static string $resource = VenueScheduleResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $field = Field::findOrFail($data['field_id']);

        $data['venue_id'] = $field->venue_id;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Jadwal berhasil diperbarui';
    }
}