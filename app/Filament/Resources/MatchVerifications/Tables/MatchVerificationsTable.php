<?php

namespace App\Filament\Resources\MatchVerifications\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MatchVerificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with([
                'match.homeTeam',
                'match.awayTeam',
                'match.latestAudit.auditor',
                'verifier',
            ]))
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('match.match_code')
                    ->label('Kode Match')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('match.homeTeam.name')
                    ->label('Tim Kandang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('match.awayTeam.name')
                    ->label('Tim Tamu')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('score')
                    ->label('Skor')
                    ->state(fn ($record): string => "{$record->score_team_a} - {$record->score_team_b}")
                    ->badge()
                    ->color('gray'),

                TextColumn::make('match.latestAudit.auditor.name')
                    ->label('Auditor')
                    ->placeholder('Belum ada')
                    ->toggleable(),

                TextColumn::make('match.latestAudit.audited_at')
                    ->label('Diaudit')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum diaudit')
                    ->sortable(),

                TextColumn::make('status')
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

                TextColumn::make('verifier.name')
                    ->label('Diverifikasi oleh')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Update')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => $record->status !== 'verified' && (bool) $record->match?->latestAudit?->audited_at)
                    ->action(fn ($record) => $record->update([
                        'status' => 'verified',
                        'verified_by' => auth()->id(),
                    ])),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => $record->status !== 'rejected' && (bool) $record->match?->latestAudit?->audited_at)
                    ->action(fn ($record) => $record->update([
                        'status' => 'rejected',
                        'verified_by' => auth()->id(),
                    ])),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada verifikasi match')
            ->emptyStateDescription('Verifikasi dibuat untuk match completed yang sudah selesai diaudit.')
            ->emptyStateIcon('heroicon-o-shield-check');
    }
}
