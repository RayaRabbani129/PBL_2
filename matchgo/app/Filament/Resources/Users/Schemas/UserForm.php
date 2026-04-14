<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Informasi User
                Section::make('Informasi User')
                    ->description('Data utama pengguna')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->placeholder('Masukkan nama lengkap')
                                    ->required(),

                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->placeholder('contoh@email.com')
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('No. HP')
                                    ->tel()
                                    ->placeholder('08xxxxxxxxxx'),

                                DateTimePicker::make('email_verified_at')
                                    ->label('Email Verified'),
                            ]),
                    ])
                    ->collapsible(),

                // 🔹 Security
                Section::make('Keamanan')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->revealable(),
                    ])
                    ->collapsible(),

                // 🔹 Role
                Section::make('Role & Akses')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])
                    ->collapsible(),
            ]);
    }
}