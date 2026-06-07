<?php

namespace App\Filament\FieldAdmin\Resources\FieldResource\Pages;

use App\Filament\FieldAdmin\Resources\FieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFields extends ListRecords
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Lapangan'),
        ];
    }
}
