<?php

namespace App\Filament\Resources\Teams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TeamsTable
{
    protected static ?string $navigationGroup = 'Team Management';

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')

            ->striped()

            ->columns([

                /**
                 * TEAM
                 */
                ImageColumn::make('photo_path')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->height(52)
                    ->width(52)
                    ->defaultImageUrl(
                        'https://ui-avatars.com/api/?name=Team&background=F3F4F6&color=6B7280'
                    ),

                TextColumn::make('name')
                    ->label('Nama Tim')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->city ?: 'Tidak ada kota')
                    ->icon('heroicon-m-shield-check')
                    ->limit(28),

                /**
                 * OWNER
                 */
                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user-circle')
                    ->toggleable(),

                /**
                 * LEVEL
                 */
                TextColumn::make('level')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'gray',
                        'intermediate' => 'warning',
                        'advanced' => 'success',
                        'professional' => 'danger',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                /**
                 * STATUS
                 */
                IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->status === 'active'),

                /**
                 * MATCH STATS
                 */
                TextColumn::make('total_matches')
                    ->label('Match')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('total_wins')
                    ->label('Win')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                /**
                 * WIN RATE
                 */
                TextColumn::make('win_rate')
                    ->label('Win Rate')
                    ->state(function ($record) {
                        if ($record->total_matches <= 0) {
                            return '0%';
                        }

                        return round(
                            ($record->total_wins / $record->total_matches) * 100
                        ) . '%';
                    })
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        (int) $state >= 70 => 'success',
                        (int) $state >= 40 => 'warning',
                        default => 'danger',
                    }),

                /**
                 * CREATED
                 */
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                SelectFilter::make('level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                        'professional' => 'Professional',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
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