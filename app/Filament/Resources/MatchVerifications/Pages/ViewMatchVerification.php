<?php

namespace App\Filament\Resources\MatchVerifications\Pages;

use App\Filament\Resources\MatchVerifications\MatchVerificationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMatchVerification extends ViewRecord
{
    protected static string $resource = MatchVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
