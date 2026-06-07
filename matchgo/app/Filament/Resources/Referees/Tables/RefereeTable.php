<?php

namespace App\Filament\Resources\Referees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RefereeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount([
                'rentals',
                'rentals as active_rentals_count' => fn ($query) => $query->whereIn('status', ['pending', 'confirmed']),
            ]))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Wasit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('certification_level')
                    ->label('Sertifikasi')
                    ->colors([
                        'gray' => 'basic',
                        'info' => 'intermediate',
                        'warning' => 'advanced',
                        'success' => 'professional',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('experience_years')
                    ->label('Pengalaman (Tahun)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hourly_rate')
                    ->label('Tarif/Jam (Rp)')
                    ->money('idr')
                    ->sortable(),
                ToggleColumn::make('is_available')
                    ->label('Tersedia'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric()
                    ->formatStateUsing(fn ($state): string => is_numeric($state) ? number_format($state, 1, ',', '.') : (string) $state)
                    ->sortable(),
                TextColumn::make('total_matches_refereed')
                    ->label('Pertandingan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('active_rentals_count')
                    ->label('Sewa Aktif')
                    ->badge()
                    ->color(fn ($state): string => ((int) $state) > 0 ? 'warning' : 'success')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_available')
                    ->label('Ketersediaan')
                    ->default(true),
                SelectFilter::make('certification_level')
                    ->label('Level Sertifikasi')
                    ->options([
                        'basic' => 'Basic',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                        'professional' => 'Professional',
                    ]),
                SelectFilter::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->preload()
                    ->options(
                        \App\Models\Referee::query()
                            ->whereNotNull('city')
                            ->where('city', '!=', '')
                            ->distinct()
                            ->orderBy('city')
                            ->pluck('city', 'city')
                    ),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('rating', 'desc');
    }
}
