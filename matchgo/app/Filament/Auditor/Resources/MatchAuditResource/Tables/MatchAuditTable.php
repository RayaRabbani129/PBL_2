<?php

namespace App\Filament\Auditor\Resources\MatchAuditResource\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MatchAuditTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('match_datetime', 'desc')

            ->columns([

                /*
                |--------------------------------------------------------------------------
                | MATCH CODE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('match_code')
                    ->label('Kode Match')
                    ->searchable()
                    ->badge()
                    ->color('warning'),

                /*
                |--------------------------------------------------------------------------
                | TEAM
                |--------------------------------------------------------------------------
                */

                TextColumn::make('homeTeam.name')
                    ->label('Home Team')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('awayTeam.name')
                    ->label('Away Team')
                    ->searchable()
                    ->weight('bold'),

                /*
                |--------------------------------------------------------------------------
                | VENUE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('venue.name')
                    ->label('Venue')
                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | DATETIME
                |--------------------------------------------------------------------------
                */

                TextColumn::make('match_datetime')
                    ->label('Tanggal')
                    ->dateTime('d M Y • H:i')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | SCORE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('score')
                    ->label('Score')
                    ->state(
                        fn ($record) =>
                        "{$record->home_score} - {$record->away_score}"
                    )
                    ->badge()
                    ->color('success'),

                /*
                |--------------------------------------------------------------------------
                | AUDIT STATUS
                |--------------------------------------------------------------------------
                */

                BadgeColumn::make('audit_status')
                    ->label('Audit')
                    ->state(function ($record) {

                        if (! $record->latestAudit) {
                            return 'Belum Audit';
                        }

                        $reviews = [
                            $record->latestAudit->home_team_review,
                            $record->latestAudit->away_team_review,
                        ];

                        if (
                            in_array('cheating', $reviews) ||
                            in_array('match_fixing', $reviews)
                        ) {
                            return 'Cheating';
                        }

                        if (
                            in_array('violence', $reviews) ||
                            in_array('fake_player', $reviews) ||
                            in_array('toxic_behavior', $reviews)
                        ) {
                            return 'Review';
                        }

                        return 'Fair';
                    })

                    ->colors([
                        'success' => 'Fair',
                        'warning' => 'Review',
                        'danger'  => 'Cheating',
                        'gray'    => 'Belum Audit',
                    ]),

                /*
                |--------------------------------------------------------------------------
                | MATCH STATUS
                |--------------------------------------------------------------------------
                */

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'ongoing',
                        'danger'  => 'cancelled',
                        'gray'    => 'scheduled',
                    ]),
            ])

            ->filters([
                //
            ])

            ->recordActions([

                EditAction::make()
                    ->label(function ($record) {

                        return $record->latestAudit
                            ? 'Sudah Diaudit'
                            : 'Audit';
                    })

                    ->icon(function ($record) {

                        return $record->latestAudit
                            ? 'heroicon-o-check-circle'
                            : 'heroicon-o-pencil-square';
                    })

                    ->color(function ($record) {

                        return $record->latestAudit
                            ? 'gray'
                            : 'warning';
                    })

                    ->disabled(fn ($record) => $record->latestAudit !== null),

            ])

            ->emptyStateHeading('Belum ada data match')
            ->emptyStateDescription(
                'Semua data pertandingan akan muncul di sini.'
            );
    }
}