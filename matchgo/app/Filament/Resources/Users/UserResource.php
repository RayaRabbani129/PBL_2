<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \UnitEnum | null $navigationGroup = 'User Management';

    // protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    // 🔹 Form
    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    // 🔹 Table
    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    // 🔹 Relations
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // 🔹 Pages
    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    // 🔒 Proteksi akses menu
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }
}