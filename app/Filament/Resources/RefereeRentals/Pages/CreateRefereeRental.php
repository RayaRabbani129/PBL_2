<?php

namespace App\Filament\Resources\RefereeRentals\Pages;

use App\Filament\Resources\RefereeRentals\RefereeRentalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRefereeRental extends CreateRecord
{
    protected static string $resource = RefereeRentalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
