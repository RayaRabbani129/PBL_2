<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Profil User')
                    ->description('Data utama pengguna.')
                    ->icon('heroicon-o-user-circle')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ])
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('Masukkan nama lengkap')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->placeholder('contoh@email.com')
                                    ->required()
                                    ->unique(User::class, 'email', ignoreRecord: true)
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('No. HP')
                                    ->tel()
                                    ->placeholder('08xxxxxxxxxx')
                                    ->maxLength(20),

                                TextInput::make('city')
                                    ->label('Kota')
                                    ->placeholder('Contoh: Malang')
                                    ->maxLength(255),
                            ]),

                        Textarea::make('bio')
                            ->label('Bio')
                            ->placeholder('Tulis deskripsi singkat user...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Foto Profil')
                    ->description('Upload foto profil user.')
                    ->icon('heroicon-o-photo')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 4,
                    ])
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('profile')
                            ->visibility('public')
                            ->imageEditor()
                            ->avatar()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Keamanan')
                    ->description('Atur password dan verifikasi akun.')
                    ->icon('heroicon-o-lock-closed')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 6,
                    ])
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->placeholder(fn ($record) => $record ? 'Kosongkan jika tidak ingin mengganti password' : 'Masukkan password')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->minLength(8)
                            ->maxLength(255),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->seconds(false)
                            ->nullable(),
                    ])
                    ->collapsible(),

                Section::make('Role & Akses')
                    ->description('Tentukan role user dalam sistem.')
                    ->icon('heroicon-o-shield-check')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 6,
                    ])
                    ->schema([
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->helperText('Contoh: super_admin, admin_field, auditor, player.'),
                    ])
                    ->collapsible(),
            ]);
    }
}