<?php

namespace App\Filament\Resources\VenueSchedules\Pages;

use App\Filament\Resources\VenueSchedules\VenueScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVenueSchedule extends EditRecord
{
    protected static string $resource = VenueScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
