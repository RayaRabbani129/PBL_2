<?php

namespace App\Filament\Resources\Referees\Pages;

use App\Filament\Resources\Referees\RefereeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReferee extends CreateRecord
{
    protected static string $resource = RefereeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
