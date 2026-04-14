<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class UsersTable
{
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email berhasil disalin!')
                    ->searchable(),

                BadgeColumn::make('roles.name')
                    ->label('Role')
                    ->colors([
                        'success' => 'admin',
                        'warning' => 'player',
                    ])
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('role')
                    ->label('Filter Role')
                    ->relationship('roles', 'name'),
            ])

            ->defaultSort('name', 'asc')

            ->searchPlaceholder('Cari user...')

            ->striped()

            ->paginated([10, 25, 50])

            ->emptyStateHeading('Belum ada user')
            ->emptyStateDescription('Silakan tambahkan user baru')
            ->emptyStateIcon('heroicon-o-user-plus')

            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil'),

                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->color('danger'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}