<?php

namespace App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVenueSchedule extends EditRecord
{
    protected static string $resource = VenueScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
