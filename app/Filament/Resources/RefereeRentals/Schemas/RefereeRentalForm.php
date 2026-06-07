<?php

namespace App\Filament\Resources\RefereeRentals\Schemas;

use App\Models\Referee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class RefereeRentalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pertandingan')
                    ->description('Data pertandingan yang memerlukan wasit')
                    ->schema([
                        Select::make('match_id')
                            ->label('Pertandingan')
                            ->relationship('match', 'match_code')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (?string $operation) => $operation === 'edit'),
                    ]),

                Section::make('Data Wasit')
                    ->description('Pilih wasit untuk pertandingan')
                    ->schema([
                        Select::make('referee_id')
                            ->label('Wasit')
                            ->options(
                                Referee::where('is_available', true)
                                    ->orderByDesc('rating')
                                    ->get()
                                    ->mapWithKeys(fn ($referee) => [
                                        $referee->id => "{$referee->name} ({$referee->certification_level}) - Rating: " . number_format($referee->rating, 1, ',', '.') . "/5"
                                    ])
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (?int $state, Set $set, Get $get) {
                                if ($state) {
                                    $referee = Referee::find($state);
                                    $set('hourly_rate', $referee->hourly_rate);
                                }
                            }),
                    ]),

                Section::make('Jadwal Sewa')
                    ->description('Tanggal dan waktu sewa wasit')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('rental_date')
                                    ->label('Tanggal Sewa')
                                    ->required(),
                                TimePicker::make('start_time')
                                    ->label('Jam Mulai')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::calculateCost($get, $set);
                                    }),
                                TimePicker::make('end_time')
                                    ->label('Jam Selesai')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::calculateCost($get, $set);
                                    }),
                            ]),
                    ]),

                Section::make('Biaya')
                    ->description('Perhitungan biaya sewa')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('hourly_rate')
                                    ->label('Tarif/Jam (Rp)')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),
                                TextInput::make('total_hours')
                                    ->label('Total Jam')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),
                                TextInput::make('rental_cost')
                                    ->label('Total Biaya (Rp)')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),
                            ]),
                    ]),

                Section::make('Status & Catatan')
                    ->description('Status dan keterangan tambahan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Menunggu Konfirmasi',
                                        'confirmed' => 'Terkonfirmasi',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->default('pending')
                                    ->required(),
                                TextInput::make('notes')
                                    ->label('Catatan')
                                    ->maxLength(500),
                            ]),
                    ]),
            ]);
    }

    protected static function calculateCost(Get $get, Set $set): void
    {
        $startTime = $get('start_time');
        $endTime = $get('end_time');
        $hourlyRate = $get('hourly_rate');

        if ($startTime && $endTime && $hourlyRate) {
            $start = \Carbon\Carbon::parse($startTime);
            $end = \Carbon\Carbon::parse($endTime);
            $hours = $start->diffInMinutes($end) / 60;

            if ($hours > 0) {
                $set('total_hours', $hours);
                $set('rental_cost', $hours * $hourlyRate);
            }
        }
    }
}
