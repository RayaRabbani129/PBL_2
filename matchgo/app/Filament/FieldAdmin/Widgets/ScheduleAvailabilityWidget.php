<?php

namespace App\Filament\FieldAdmin\Widgets;

use App\Models\VenueSchedule;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ScheduleAvailabilityWidget extends BaseWidget
{
    protected static ?string $heading = 'Ketersediaan Jadwal';

    protected static ?int $sort = 3;

    protected static ?string $pollingInterval = '10s';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm'      => 'full',
        'md'      => 'full',
        'lg'      => 7,
        'xl'      => 7,
        '2xl'     => 7,
    ];

    public function table(Table $table): Table
    {
        $venueIds = auth()->user()
            ->managedVenues()
            ->pluck('venues.id');

        return $table
            ->query(
                VenueSchedule::query()
                    ->whereIn('venue_id', $venueIds)
                    ->orderBy('date')
                    ->orderBy('start_time')
            )
            ->striped()
            ->paginated([8, 15, 25])
            ->defaultPaginationPageOption(8)
            ->columns([
                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->icon('heroicon-m-play-circle')
                    ->iconColor('success'),

                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->icon('heroicon-m-stop-circle')
                    ->iconColor('danger'),

                BadgeColumn::make('is_available')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->is_available ? 'Tersedia' : 'Terisi')
                    ->colors([
                        'success' => 'Tersedia',
                        'danger'  => 'Terisi',
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => 'Tersedia',
                        'heroicon-m-x-circle'     => 'Terisi',
                    ]),

                ToggleColumn::make('is_available')
                    ->label('Toggle')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                Filter::make('today')
                    ->label('Hari ini saja')
                    ->query(fn (Builder $query) => $query->whereDate('date', today())),

                SelectFilter::make('is_available')
                    ->label('Ketersediaan')
                    ->options([
                        '1' => 'Tersedia',
                        '0' => 'Terisi',
                    ]),
            ])
            ->emptyStateHeading('Tidak ada jadwal')
            ->emptyStateDescription('Belum ada jadwal yang dibuat.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}