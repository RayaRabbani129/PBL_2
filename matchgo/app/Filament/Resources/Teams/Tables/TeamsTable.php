<?php

namespace App\Filament\Resources\Teams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TeamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->with([
                    'owner',
                    'stats',
                    'latestStatusLog.updater',
                ])
                ->withCount([
                    'members',
                    'schedules',
                    'matchRequests',
                    'homeMatches',
                    'awayMatches',
                    'statusLogs',
                ])
            )
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->height(52)
                    ->width(52)
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Team&background=F3F4F6&color=6B7280'),

                TextColumn::make('name')
                    ->label('Nama Tim')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->city . ', ' . $record->province)
                    ->icon('heroicon-m-shield-check')
                    ->limit(28),

                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user-circle'),

                TextColumn::make('owner.email')
                    ->label('Email Owner')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->sortable()
                    ->color(fn (?string $state): string => match ($state) {
                        'casual' => 'gray',
                        'semi_pro' => 'warning',
                        'competitive' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'casual' => 'Casual',
                        'semi_pro' => 'Semi Pro',
                        'competitive' => 'Competitive',
                        default => '-',
                    }),

                IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->status === 'active'),

                TextColumn::make('warning_points')
                    ->label('Warning')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 5 => 'danger',
                        $state >= 3 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                TextColumn::make('members_count')
                    ->label('Members')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('schedules_count')
                    ->label('Schedules')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('stats.total_matches')
                    ->label('Match')
                    ->badge()
                    ->color('primary')
                    ->default(0)
                    ->sortable(),

                TextColumn::make('stats.wins')
                    ->label('Win')
                    ->badge()
                    ->color('success')
                    ->default(0)
                    ->sortable(),

                TextColumn::make('stats.losses')
                    ->label('Lose')
                    ->badge()
                    ->color('danger')
                    ->default(0)
                    ->sortable(),

                TextColumn::make('win_rate')
                    ->label('Win Rate')
                    ->state(function ($record) {
                        $total = $record->stats?->total_matches ?? 0;
                        $wins = $record->stats?->wins ?? 0;

                        if ($total <= 0) {
                            return '0%';
                        }

                        return round(($wins / $total) * 100) . '%';
                    })
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        (int) $state >= 70 => 'success',
                        (int) $state >= 40 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('total_matches_relasi')
                    ->label('Total Match Relasi')
                    ->state(fn ($record) => $record->home_matches_count + $record->away_matches_count)
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('match_requests_count')
                    ->label('Match Request')
                    ->badge()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('latestStatusLog.status')
                    ->label('Status Terakhir')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'banned' => 'danger',
                        'warning' => 'warning',
                        default => 'gray',
                    })
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('latestStatusLog.reason')
                    ->label('Alasan Status')
                    ->limit(35)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->label('Level')
                    ->options([
                        'casual' => 'Casual',
                        'semi_pro' => 'Semi Pro',
                        'competitive' => 'Competitive',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'banned' => 'Banned',
                    ]),
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
            ->emptyStateHeading('Belum ada tim')
            ->emptyStateDescription('Team yang dibuat user akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->paginated([10, 25, 50, 100]);
    }
}