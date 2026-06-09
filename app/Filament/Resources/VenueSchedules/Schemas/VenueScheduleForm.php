<?php

namespace App\Filament\Resources\VenueSchedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VenueScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Venue & Lapangan')
                    ->description('Pilih venue dan lapangan yang akan dijadwalkan.')
                    ->icon('heroicon-o-building-office-2')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 5,
                    ])
                    ->schema([
                        Select::make('venue_id')
                            ->label('Venue')
                            ->relationship('venue', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false)
                            ->placeholder('Pilih venue'),

                        Select::make('field_id')
                            ->label('Lapangan')
                            ->relationship('field', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih lapangan')
                            ->helperText('Kosongkan jika jadwal berlaku untuk seluruh venue.'),
                    ])
                    ->collapsible(),

                Section::make('Waktu Jadwal')
                    ->description('Atur tanggal dan jam ketersediaan.')
                    ->icon('heroicon-o-clock')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 7,
                    ])
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Tanggal')
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->required()
                                    ->placeholder('Pilih tanggal'),

                                TimePicker::make('start_time')
                                    ->label('Jam Mulai')
                                    ->seconds(false)
                                    ->required(),

                                TimePicker::make('end_time')
                                    ->label('Jam Selesai')
                                    ->seconds(false)
                                    ->after('start_time')
                                    ->required(),
                            ]),

                        Toggle::make('is_available')
                            ->label('Jadwal tersedia')
                            ->helperText('Matikan jika slot ini sedang ditutup atau tidak dapat dipesan.')
                            ->default(true)
                            ->required(),
                    ])
                    ->collapsible(),
            ]);
    }
}