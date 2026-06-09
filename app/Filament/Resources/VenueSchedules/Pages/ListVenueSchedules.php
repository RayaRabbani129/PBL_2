<?php

namespace App\Filament\Resources\VenueSchedules\Pages;

use App\Filament\Resources\VenueSchedules\VenueScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVenueSchedules extends ListRecords
{
    protected static string $resource = VenueScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
