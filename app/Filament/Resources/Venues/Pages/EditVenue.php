<?php

namespace App\Filament\Resources\Venues\Pages;

use App\Filament\Resources\Venues\VenueResource;
use App\Models\FieldAdminVenue;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVenue extends EditRecord
{
    protected static string $resource = VenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $fieldAdminId = $this->data['field_admin_id'] ?? null;

        FieldAdminVenue::where('venue_id', $this->record->id)
            ->delete();

        if ($fieldAdminId) {
            FieldAdminVenue::create([
                'user_id' => $fieldAdminId,
                'venue_id' => $this->record->id,
            ]);
        }
    }
}
