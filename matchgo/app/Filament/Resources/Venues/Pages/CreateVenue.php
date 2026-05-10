<?php

namespace App\Filament\Resources\Venues\Pages;

use App\Filament\Resources\Venues\VenueResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\FieldAdminVenue;

class CreateVenue extends CreateRecord
{
    protected static string $resource = VenueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $fieldAdminId = $this->data['field_admin_id'] ?? null;

        if ($fieldAdminId) {
            FieldAdminVenue::create([
                'user_id' => $fieldAdminId,
                'venue_id' => $this->record->id,
            ]);
        }
    }
}
