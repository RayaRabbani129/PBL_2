<?php

namespace App\Filament\Auditor\Resources\MatchAuditResource\Forms;

use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MatchAuditForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | MATCH INFORMATION
                |--------------------------------------------------------------------------
                */

                Section::make('Informasi Match')
                    ->description('Detail pertandingan yang sedang diaudit')
                    ->icon('heroicon-o-trophy')
                    ->collapsible()
                    ->schema([

                        Grid::make(4)
                            ->schema([

                                Placeholder::make('match_code')
                                    ->label('Kode Match')
                                    ->content(
                                        fn ($record) =>
                                        $record?->match_code ?? '-'
                                    ),

                                Placeholder::make('home_team')
                                    ->label('Home Team')
                                    ->content(
                                        fn ($record) =>
                                        $record?->homeTeam?->name ?? '-'
                                    ),

                                Placeholder::make('away_team')
                                    ->label('Away Team')
                                    ->content(
                                        fn ($record) =>
                                        $record?->awayTeam?->name ?? '-'
                                    ),

                                Placeholder::make('venue')
                                    ->label('Venue')
                                    ->content(
                                        fn ($record) =>
                                        $record?->venue?->name ?? '-'
                                    ),

                            ]),

                        Grid::make(3)
                            ->schema([

                                Placeholder::make('match_datetime')
                                    ->label('Tanggal Match')
                                    ->content(
                                        fn ($record) =>
                                        $record?->match_datetime
                                            ? Carbon::parse(
                                                $record->match_datetime
                                            )->format('d M Y • H:i')
                                            : '-'
                                    ),

                                Placeholder::make('duration')
                                    ->label('Durasi')
                                    ->content(
                                        fn ($record) =>
                                        ($record?->duration_minutes ?? 0)
                                        . ' Menit'
                                    ),

                                Placeholder::make('current_status')
                                    ->label('Status Saat Ini')
                                    ->content(
                                        fn ($record) =>
                                        ucfirst($record?->status ?? '-')
                                    ),

                            ]),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | MATCH RESULT
                |--------------------------------------------------------------------------
                */

                Section::make('Hasil Pertandingan')
                    ->description('Input hasil akhir pertandingan')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                TextInput::make('home_score')
                                    ->label('Score Home')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(
                                        fn ($record) =>
                                        $record?->home_score ?? 0
                                    )
                                    ->required(),

                                TextInput::make('away_score')
                                    ->label('Score Away')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(
                                        fn ($record) =>
                                        $record?->away_score ?? 0
                                    )
                                    ->required(),

                            ]),

                        Select::make('status')
                            ->label('Status Match')
                            ->native(false)
                            ->options([
                                'completed' => 'Completed',
                            ])
                            ->default('completed')
                            ->disabled()
                            ->dehydrated(false)
                            ->required(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | TEAM REVIEW
                |--------------------------------------------------------------------------
                */

                Section::make('Review Tim')
                    ->description('Review perilaku dan fair play kedua tim')
                    ->icon('heroicon-o-shield-check')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Select::make('home_team_review')
                                    ->label('Review Home Team')
                                    ->native(false)
                                    ->options([
                                        'fair_play'      => '✅ Fair Play',
                                        'warning'        => '⚠️ Warning',
                                        'under_review'   => '🟠 Under Review',
                                        'toxic_behavior' => '🤬 Toxic Behavior',
                                        'fake_player'    => '🕵️ Fake Player',
                                        'violence'       => '🥊 Violence',
                                        'cheating'       => '🚨 Cheating',
                                        'match_fixing'   => '💰 Match Fixing',
                                    ])
                                    ->default(
                                        fn ($record) =>
                                        $record?->audit?->home_team_review
                                        ?? 'fair_play'
                                    )
                                    ->required(),

                                Select::make('away_team_review')
                                    ->label('Review Away Team')
                                    ->native(false)
                                    ->options([
                                        'fair_play'      => '✅ Fair Play',
                                        'warning'        => '⚠️ Warning',
                                        'under_review'   => '🟠 Under Review',
                                        'toxic_behavior' => '🤬 Toxic Behavior',
                                        'fake_player'    => '🕵️ Fake Player',
                                        'violence'       => '🥊 Violence',
                                        'cheating'       => '🚨 Cheating',
                                        'match_fixing'   => '💰 Match Fixing',
                                    ])
                                    ->default(
                                        fn ($record) =>
                                        $record?->audit?->away_team_review
                                        ?? 'fair_play'
                                    )
                                    ->required(),

                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | SPORTSMANSHIP
                        |--------------------------------------------------------------------------
                        */

                        ToggleButtons::make('sportsmanship_rating')
                            ->label('Sportsmanship Rating')
                            ->inline()
                            ->options([
                                1 => '1',
                                2 => '2',
                                3 => '3',
                                4 => '4',
                                5 => '5',
                            ])
                            ->default(
                                fn ($record) =>
                                $record?->audit?->sportsmanship_rating
                            ),

                        /*
                        |--------------------------------------------------------------------------
                        | ALERT
                        |--------------------------------------------------------------------------
                        */

                        Placeholder::make('audit_alert')
                            ->label('Status Audit')
                            ->content(function (Get $get) {

                                $danger = [
                                    'cheating',
                                    'match_fixing',
                                ];

                                $warning = [
                                    'warning',
                                    'under_review',
                                    'toxic_behavior',
                                    'fake_player',
                                    'violence',
                                ];

                                $home = $get('home_team_review');
                                $away = $get('away_team_review');

                                if (
                                    in_array($home, $danger) ||
                                    in_array($away, $danger)
                                ) {
                                    return '🚨 Tim akan langsung dibanned.';
                                }

                                if (
                                    in_array($home, $warning) ||
                                    in_array($away, $warning)
                                ) {
                                    return '⚠️ Tim akan masuk monitoring auditor.';
                                }

                                return '✅ Pertandingan aman dan fair play.';
                            }),

                        /*
                        |--------------------------------------------------------------------------
                        | NOTES
                        |--------------------------------------------------------------------------
                        */

                        Textarea::make('audit_notes')
                            ->label('Catatan Auditor')
                            ->rows(5)
                            ->default(
                                fn ($record) =>
                                $record?->audit?->audit_notes
                            )
                            ->columnSpanFull(),

                        /*
                        |--------------------------------------------------------------------------
                        | GAME REVIEW
                        |--------------------------------------------------------------------------
                        */

                        RichEditor::make('game_review')
                            ->label('Review Permainan')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'redo',
                                'undo',
                            ])
                            ->default(
                                fn ($record) =>
                                $record?->audit?->game_review
                            )
                            ->columnSpanFull(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | AUDIT INFO
                |--------------------------------------------------------------------------
                */

                Section::make('Informasi Audit')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->schema([

                        Placeholder::make('auditor')
                            ->label('Auditor')
                            ->content(
                                fn ($record) =>
                                $record?->audit?->auditor?->name ?? '-'
                            ),

                        DateTimePicker::make('audited_at')
                            ->label('Waktu Audit')
                            ->seconds(false)
                            ->default(
                                fn ($record) =>
                                $record?->audit?->audited_at ?? now()
                            ),

                    ]),
            ]);
    }
}
