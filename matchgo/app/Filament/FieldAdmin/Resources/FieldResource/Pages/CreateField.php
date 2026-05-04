<?php

namespace App\Filament\FieldAdmin\Resources\FieldResource\Pages;

use App\Filament\FieldAdmin\Resources\FieldResource;
use Filament\Resources\Pages\CreateRecord;

class CreateField extends CreateRecord
{
    protected static string $resource = FieldResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Lapangan berhasil ditambahkan';
    }
}
