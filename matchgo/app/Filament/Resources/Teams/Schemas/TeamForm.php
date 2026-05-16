<?php

namespace App\Filament\Resources\Teams\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama Tim')
                    ->description('Data utama tim dan pemilik akun.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Owner / Pemilik Tim')
                                    ->relationship('owner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nama')
                                            ->required(),

                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->unique(User::class, 'email'),

                                        TextInput::make('password')
                                            ->label('Password')
                                            ->password()
                                            ->required()
                                            ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                                    ]),

                                TextInput::make('name')
                                    ->label('Nama Tim')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('level')
                                    ->label('Level Tim')
                                    ->options([
                                        'casual' => 'Casual',
                                        'semi_pro' => 'Semi Pro',
                                        'competitive' => 'Competitive',
                                    ])
                                    ->default('casual')
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Tim')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'banned' => 'Banned',
                                    ])
                                    ->default('active')
                                    ->required(),

                                TextInput::make('warning_points')
                                    ->label('Warning Points')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                DateTimePicker::make('banned_at')
                                    ->label('Tanggal Banned')
                                    ->nullable(),
                            ]),

                        Textarea::make('description')
                            ->label('Deskripsi Tim')
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('logo_path')
                            ->label('Logo Tim')
                            ->image()
                            ->disk('public')
                            ->directory('teams/logos')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Lokasi Tim')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('city')
                                    ->label('Kota')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('province')
                                    ->label('Provinsi')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->required(),
                            ]),
                    ]),

                Section::make('Anggota Tim')
                    ->description('Relasi dari Team ke TeamMember.')
                    ->schema([
                        Repeater::make('members')
                            ->label('Daftar Anggota')
                            ->relationship('members')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Anggota')
                                    ->required(),

                                Select::make('role')
                                    ->label('Role')
                                    ->options([
                                        'goalkeeper' => 'Goalkeeper',
                                        'defender' => 'Defender',
                                        'midfielder' => 'Midfielder',
                                        'striker' => 'Striker',
                                    ])
                                    ->default('striker')
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Anggota'),
                    ]),

                Section::make('Jadwal Tim')
                    ->description('Relasi dari Team ke TeamSchedule.')
                    ->schema([
                        Repeater::make('schedules')
                            ->label('Jadwal Ketersediaan')
                            ->relationship('schedules')
                            ->schema([
                                Select::make('day_of_week')
                                    ->label('Hari')
                                    ->options([
                                        'monday' => 'Senin',
                                        'tuesday' => 'Selasa',
                                        'wednesday' => 'Rabu',
                                        'thursday' => 'Kamis',
                                        'friday' => 'Jumat',
                                        'saturday' => 'Sabtu',
                                        'sunday' => 'Minggu',
                                    ])
                                    ->required(),

                                TextInput::make('start_time')
                                    ->label('Jam Mulai')
                                    ->type('time')
                                    ->required(),

                                TextInput::make('end_time')
                                    ->label('Jam Selesai')
                                    ->type('time')
                                    ->required(),

                                Select::make('is_available')
                                    ->label('Tersedia')
                                    ->options([
                                        1 => 'Ya',
                                        0 => 'Tidak',
                                    ])
                                    ->default(1)
                                    ->required(),
                            ])
                            ->columns(4)
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Jadwal'),
                    ]),

                Section::make('Statistik Tim')
                    ->description('Relasi dari Team ke TeamStat.')
                    ->schema([
                        Repeater::make('stats')
                            ->label('Statistik')
                            ->relationship('stats')
                            ->schema([
                                TextInput::make('total_matches')
                                    ->label('Total Match')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('wins')
                                    ->label('Menang')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('losses')
                                    ->label('Kalah')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('goals_scored')
                                    ->label('Gol Masuk')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('goals_conceded')
                                    ->label('Gol Kebobolan')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(5)
                            ->maxItems(1)
                            ->defaultItems(1)
                            ->deletable(false),
                    ]),

                Section::make('Log Status Tim')
                    ->description('Catatan perubahan status tim.')
                    ->schema([
                        Repeater::make('statusLogs')
                            ->label('Status Logs')
                            ->relationship('statusLogs')
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'banned' => 'Banned',
                                        'warning' => 'Warning',
                                    ])
                                    ->required(),

                                Textarea::make('reason')
                                    ->label('Alasan')
                                    ->rows(2),

                                Hidden::make('updated_by')
                                    ->default(fn () => auth()->id()),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Log Status'),
                    ]),
            ]);
    }
}