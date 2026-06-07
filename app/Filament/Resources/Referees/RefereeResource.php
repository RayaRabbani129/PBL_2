<?php

namespace App\Filament\Resources\Referees;

use App\Filament\Resources\Referees\Pages\CreateReferee;
use App\Filament\Resources\Referees\Pages\EditReferee;
use App\Filament\Resources\Referees\Pages\ListReferees;
use App\Filament\Resources\Referees\Schemas\RefereeForm;
use App\Filament\Resources\Referees\Tables\RefereeTable;
use App\Models\Referee;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RefereeResource extends Resource
{
    protected static ?string $model = Referee::class;

    protected static string | \UnitEnum | null $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Wasit';

    protected static ?string $modelLabel = 'Wasit';

    protected static ?string $pluralModelLabel = 'Wasit';

    // protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return RefereeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefereeTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReferees::route('/'),
            'create' => CreateReferee::route('/create'),
            'edit' => EditReferee::route('/{record}/edit'),
        ];
    }
}
