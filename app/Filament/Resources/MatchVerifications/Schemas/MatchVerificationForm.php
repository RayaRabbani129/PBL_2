<?php

namespace App\Filament\Resources\MatchVerifications\Schemas;

use App\Models\Matches;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class MatchVerificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Verifikasi Match')
                    ->description('Super admin memverifikasi hasil match setelah auditor menyelesaikan audit.')
                    ->columns(2)
                    ->schema([
                        Select::make('match_id')
                            ->label('Match yang Sudah Diaudit')
                            ->relationship(
                                name: 'match',
                                titleAttribute: 'match_code',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->where('status', 'completed')
                                    ->whereHas('audits', fn (Builder $auditQuery) => $auditQuery->whereNotNull('audited_at'))
                                    ->with(['homeTeam', 'awayTeam'])
                                    ->orderByDesc('match_datetime')
                            )
                            ->getOptionLabelFromRecordUsing(fn (Matches $record): string => sprintf(
                                '%s - %s vs %s (%s - %s)',
                                $record->match_code,
                                $record->homeTeam?->name ?? 'Tim Kandang',
                                $record->awayTeam?->name ?? 'Tim Tamu',
                                $record->home_score ?? 0,
                                $record->away_score ?? 0,
                            ))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('score_team_a')
                            ->label('Skor Tim Kandang')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('score_team_b')
                            ->label('Skor Tim Tamu')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        Select::make('status')
                            ->label('Status Verifikasi')
                            ->options([
                                'pending' => 'Menunggu',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('verified')
                            ->required(),

                        Hidden::make('verified_by')
                            ->default(fn () => auth()->id()),

                        Textarea::make('notes')
                            ->label('Catatan Verifikasi')
                            ->placeholder('Tambahkan catatan jika skor ditolak atau ada koreksi dari audit.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
