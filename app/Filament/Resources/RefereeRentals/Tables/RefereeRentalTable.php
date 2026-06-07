<?php

namespace App\Filament\Resources\RefereeRentals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RefereeRentalTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('match.match_code')
                    ->label('Kode Pertandingan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referee.name')
                    ->label('Wasit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referee.certification_level')
                    ->label('Sertifikasi')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('rental_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Mulai - Selesai')
                    ->state(function ($record) {
                        return "{$record->start_time} - {$record->end_time}";
                    })
                    ->sortable(),
                TextColumn::make('total_hours')
                    ->label('Durasi (Jam)')
                    ->numeric()
                    ->formatStateUsing(fn ($state): string => is_numeric($state) ? number_format($state, 1, ',', '.') : (string) $state)
                    ->sortable(),
                TextColumn::make('rental_cost')
                    ->label('Biaya (Rp)')
                    ->money('idr')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'info' => 'completed',
                        'gray' => 'cancelled',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'pending' => 'Menunggu',
                            'confirmed' => 'Terkonfirmasi',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            default => $state,
                        };
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'confirmed' => 'Terkonfirmasi',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                SelectFilter::make('referee_id')
                    ->label('Wasit')
                    ->relationship('referee', 'name')
                    ->searchable()
                    ->preload(),
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
            ->defaultSort('rental_date', 'desc');
    }
}
