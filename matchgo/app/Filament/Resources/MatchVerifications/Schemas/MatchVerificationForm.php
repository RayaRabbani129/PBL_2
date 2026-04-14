<?php

namespace App\Filament\Resources\MatchVerifications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MatchVerificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('match_id')
                    ->required()
                    ->numeric(),
                TextInput::make('score_team_a')
                    ->required()
                    ->numeric(),
                TextInput::make('score_team_b')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['valid' => 'Valid', 'cheating' => 'Cheating'])
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('verified_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
