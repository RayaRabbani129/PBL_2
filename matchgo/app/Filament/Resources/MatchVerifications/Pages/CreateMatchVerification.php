<?php

namespace App\Filament\Resources\MatchVerifications\Pages;

use App\Filament\Resources\MatchVerifications\MatchVerificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMatchVerification extends CreateRecord
{
    protected static string $resource = MatchVerificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['verified_by'] = auth()->id();

        if (! empty($data['match_id'])) {
            $match = \App\Models\Matches::find($data['match_id']);

            $data['score_team_a'] ??= $match?->home_score ?? 0;
            $data['score_team_b'] ??= $match?->away_score ?? 0;
        }

        return $data;
    }
}
