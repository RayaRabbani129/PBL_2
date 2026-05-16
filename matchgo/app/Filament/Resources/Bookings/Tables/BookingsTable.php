<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with([
                'match',
                'venue',
                'field',
                'team',
            ]))

            ->defaultSort('booking_date', 'desc')

            ->striped()

            ->columns([

                TextColumn::make('match.match_code')
                    ->label('Match')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-trophy')
                    ->placeholder('-'),

                TextColumn::make('team.name')
                    ->label('Team')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-shield-check')
                    ->weight('bold')
                    ->placeholder('-'),

                TextColumn::make('venue.name')
                    ->label('Venue')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-building-office-2')
                    ->description(fn ($record) => $record->field?->name ?? '-')
                    ->placeholder('-'),

                TextColumn::make('booking_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->badge()
                    ->color('warning')
                    ->sortable(),

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

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'finished' => 'info',
                        default => 'gray',
                    }),

                IconColumn::make('is_today')
                    ->label('Hari Ini')
                    ->state(function ($record) {

                        if (! $record->booking_date) {
                            return false;
                        }

                        return now()->toDateString() === \Carbon\Carbon::parse($record->booking_date)->toDateString();
                    })
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

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

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'finished' => 'Finished',
                    ]),

                SelectFilter::make('venue_id')
                    ->label('Venue')
                    ->relationship('venue', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('created_by')
                    ->label('Team')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('booking_date')
                    ->label('Filter Tanggal')
                    ->form([

                        DatePicker::make('from')
                            ->label('Dari'),

                        DatePicker::make('until')
                            ->label('Sampai'),
                    ])

                    ->query(function ($query, array $data) {

                        return $query
                            ->when(
                                $data['from'],
                                fn ($query, $date) => $query->whereDate('booking_date', '>=', $date),
                            )

                            ->when(
                                $data['until'],
                                fn ($query, $date) => $query->whereDate('booking_date', '<=', $date),
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

            ->searchPlaceholder('Cari match, venue, atau team...')

            ->emptyStateHeading('Belum ada booking')
            ->emptyStateDescription('Booking pertandingan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-calendar-days')

            ->paginated([10, 25, 50, 100]);
    }
}