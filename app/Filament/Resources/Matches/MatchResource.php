<?php

namespace App\Filament\Resources\Matches;

use App\Filament\Resources\Matches\Pages\CreateMatch;
use App\Filament\Resources\Matches\Pages\EditMatch;
use App\Filament\Resources\Matches\Pages\ListMatches;
use App\Filament\Resources\Matches\Schemas\MatchForm;
use App\Filament\Resources\Matches\Tables\MatchesTable;
use App\Models\Matches;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MatchResource extends Resource
{
    protected static ?string $model = Matches::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Match Management';

    protected static ?string $navigationLabel = 'Matches';

    protected static ?string $modelLabel = 'Match';

    protected static ?string $pluralModelLabel = 'Matches';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $recordTitleAttribute = 'match_code';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return MatchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatchesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMatches::route('/'),
            'create' => CreateMatch::route('/create'),
            'edit' => EditMatch::route('/{record}/edit'),
        ];
    }
}