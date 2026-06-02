<?php

namespace App\Filament\Resources\RefereeRentals\Pages;

use App\Filament\Resources\RefereeRentals\RefereeRentalResource;
use Filament\Resources\Pages\ListRecords;

class ListRefereeRentals extends ListRecords
{
    protected static string $resource = RefereeRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
