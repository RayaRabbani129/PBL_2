<?php

namespace App\Filament\FieldAdmin\Resources\FieldResource\Pages;

use App\Filament\FieldAdmin\Resources\FieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditField extends EditRecord
{
    protected static string $resource = FieldResource::class;

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
        return 'Lapangan berhasil diperbarui';
    }
}
