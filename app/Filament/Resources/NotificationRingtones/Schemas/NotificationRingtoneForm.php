<?php

namespace App\Filament\Resources\NotificationRingtones\Schemas;

use App\Models\NotificationRingtone;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NotificationRingtoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Informasi Ringtone')
                    ->description('Atur suara notifikasi berdasarkan kategori.')
                    ->icon('heroicon-o-speaker-wave')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ])
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('category')
                                    ->label('Kategori Notifikasi')
                                    ->options([
                                        'booking' => 'Booking',
                                        'match' => 'Match',
                                        'verification' => 'Verification',
                                        'match_confirmed' => 'Match Confirmed',
                                        'match_challenge' => 'Match Challenge',
                                        'challenge_accepted' => 'Challenge Accepted',
                                        'challenge_rejected' => 'Challenge Rejected',
                                        'challenge_cancelled' => 'Challenge Cancelled',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->searchable()
                                    ->unique(
                                        table: NotificationRingtone::class,
                                        column: 'category',
                                        ignoreRecord: true
                                    ),

                                TextInput::make('name')
                                    ->label('Nama Ringtone')
                                    ->placeholder('Contoh: Booking Alert Sound')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        FileUpload::make('file_path')
                            ->label('File Ringtone')
                            ->acceptedFileTypes([
                                'audio/mpeg',
                                'audio/mp3',
                                'audio/wav',
                                'audio/ogg',
                            ])
                            ->disk('public')
                            ->directory('ringtones')
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            ->required()
                            ->helperText('Format yang didukung: MP3, WAV, OGG.'),

                        Toggle::make('is_active')
                            ->label('Aktifkan ringtone')
                            ->helperText('Jika aktif, ringtone ini dapat digunakan untuk notifikasi.')
                            ->default(true),
                    ])
                    ->collapsible(),

                Section::make('Panduan')
                    ->icon('heroicon-o-information-circle')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 4,
                    ])
                    ->schema([
                        Placeholder::make('info')
                            ->label('Catatan')
                            ->content('Setiap kategori hanya boleh memiliki satu ringtone. Jika ingin mengganti suara, edit data kategori yang sudah ada.'),

                        Placeholder::make('storage_info')
                            ->label('Lokasi File')
                            ->content('File akan disimpan ke storage/app/public/ringtones dan diakses melalui /storage/ringtones.'),
                    ])
                    ->collapsible(),
            ]);
    }
}