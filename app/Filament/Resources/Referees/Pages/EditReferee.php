<?php

namespace App\Filament\Resources\Referees\Pages;

use App\Filament\Resources\Referees\RefereeResource;
use Filament\Resources\Pages\EditRecord;

class EditReferee extends EditRecord
{
    protected static string $resource = RefereeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
