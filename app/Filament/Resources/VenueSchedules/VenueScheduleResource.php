<?php

namespace App\Filament\Resources\VenueSchedules;

use App\Filament\Resources\VenueSchedules\Pages\CreateVenueSchedule;
use App\Filament\Resources\VenueSchedules\Pages\EditVenueSchedule;
use App\Filament\Resources\VenueSchedules\Pages\ListVenueSchedules;
use App\Filament\Resources\VenueSchedules\Schemas\VenueScheduleForm;
use App\Filament\Resources\VenueSchedules\Tables\VenueSchedulesTable;
use App\Models\VenueSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VenueScheduleResource extends Resource
{
    protected static ?string $model = VenueSchedule::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | \UnitEnum | null $navigationGroup = 'Venue Management';

    protected static ?string $recordTitleAttribute = 'venue.name';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return VenueScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VenueSchedulesTable::configure($table);
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
            'index' => ListVenueSchedules::route('/'),
            'create' => CreateVenueSchedule::route('/create'),
            'edit' => EditVenueSchedule::route('/{record}/edit'),
        ];
    }
}
