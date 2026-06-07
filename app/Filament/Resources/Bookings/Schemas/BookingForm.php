<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([

                Section::make('Informasi Booking')
                    ->description('Data utama booking pertandingan.')
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 7,
                    ])
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Select::make('match_id')
                                    ->label('Match')
                                    ->relationship('match', 'match_code')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required()
                                    ->placeholder('Pilih match'),

                                Select::make('created_by')
                                    ->label('Dibuat Oleh')
                                    ->relationship('team', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required()
                                    ->placeholder('Pilih team'),
                            ]),

                        Grid::make(2)
                            ->schema([

                                Select::make('venue_id')
                                    ->label('Venue')
                                    ->relationship('venue', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required()
                                    ->live()
                                    ->placeholder('Pilih venue'),

                                Select::make('field_id')
                                    ->label('Lapangan')
                                    ->relationship('field', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required()
                                    ->placeholder('Pilih lapangan'),
                            ]),

                        Grid::make(3)
                            ->schema([

                                DatePicker::make('booking_date')
                                    ->label('Tanggal Booking')
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->required(),

                                TimePicker::make('start_time')
                                    ->label('Jam Mulai')
                                    ->seconds(false)
                                    ->required(),

                                TimePicker::make('end_time')
                                    ->label('Jam Selesai')
                                    ->seconds(false)
                                    ->required()
                                    ->after('start_time'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Status Booking')
                    ->description('Atur status booking.')
                    ->icon('heroicon-o-check-badge')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 5,
                    ])
                    ->schema([

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'finished' => 'Finished',
                            ])
                            ->default('pending')
                            ->native(false)
                            ->required(),

                        Placeholder::make('booking_info')
                            ->label('Informasi')
                            ->content('Pastikan jadwal venue dan lapangan tidak bentrok dengan booking lain.'),

                    ])
                    ->collapsible(),
            ]);
    }
}