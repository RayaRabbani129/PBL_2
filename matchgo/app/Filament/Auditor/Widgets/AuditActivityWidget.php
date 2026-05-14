<?php

namespace App\Filament\Auditor\Widgets;

use App\Models\MatchAudit;
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
            ->with(['match.homeTeam', 'match.awayTeam', 'match.venue', 'auditor'])
            ->latest('audited_at')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('match.match_code')
                ->label('Kode Match')
                ->prefix('M-')
                ->sortable()
                ->width('100px'),

            Tables\Columns\TextColumn::make('match.homeTeam.name')
                ->label('Home Team')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('match.awayTeam.name')
                ->label('Away Team')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('match.match_datetime')
                ->label('Tanggal Match')
                ->dateTime('d M Y H:i')
                ->sortable(),

            Tables\Columns\TextColumn::make('sportsmanship_rating')
                ->label('Rating Sportivitas')
                ->badge()
                ->formatStateUsing(fn ($state) => $state ? "{$state}/10" : '-')
                ->sortable(),

            Tables\Columns\BadgeColumn::make('match.status')
                ->label('Status Match')
                ->colors([
                    'primary' => 'matched',
                    'warning' => 'confirmed',
                    'info' => 'ongoing',
                    'success' => 'completed',
                    'danger' => 'cancelled',
                ])
                ->formatStateUsing(fn ($state) => match ($state) {
                    'matched' => 'Matched',
                    'confirmed' => 'Confirmed',
                    'ongoing' => 'Berlangsung',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $state,
                }),

            Tables\Columns\TextColumn::make('audited_at')
                ->label('Diaudit')
                ->since()
                ->sortable(),
        ];
    }

    public function getTable(): Table
    {
        return $this->table(
            Table::make($this)
                ->query($this->getTableQuery())
                ->columns($this->getTableColumns())
                ->striped()
                ->paginated([5, 10, 25])
                ->defaultPaginationPageOption(10)
                ->poll('60s')
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->striped()
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(10)
            ->poll('60s');
    }
}