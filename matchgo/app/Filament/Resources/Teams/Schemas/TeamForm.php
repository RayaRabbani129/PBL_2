<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('province')
                    ->required(),
                TextInput::make('latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('longitude')
                    ->required()
                    ->numeric(),
                Select::make('level')
                    ->options(['casual' => 'Casual', 'semi_pro' => 'Semi pro', 'competitive' => 'Competitive'])
                    ->default('casual')
                    ->required(),
                TextInput::make('total_matches')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_wins')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_losses')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_draws')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_goals_scored')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_goals_conceded')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('logo_path'),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
