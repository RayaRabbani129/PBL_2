<?php

namespace App\Filament\Resources\MatchVerifications\Pages;

use App\Filament\Resources\MatchVerifications\MatchVerificationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMatchVerification extends EditRecord
{
    protected static string $resource = MatchVerificationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['verified_by'] = auth()->id();

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
