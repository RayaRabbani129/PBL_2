<?php

namespace App\Filament\Resources\MatchVerifications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MatchVerificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Verifikasi')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('match.match_code')
                            ->label('Kode Match')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                                'pending' => 'Menunggu',
                                'valid' => 'Terverifikasi',
                                'cheating' => 'Ditolak',
                                default => '-',
                            })
                            ->color(fn (?string $state): string => match ($state) {
                                'verified', 'valid' => 'success',
                                'rejected', 'cheating' => 'danger',
                                'pending' => 'warning',
                                default => 'gray',
                            }),

                        TextEntry::make('match.homeTeam.name')
                            ->label('Tim Kandang'),

                        TextEntry::make('match.awayTeam.name')
                            ->label('Tim Tamu'),

                        TextEntry::make('score')
                            ->label('Skor')
                            ->state(fn ($record): string => "{$record->score_team_a} - {$record->score_team_b}"),

                        TextEntry::make('verifier.name')
                            ->label('Diverifikasi oleh')
                            ->placeholder('-'),

                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Update')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
