<?php

namespace App\Filament\Resources\RefereeRentals;

use App\Filament\Resources\RefereeRentals\Pages\CreateRefereeRental;
use App\Filament\Resources\RefereeRentals\Pages\EditRefereeRental;
use App\Filament\Resources\RefereeRentals\Pages\ListRefereeRentals;
use App\Filament\Resources\RefereeRentals\Schemas\RefereeRentalForm;
use App\Filament\Resources\RefereeRentals\Tables\RefereeRentalTable;
use App\Models\RefereeRental;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RefereeRentalResource extends Resource
{
    protected static ?string $model = RefereeRental::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Match Management';

    protected static ?string $navigationLabel = 'Penyewaan Wasit';

    protected static ?string $modelLabel = 'Penyewaan Wasit';

    protected static ?string $pluralModelLabel = 'Penyewaan Wasit';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return RefereeRentalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefereeRentalTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRefereeRentals::route('/'),
            'create' => CreateRefereeRental::route('/create'),
            'edit' => EditRefereeRental::route('/{record}/edit'),
        ];
    }
}
