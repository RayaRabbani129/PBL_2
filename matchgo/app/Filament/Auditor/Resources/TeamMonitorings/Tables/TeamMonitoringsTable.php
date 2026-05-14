<?php

namespace App\Filament\Auditor\Resources\TeamMonitoringResource\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamMonitoringTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')

            ->columns([

                TextColumn::make('name')
                    ->label('Team')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('city')
                    ->searchable(),

                TextColumn::make('members_count')
                    ->label('Members')
                    ->counts('members')
                    ->badge(),

                TextColumn::make('stats.total_matches')
                    ->label('Matches')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('stats.wins')
                    ->label('Wins')
                    ->badge()
                    ->color('success'),

                TextColumn::make('stats.losses')
                    ->label('Losses')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('latestStatusLog.status')
                    ->label('Monitoring')
                    ->badge()
                    ->colors([

                        'success' => 'fair_play',

                        'warning' => [
                            'warning',
                            'under_review',
                        ],

                        'danger' => [
                            'toxic_behavior',
                            'fake_player',
                            'violence',
                            'cheating',
                            'match_fixing',
                        ],
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {

                        'fair_play' =>
                            'Fair Play',

                        'warning' =>
                            'Warning',

                        'under_review' =>
                            'Under Review',

                        'toxic_behavior' =>
                            'Toxic',

                        'fake_player' =>
                            'Fake Player',

                        'violence' =>
                            'Violence',

                        'cheating' =>
                            'Cheating',

                        'match_fixing' =>
                            'Match Fixing',

                        default =>
                            'No Status',
                    }),

            ])

            ->recordActions([

                EditAction::make()
                    ->label('Monitor'),

            ])

            ->emptyStateHeading('Belum ada team')
            ->emptyStateDescription('Data monitoring team akan muncul di sini.');
    }
}