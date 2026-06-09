<?php

namespace App\Filament\Resources\Venues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VenuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')

            ->striped()

            ->columns([

                // ─────────────────────────────
                // PHOTO
                // ─────────────────────────────
                ImageColumn::make('photo_path')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->height(52)
                    ->width(52)
                    ->defaultImageUrl(
                        'https://ui-avatars.com/api/?name=Venue&background=F3F4F6&color=6B7280'
                    ),

                // ─────────────────────────────
                // VENUE INFO
                // ─────────────────────────────
                TextColumn::make('name')
                    ->label('Venue')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) =>
                        $record->city . ', ' . $record->province
                    )
                    ->wrap(),

                // ─────────────────────────────
                // PRICE
                // ─────────────────────────────
                TextColumn::make('price_per_hour')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                // ─────────────────────────────
                // CAPACITY
                // ─────────────────────────────
                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' pemain')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-users'),

                // ─────────────────────────────
                // PHONE
                // ─────────────────────────────
                TextColumn::make('phone')
                    ->label('Kontak')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Nomor disalin')
                    ->toggleable(),

                // ─────────────────────────────
                // STATUS
                // ─────────────────────────────
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'active' => 'heroicon-m-check-circle',
                        'inactive' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                // ─────────────────────────────
                // AVAILABLE
                // ─────────────────────────────
                IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-badge')
                    ->falseIcon('heroicon-m-x-mark')
                    ->trueColor(Color::Green)
                    ->falseColor(Color::Red),

                // ─────────────────────────────
                // CREATED BY
                // ─────────────────────────────
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-user')
                    ->toggleable(),

                // ─────────────────────────────
                // CREATED DATE
                // ─────────────────────────────
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->sortable()
                    ->color('gray'),

            ])

            // ─────────────────────────────
            // FILTERS
            // ─────────────────────────────
            ->filters([

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),

                SelectFilter::make('is_available')
                    ->label('Ketersediaan')
                    ->options([
                        1 => 'Tersedia',
                        0 => 'Tidak tersedia',
                    ]),

            ])

            // ─────────────────────────────
            // ACTIONS
            // ─────────────────────────────
            ->recordActions([

                EditAction::make()
                    ->iconButton()
                    ->color('warning'),

            ])

            // ─────────────────────────────
            // BULK ACTIONS
            // ─────────────────────────────
            ->toolbarActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ])

            // ─────────────────────────────
            // EMPTY STATE
            // ─────────────────────────────
            ->emptyStateHeading('Belum ada venue')
            ->emptyStateDescription('Venue futsal yang kamu buat akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-map-pin');
    }
}