<?php

namespace App\Filament\Resources\MatchVerifications\Pages;

use App\Filament\Resources\MatchVerifications\MatchVerificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMatchVerifications extends ListRecords
{
    protected static string $resource = MatchVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
