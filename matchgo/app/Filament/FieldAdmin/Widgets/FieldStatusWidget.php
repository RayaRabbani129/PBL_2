<?php

namespace App\Filament\FieldAdmin\Widgets;

use App\Models\Field;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FieldStatusWidget extends BaseWidget
{
    protected static ?string $heading = 'Manajemen Lapangan';

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '15s';

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
                Field::query()
                    ->whereIn('venue_id', $venueIds)
                    ->with('venue')
                    ->latest()
            )
            ->striped()
            ->paginated([6, 10, 25])
            ->defaultPaginationPageOption(6)
            ->columns([

                TextColumn::make('name')
                    ->label('Nama Lapangan')
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-m-rectangle-group'),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ])
                    ->icons([
                        'heroicon-m-check-circle' => 'active',
                        'heroicon-m-x-circle'     => 'inactive',
                    ])
                    ->formatStateUsing(fn (string $state) => $state === 'active' ? 'Aktif' : 'Nonaktif'),

                ToggleColumn::make('is_available')
                    ->label('Tersedia')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active'   => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label(fn ($record) => $record->status === 'active' ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn ($record) => $record->status === 'active'
                        ? 'heroicon-m-lock-closed'
                        : 'heroicon-m-lock-open')
                    ->color(fn ($record) => $record->status === 'active' ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => $record->status === 'active'
                        ? "Nonaktifkan {$record->name}?"
                        : "Aktifkan {$record->name}?")
                    ->modalDescription(fn ($record) => $record->status === 'active'
                        ? 'Lapangan tidak akan bisa dibooking setelah dinonaktifkan.'
                        : 'Lapangan akan kembali tersedia untuk booking.')
                    ->modalSubmitActionLabel('Ya, lanjutkan')
                    ->action(function ($record) {
                        $record->update([
                            'status' => $record->status === 'active' ? 'inactive' : 'active',
                        ]);
                    }),
            ])
            ->emptyStateHeading('Belum ada lapangan')
            ->emptyStateDescription('Tambahkan lapangan terlebih dahulu di menu Lapangan.')
            ->emptyStateIcon('heroicon-o-rectangle-group');
    }
}