<?php

namespace App\Filament\Auditor\Resources\TeamMonitoringResource\Forms;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMonitoringForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | TEAM INFO
                |--------------------------------------------------------------------------
                */

                Section::make('Informasi Team')
                    ->icon('heroicon-o-users')
                    ->schema([

                        Grid::make(4)
                            ->schema([

                                Placeholder::make('team_name')
                                    ->label('Team')
                                    ->content(
                                        fn ($record) => $record?->name ?? '-'
                                    ),

                                Placeholder::make('city')
                                    ->label('City')
                                    ->content(
                                        fn ($record) => $record?->city ?? '-'
                                    ),

                                Placeholder::make('wins')
                                    ->label('Wins')
                                    ->content(
                                        fn ($record) =>
                                        $record?->stats?->wins ?? 0
                                    ),

                                Placeholder::make('losses')
                                    ->label('Losses')
                                    ->content(
                                        fn ($record) =>
                                        $record?->stats?->losses ?? 0
                                    ),

                            ]),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | TEAM STATUS
                |--------------------------------------------------------------------------
                */

                Section::make('Monitoring Team')
                    ->icon('heroicon-o-shield-exclamation')
                    ->schema([

                        Select::make('status')
                            ->label('Status Team')
                            ->native(false)
                            ->options([

                                'fair_play' =>
                                    '✅ Fair Play',

                                'warning' =>
                                    '⚠️ Warning',

                                'under_review' =>
                                    '🟠 Under Review',

                                'toxic_behavior' =>
                                    '🤬 Toxic Behavior',

                                'fake_player' =>
                                    '🕵️ Fake Player',

                                'violence' =>
                                    '🥊 Violence',

                                'cheating' =>
                                    '🚨 Cheating',

                                'match_fixing' =>
                                    '💰 Match Fixing',
                            ])
                            ->required(),

                        RichEditor::make('reason')
                            ->label('Catatan Auditor')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'redo',
                                'undo',
                            ])
                            ->columnSpanFull(),

                    ]),
            ]);
    }
}