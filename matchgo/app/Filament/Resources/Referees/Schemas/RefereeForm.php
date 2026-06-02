<?php

namespace App\Filament\Resources\Referees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RefereeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Dasar')
                    ->description('Data pribadi wasit')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Wasit')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(20),
                                TextInput::make('city')
                                    ->label('Kota')
                                    ->maxLength(100),
                            ]),
                    ]),

                Section::make('Kualifikasi')
                    ->description('Pengalaman dan sertifikasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('experience_years')
                                    ->label('Tahun Pengalaman')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                Select::make('certification_level')
                                    ->label('Level Sertifikasi')
                                    ->options([
                                        'basic' => 'Basic',
                                        'intermediate' => 'Intermediate',
                                        'advanced' => 'Advanced',
                                        'professional' => 'Professional',
                                    ])
                                    ->default('basic')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Tarif & Ketersediaan')
                    ->description('Harga dan jadwal ketersediaan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('hourly_rate')
                                    ->label('Tarif Per Jam (Rp)')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->required(),
                                Toggle::make('is_available')
                                    ->label('Tersedia')
                                    ->default(true),
                            ]),
                    ]),

                Section::make('Statistik')
                    ->description('Informasi performa')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('rating')
                                    ->label('Rating')
                                    ->numeric()
                                    ->step(0.1)
                                    ->disabled()
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(5),
                                TextInput::make('total_matches_refereed')
                                    ->label('Total Pertandingan')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0),
                            ]),
                    ]),
            ]);
    }
}
