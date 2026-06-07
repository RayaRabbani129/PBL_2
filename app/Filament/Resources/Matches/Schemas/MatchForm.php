<?php

namespace App\Filament\Resources\Matches\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Pertandingan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('match_code')
                                    ->label('Kode Match')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                DateTimePicker::make('match_datetime')
                                    ->label('Tanggal & Jam Match')
                                    ->required(),

                                Select::make('home_team_id')
                                    ->label('Home Team')
                                    ->relationship('homeTeam', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('away_team_id')
                                    ->label('Away Team')
                                    ->relationship('awayTeam', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('venue_id')
                                    ->label('Venue')
                                    ->relationship('venue', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('field_id')
                                    ->label('Lapangan')
                                    ->relationship('field', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('duration_minutes')
                                    ->label('Durasi Menit')
                                    ->numeric()
                                    ->default(60)
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Match')
                                    ->options([
                                        'pending' => 'Pending',
                                        'scheduled' => 'Scheduled',
                                        'awaiting_payment' => 'Awaiting Payment',
                                        'ongoing' => 'Ongoing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                TextInput::make('home_score')
                                    ->label('Skor Home')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('away_score')
                                    ->label('Skor Away')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('total_cost')
                                    ->label('Total Biaya')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Select::make('stats_processed')
                                    ->label('Statistik Diproses')
                                    ->options([
                                        1 => 'Ya',
                                        0 => 'Tidak',
                                    ])
                                    ->default(0),
                            ]),

                        Textarea::make('notes')
                            ->label('Catatan Match')
                            ->columnSpanFull(),
                    ]),

                Section::make('Booking')
                    ->description('Relasi hasOne dari Matches ke Booking.')
                    ->relationship('booking')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('venue_id')
                                    ->label('Venue Booking')
                                    ->relationship('venue', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('field_id')
                                    ->label('Lapangan Booking')
                                    ->relationship('field', 'name')
                                    ->searchable()
                                    ->preload(),

                                DatePicker::make('booking_date')
                                    ->label('Tanggal Booking'),

                                TextInput::make('start_time')
                                    ->label('Jam Mulai')
                                    ->type('time'),

                                TextInput::make('end_time')
                                    ->label('Jam Selesai')
                                    ->type('time'),

                                Select::make('status')
                                    ->label('Status Booking')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'cancelled' => 'Cancelled',
                                        'completed' => 'Completed',
                                    ])
                                    ->default('pending'),

                                Select::make('created_by')
                                    ->label('Dibuat Oleh / Team')
                                    ->relationship('team', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Section::make('Biaya Match')
                    ->description('Relasi hasOne dari Matches ke MatchCost.')
                    ->relationship('cost')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('total_venue_cost')
                                    ->label('Total Biaya Venue')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                TextInput::make('home_team_cost')
                                    ->label('Biaya Home Team')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                TextInput::make('away_team_cost')
                                    ->label('Biaya Away Team')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                TextInput::make('home_team_players')
                                    ->label('Jumlah Pemain Home')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('away_team_players')
                                    ->label('Jumlah Pemain Away')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('home_cost_per_player')
                                    ->label('Biaya Per Pemain Home')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                TextInput::make('away_cost_per_player')
                                    ->label('Biaya Per Pemain Away')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Select::make('is_finalized')
                                    ->label('Sudah Final?')
                                    ->options([
                                        1 => 'Ya',
                                        0 => 'Tidak',
                                    ])
                                    ->default(0),
                            ]),

                        Textarea::make('notes')
                            ->label('Catatan Biaya')
                            ->columnSpanFull(),
                    ]),

                Section::make('Verifikasi Match')
                    ->description('Relasi hasOne dari Matches ke MatchVerification.')
                    ->relationship('verification')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Status Verifikasi')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->default('pending'),

                                Select::make('verified_by')
                                    ->label('Diverifikasi Oleh')
                                    ->relationship('verifier', 'name')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('home_score')
                                    ->label('Skor Home Terverifikasi')
                                    ->numeric(),

                                TextInput::make('away_score')
                                    ->label('Skor Away Terverifikasi')
                                    ->numeric(),
                            ]),

                        Textarea::make('notes')
                            ->label('Catatan Verifikasi')
                            ->columnSpanFull(),
                    ]),

                Section::make('Audit Match')
                    ->description('Relasi hasMany dari Matches ke MatchAudit.')
                    ->schema([
                        Repeater::make('audits')
                            ->label('Riwayat Audit')
                            ->relationship('audits')
                            ->schema([
                                Select::make('status')
                                    ->label('Status Audit')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'revision' => 'Revision',
                                    ])
                                    ->required(),

                                Textarea::make('notes')
                                    ->label('Catatan')
                                    ->rows(2),

                                Hidden::make('updated_by')
                                    ->default(fn () => auth()->id()),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Audit'),
                    ]),

                Section::make('Penyewaan Wasit')
                    ->description('Informasi wasit untuk pertandingan ini')
                    ->relationship('refereeRental')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('referee_id')
                                    ->label('Wasit')
                                    ->relationship('referee', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('status')
                                    ->label('Status Sewa')
                                    ->options([
                                        'pending' => 'Menunggu Konfirmasi',
                                        'confirmed' => 'Terkonfirmasi',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ]),

                                TextInput::make('hourly_rate')
                                    ->label('Tarif Per Jam')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),

                                TextInput::make('total_hours')
                                    ->label('Total Jam')
                                    ->numeric()
                                    ->disabled(),

                                TextInput::make('rental_cost')
                                    ->label('Total Biaya Sewa')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),

                                Textarea::make('notes')
                                    ->label('Catatan')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
