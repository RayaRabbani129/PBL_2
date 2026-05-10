<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use App\Models\User;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Venue')
                    ->icon('heroicon-o-building-office-2')
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

                    ])
                    ->columns(1),

                Section::make('Lokasi Venue')
                    ->icon('heroicon-o-map-pin')
                    ->schema([

                        // View::make('filament.forms.components.venue-map-picker')
                        //     ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([

                                TextInput::make('latitude')
                                    ->required()
                                    ->numeric()
                                    // ->readOnly()
                                    ->dehydrated(),

                                TextInput::make('longitude')
                                    ->required()
                                    ->numeric()
                                    // ->readOnly()
                                    ->dehydrated(),

                            ]),

                    ]),

                Section::make('Detail Venue')
                    ->icon('heroicon-o-information-circle')
                    ->schema([

                        Grid::make(3)
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
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active')
                                    ->native(false)
                                    ->required(),

                            ]),

                        Toggle::make('is_available')
                            ->label('Venue tersedia')
                            ->default(true),

                    ]),

                Select::make('field_admin_id')
                    ->label('Admin Lapangan')
                    ->options(
                        User::role('admin_field')
                            ->pluck('name', 'id')
                    )
                    ->formatStateUsing(fn ($record) =>
                        $record?->fieldAdminVenue?->user_id
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),

                Section::make('Foto Venue')
                    ->icon('heroicon-o-photo')
                    ->schema([

                        FileUpload::make('photo_path')
                            ->label('Foto Venue')
                            ->image()
                            ->directory('venues')
                            ->imageEditor()
                            ->disk('public')
                            ->visibility('public')

                    ]),

            ]);
    }
}