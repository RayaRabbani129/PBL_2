<?php

namespace App\Filament\Auditor\Widgets;

use App\Models\MatchAudit;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AuditActivityWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Aktivitas Audit Match Terbaru';

    protected function getTableQuery(): Builder
    {
        return MatchAudit::query()
            ->with([
                'match.homeTeam',
                'match.awayTeam',
                'match.venue',
                'auditor',
            ])
            ->latest('audited_at');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('match.match_code')
                ->label('Kode Match')
                ->formatStateUsing(fn ($state) => $state ? 'M-' . $state : '-')
                ->badge()
                ->color('warning')
                ->icon('heroicon-m-hashtag')
                ->searchable()
                ->sortable()
                ->copyable(),

            Tables\Columns\TextColumn::make('match.homeTeam.name')
                ->label('Home Team')
                ->searchable()
                ->sortable()
                ->weight('bold')
                ->icon('heroicon-m-home')
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('match.awayTeam.name')
                ->label('Away Team')
                ->searchable()
                ->sortable()
                ->weight('bold')
                ->icon('heroicon-m-user-group')
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('match.venue.name')
                ->label('Venue')
                ->badge()
                ->color('gray')
                ->icon('heroicon-m-map-pin')
                ->searchable()
                ->sortable()
                ->placeholder('-')
                ->toggleable(),

            Tables\Columns\TextColumn::make('match.match_datetime')
                ->label('Tanggal Match')
                ->dateTime('d M Y • H:i')
                ->description(fn ($record) => $record->match?->match_datetime
                    ? \Carbon\Carbon::parse($record->match->match_datetime)->translatedFormat('l')
                    : '-'
                )
                ->icon('heroicon-m-calendar-days')
                ->sortable(),

            Tables\Columns\TextColumn::make('score')
                ->label('Score')
                ->getStateUsing(fn ($record) =>
                    ($record->match?->home_score ?? 0) . ' - ' . ($record->match?->away_score ?? 0)
                )
                ->badge()
                ->color('success')
                ->icon('heroicon-m-trophy'),

            Tables\Columns\TextColumn::make('sportsmanship_rating')
                ->label('Sportivitas')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? "{$state}/10" : '-')
                ->color(fn ($state) => match (true) {
                    $state >= 8 => 'success',
                    $state >= 5 => 'warning',
                    $state > 0 => 'danger',
                    default => 'gray',
                })
                ->sortable(),

            Tables\Columns\BadgeColumn::make('audit_result')
                ->label('Hasil Audit')
                ->state(function ($record) {
                    $reviews = [
                        $record->home_team_review,
                        $record->away_team_review,
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
                    'danger' => 'Cheating',
                ]),

            Tables\Columns\BadgeColumn::make('match.status')
                ->label('Status Match')
                ->colors([
                    'primary' => 'matched',
                    'warning' => 'confirmed',
                    'info' => 'ongoing',
                    'success' => 'completed',
                    'danger' => 'cancelled',
                    'gray' => 'scheduled',
                ])
                ->formatStateUsing(fn ($state) => match ($state) {
                    'matched' => 'Matched',
                    'confirmed' => 'Confirmed',
                    'ongoing' => 'Berlangsung',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    'scheduled' => 'Terjadwal',
                    default => ucfirst($state ?? '-'),
                }),

            Tables\Columns\TextColumn::make('auditor.name')
                ->label('Auditor')
                ->icon('heroicon-m-shield-check')
                ->placeholder('-')
                ->toggleable(),

            Tables\Columns\TextColumn::make('audited_at')
                ->label('Diaudit')
                ->since()
                ->description(fn ($record) => $record->audited_at
                    ? \Carbon\Carbon::parse($record->audited_at)->format('d M Y • H:i')
                    : '-'
                )
                ->sortable(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->striped()
            ->defaultSort('audited_at', 'desc')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(10)
            ->poll('30s')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('Belum ada aktivitas audit')
            ->emptyStateDescription('Aktivitas audit match terbaru akan tampil di sini.');
    }
}