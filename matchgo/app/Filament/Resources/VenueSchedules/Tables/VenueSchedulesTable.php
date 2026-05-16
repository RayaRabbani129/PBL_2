<?php

namespace App\Filament\Resources\VenueSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;

class VenueSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['venue', 'field']))
            ->defaultSort('date', 'desc')
            ->striped()
            ->columns([
                TextColumn::make('venue.name')
                    ->label('Venue')
                    ->icon('heroicon-m-building-office-2')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->venue?->city ?? '-')
                    ->placeholder('-'),

                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->icon('heroicon-m-squares-2x2')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('duration')
                    ->label('Durasi')
                    ->state(function ($record) {
                        if (! $record->start_time || ! $record->end_time) {
                            return '-';
                        }

                        $start = \Carbon\Carbon::parse($record->start_time);
                        $end = \Carbon\Carbon::parse($record->end_time);

                        return $start->diffInMinutes($end) . ' menit';
                    })
                    ->badge()
                    ->color('gray'),

                IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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

                SelectFilter::make('is_available')
                    ->label('Status Ketersediaan')
                    ->options([
                        1 => 'Tersedia',
                        0 => 'Tidak Tersedia',
                    ]),

                Filter::make('date')
                    ->label('Filter Tanggal')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal'),

                        DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn ($query, $date) => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn ($query, $date) => $query->whereDate('date', '<=', $date),
                            );
                    }),
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
            ->searchPlaceholder('Cari venue atau lapangan...')
            ->emptyStateHeading('Belum ada jadwal venue')
            ->emptyStateDescription('Jadwal venue dan lapangan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->paginated([10, 25, 50, 100]);
    }
}