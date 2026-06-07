<?php

namespace App\Filament\Resources\NotificationRingtones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NotificationRingtonesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('category', 'asc')
            ->striped()
            ->columns([
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->color(fn (?string $state): string => match ($state) {
                        'booking' => 'primary',
                        'match' => 'success',
                        'verification' => 'warning',
                        'match_confirmed' => 'success',
                        'match_challenge' => 'info',
                        'challenge_accepted' => 'success',
                        'challenge_rejected' => 'danger',
                        'challenge_cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'booking' => 'Booking',
                        'match' => 'Match',
                        'verification' => 'Verification',
                        'match_confirmed' => 'Match Confirmed',
                        'match_challenge' => 'Match Challenge',
                        'challenge_accepted' => 'Challenge Accepted',
                        'challenge_rejected' => 'Challenge Rejected',
                        'challenge_cancelled' => 'Challenge Cancelled',
                        default => '-',
                    }),

                TextColumn::make('name')
                    ->label('Nama Ringtone')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-musical-note'),

                TextColumn::make('preview')
                    ->label('Preview')
                    ->html()
                    ->state(function ($record) {
                        if (! $record->file_path) {
                            return '-';
                        }

                        $url = asset('storage/' . $record->file_path);

                        return '
                            <audio controls preload="none" style="width: 190px; height: 34px;">
                                <source src="' . e($url) . '">
                                Browser tidak mendukung audio.
                            </audio>
                        ';
                    }),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'booking' => 'Booking',
                        'match' => 'Match',
                        'verification' => 'Verification',
                        'match_confirmed' => 'Match Confirmed',
                        'match_challenge' => 'Match Challenge',
                        'challenge_accepted' => 'Challenge Accepted',
                        'challenge_rejected' => 'Challenge Rejected',
                        'challenge_cancelled' => 'Challenge Cancelled',
                    ]),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Nonaktif',
                    ]),
            ])
            ->searchPlaceholder('Cari kategori atau nama ringtone...')
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->color('warning'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada ringtone')
            ->emptyStateDescription('Tambahkan ringtone untuk setiap kategori notifikasi.')
            ->emptyStateIcon('heroicon-o-speaker-wave')
            ->paginated([10, 25, 50]);
    }
}