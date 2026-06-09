<?php

namespace App\Filament\Auditor\Resources\MatchAuditResource\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatchAuditTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('match_datetime', 'desc')

            ->columns([
                TextColumn::make('match_code')
                    ->label('Kode Match')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-m-hashtag')
                    ->copyable(),

                TextColumn::make('homeTeam.name')
                    ->label('Home Team')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-home')
                    ->description(fn ($record) => 'Score: ' . ($record->home_score ?? 0)),

                TextColumn::make('awayTeam.name')
                    ->label('Away Team')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user-group')
                    ->description(fn ($record) => 'Score: ' . ($record->away_score ?? 0)),

                TextColumn::make('venue.name')
                    ->label('Venue')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-map-pin')
                    ->toggleable(),

                TextColumn::make('match_datetime')
                    ->label('Tanggal Match')
                    ->dateTime('d M Y • H:i')
                    ->sortable()
                    ->icon('heroicon-m-calendar-days')
                    ->description(fn ($record) => $record->match_datetime
                        ? \Carbon\Carbon::parse($record->match_datetime)->translatedFormat('l')
                        : '-'
                    ),

                TextColumn::make('score')
                    ->label('Score')
                    ->state(fn ($record) =>
                        ($record->home_score ?? 0) . ' - ' . ($record->away_score ?? 0)
                    )
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-trophy'),

                BadgeColumn::make('audit_status')
                    ->label('Audit')
                    ->state(function ($record) {
                        if (! $record->latestAudit) {
                            return 'Belum Audit';
                        }

                        $reviews = [
                            $record->latestAudit->home_team_review,
                            $record->latestAudit->away_team_review,
                        ];

                        if (
                            in_array('cheating', $reviews) ||
                            in_array('match_fixing', $reviews)
                        ) {
                            return 'Cheating';
                        }

                        if (
                            in_array('violence', $reviews) ||
                            in_array('fake_player', $reviews) ||
                            in_array('toxic_behavior', $reviews)
                        ) {
                            return 'Review';
                        }

                        return 'Fair';
                    })
                    ->colors([
                        'success' => 'Fair',
                        'warning' => 'Review',
                        'danger'  => 'Cheating',
                        'gray'    => 'Belum Audit',
                    ]),

                BadgeColumn::make('status')
                    ->label('Status Match')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'completed' => 'Selesai',
                        'ongoing' => 'Berlangsung',
                        'cancelled' => 'Dibatalkan',
                        'scheduled' => 'Terjadwal',
                        default => ucfirst($state ?? '-'),
                    })
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'ongoing',
                        'danger'  => 'cancelled',
                        'gray'    => 'scheduled',
                    ]),

                TextColumn::make('latestAudit.auditor.name')
                    ->label('Auditor')
                    ->placeholder('Belum ada')
                    ->icon('heroicon-m-shield-check')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('latestAudit.created_at')
                    ->label('Tanggal Audit')
                    ->dateTime('d M Y • H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->label('Status Match')
                    ->options([
                        'scheduled' => 'Terjadwal',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                SelectFilter::make('audit_status')
                    ->label('Status Audit')
                    ->options([
                        'not_audited' => 'Belum Audit',
                        'fair' => 'Fair',
                        'review' => 'Review',
                        'cheating' => 'Cheating',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;

                        return match ($value) {
                            'not_audited' => $query->whereDoesntHave('latestAudit'),

                            'fair' => $query->whereHas('latestAudit', function ($q) {
                                $q->whereNotIn('home_team_review', [
                                    'cheating',
                                    'match_fixing',
                                    'violence',
                                    'fake_player',
                                    'toxic_behavior',
                                ])->whereNotIn('away_team_review', [
                                    'cheating',
                                    'match_fixing',
                                    'violence',
                                    'fake_player',
                                    'toxic_behavior',
                                ]);
                            }),

                            'review' => $query->whereHas('latestAudit', function ($q) {
                                $q->where(function ($sub) {
                                    $sub->whereIn('home_team_review', [
                                        'violence',
                                        'fake_player',
                                        'toxic_behavior',
                                    ])->orWhereIn('away_team_review', [
                                        'violence',
                                        'fake_player',
                                        'toxic_behavior',
                                    ]);
                                });
                            }),

                            'cheating' => $query->whereHas('latestAudit', function ($q) {
                                $q->where(function ($sub) {
                                    $sub->whereIn('home_team_review', [
                                        'cheating',
                                        'match_fixing',
                                    ])->orWhereIn('away_team_review', [
                                        'cheating',
                                        'match_fixing',
                                    ]);
                                });
                            }),

                            default => $query,
                        };
                    }),

                Filter::make('match_date')
                    ->label('Tanggal Match')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari')
                            ->native(false),

                        DatePicker::make('until')
                            ->label('Sampai')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn ($q, $date) => $q->whereDate('match_datetime', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn ($q, $date) => $q->whereDate('match_datetime', '<=', $date)
                            );
                    }),
            ])
            ->filtersLayout(FiltersLayout::Dropdown)

            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Lihat Detail')
                        ->icon('heroicon-o-eye')
                        ->color('info'),

                    EditAction::make()
                        ->label(fn ($record) => $record->latestAudit ? 'Sudah Diaudit' : 'Audit')
                        ->icon(fn ($record) => $record->latestAudit
                            ? 'heroicon-o-check-circle'
                            : 'heroicon-o-pencil-square'
                        )
                        ->color(fn ($record) => $record->latestAudit ? 'gray' : 'warning')
                        ->disabled(fn ($record) => $record->latestAudit !== null),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray'),
            ])

            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('Belum ada data match')
            ->emptyStateDescription('Semua data pertandingan akan muncul di sini.');
    }
}