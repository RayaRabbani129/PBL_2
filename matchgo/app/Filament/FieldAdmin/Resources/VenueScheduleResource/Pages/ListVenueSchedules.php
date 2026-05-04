<?php

namespace App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVenueSchedules extends ListRecords
{
    protected static string $resource = VenueScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jadwal'),
        ];
    }
}
