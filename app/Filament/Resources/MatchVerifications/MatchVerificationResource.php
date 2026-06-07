<?php

namespace App\Filament\Resources\MatchVerifications;

use App\Filament\Resources\MatchVerifications\Pages\CreateMatchVerification;
use App\Filament\Resources\MatchVerifications\Pages\EditMatchVerification;
use App\Filament\Resources\MatchVerifications\Pages\ListMatchVerifications;
use App\Filament\Resources\MatchVerifications\Pages\ViewMatchVerification;
use App\Filament\Resources\MatchVerifications\Schemas\MatchVerificationForm;
use App\Filament\Resources\MatchVerifications\Schemas\MatchVerificationInfolist;
use App\Filament\Resources\MatchVerifications\Tables\MatchVerificationsTable;
use App\Models\MatchVerification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MatchVerificationResource extends Resource
{
    protected static ?string $model = MatchVerification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static string | \UnitEnum | null $navigationGroup = 'Match Management';
    protected static ?string $navigationLabel = 'Verifikasi Match';
    protected static ?string $modelLabel = 'Verifikasi Match';
    protected static ?string $pluralModelLabel = 'Verifikasi Match';

    protected static ?string $recordTitleAttribute = 'match_id';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return MatchVerificationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MatchVerificationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatchVerificationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMatchVerifications::route('/'),
            'create' => CreateMatchVerification::route('/create'),
            'view' => ViewMatchVerification::route('/{record}'),
            'edit' => EditMatchVerification::route('/{record}/edit'),
        ];
    }
}
