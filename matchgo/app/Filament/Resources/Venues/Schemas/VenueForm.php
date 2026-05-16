<?php

namespace App\Filament\Resources\Venues\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Informasi Venue')
                    ->icon('heroicon-o-building-office-2')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ])
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Venue')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Galaxy Futsal Arena'),

                                TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->placeholder('08xxxxxxxxxx'),
                            ]),

                        Textarea::make('address')
                            ->label('Alamat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('city')
                                    ->label('Kota')
                                    ->required(),

                                TextInput::make('province')
                                    ->label('Provinsi')
                                    ->required(),
                            ]),

                        Textarea::make('description')
                            ->label('Deskripsi Venue')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Detail Venue')
                    ->icon('heroicon-o-information-circle')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 4,
                    ])
                    ->schema([
                        TextInput::make('price_per_hour')
                            ->label('Harga per Jam')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('capacity')
                            ->label('Kapasitas')
                            ->numeric()
                            ->default(14)
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->native(false)
                            ->required(),

                        Toggle::make('is_available')
                            ->label('Venue tersedia')
                            ->default(true),
                    ]),

                Section::make('Lokasi Venue')
                    ->icon('heroicon-o-map-pin')
                    ->columnSpan(12)
                    ->schema([
                        View::make('filament.forms.components.venue-map-picker')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->required()
                                    ->numeric()
                                    ->dehydrated(),

                                TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->required()
                                    ->numeric()
                                    ->dehydrated(),
                            ]),
                    ]),

                Section::make('Admin Lapangan')
                    ->icon('heroicon-o-user-circle')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 6,
                    ])
                    ->schema([
                        Select::make('field_admin_id')
                            ->label('Admin Lapangan')
                            ->options(
                                User::role('admin_field')->pluck('name', 'id')
                            )
                            ->formatStateUsing(fn ($record) => $record?->fieldAdminVenue?->user_id)
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ]),

                Section::make('Foto Venue')
                    ->icon('heroicon-o-photo')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 6,
                    ])
                    ->schema([
                        FileUpload::make('photo_path')
                            ->label('Foto Venue')
                            ->image()
                            ->directory('venues')
                            ->imageEditor()
                            ->disk('public')
                            ->visibility('public'),
                    ]),
            ]);
    }
}