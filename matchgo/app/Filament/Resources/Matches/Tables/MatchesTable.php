<?php

namespace App\Filament\Resources\Matches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->with([
                    'homeTeam',
                    'awayTeam',
                    'venue',
                    'field',
                    'booking',
                    'cost',
                    'verification',
                    'latestAudit',
                ])
                ->withCount([
                    'audits',
                ])
            )
            ->defaultSort('match_datetime', 'desc')
            ->striped()
            ->columns([
                TextColumn::make('match_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('homeTeam.name')
                    ->label('Home Team')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-home'),

                TextColumn::make('awayTeam.name')
                    ->label('Away Team')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-shield-check'),

                TextColumn::make('score')
                    ->label('Skor')
                    ->state(fn ($record) => ($record->home_score ?? 0) . ' - ' . ($record->away_score ?? 0))
                    ->badge()
                    ->color('gray'),

                TextColumn::make('venue.name')
                    ->label('Venue')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('match_datetime')
                    ->label('Jadwal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('duration_minutes')
                    ->label('Durasi')
                    ->suffix(' menit')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label('Status Match')
                    ->badge()
                    ->sortable()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'scheduled' => 'info',
                        'ongoing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('booking.status')
                    ->label('Booking')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'primary',
                        default => 'gray',
                    })
                    ->placeholder('-'),

                TextColumn::make('cost.total_venue_cost')
                    ->label('Biaya Venue')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('Rp0'),

                IconColumn::make('cost.is_finalized')
                    ->label('Cost Final')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->toggleable(),

                TextColumn::make('verification.status')
                    ->label('Verifikasi')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->placeholder('-'),

                TextColumn::make('audits_count')
                    ->label('Audit')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                IconColumn::make('stats_processed')
                    ->label('Stats')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Match')
                    ->options([
                        'pending' => 'Pending',
                        'scheduled' => 'Scheduled',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('venue_id')
                    ->label('Venue')
                    ->relationship('venue', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('field_id')
                    ->label('Lapangan')
                    ->relationship('field', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('home_team_id')
                    ->label('Home Team')
                    ->relationship('homeTeam', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('away_team_id')
                    ->label('Away Team')
                    ->relationship('awayTeam', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->color('warning'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada match')
            ->emptyStateDescription('Data pertandingan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-trophy')
            ->paginated([10, 25, 50, 100]);
    }
}