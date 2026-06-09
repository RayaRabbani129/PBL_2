<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('roles'))
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->columns([
                ImageColumn::make('photo')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->height(46)
                    ->width(46)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name ?? 'User') . '&background=A3B14B&color=111111'),

                TextColumn::make('name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user-circle')
                    ->description(fn ($record) => $record->email)
                    ->limit(28),

                TextColumn::make('phone')
                    ->label('No. HP')
                    ->icon('heroicon-m-phone')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('city')
                    ->label('Kota')
                    ->icon('heroicon-m-map-pin')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->separator(',')
                    ->color(fn ($state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin_field' => 'warning',
                        'auditor' => 'info',
                        'player' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('email_verified_at')
                    ->label('Verifikasi Email')
                    ->badge()
                    ->state(fn ($record) => $record->email_verified_at ? 'Verified' : 'Unverified')
                    ->color(fn ($state) => $state === 'Verified' ? 'success' : 'warning')
                    ->sortable(),

                TextColumn::make('team.name')
                    ->label('Team')
                    ->icon('heroicon-m-shield-check')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('venues_count')
                    ->label('Venue')
                    ->counts('venues')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime('d M Y')
                    ->since()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Update Terakhir')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Filter Role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                SelectFilter::make('email_verified_at')
                    ->label('Status Verifikasi')
                    ->options([
                        'verified' => 'Verified',
                        'unverified' => 'Unverified',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'verified' => $query->whereNotNull('email_verified_at'),
                            'unverified' => $query->whereNull('email_verified_at'),
                            default => $query,
                        };
                    }),
            ])
            ->searchPlaceholder('Cari nama, email, no HP, kota...')
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('Belum ada user')
            ->emptyStateDescription('User yang terdaftar akan muncul di halaman ini.')
            ->emptyStateIcon('heroicon-o-user-plus')
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->color('warning'),

                DeleteAction::make()
                    ->iconButton()
                    ->color('danger'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}