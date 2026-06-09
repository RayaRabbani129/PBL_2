<?php

namespace App\Filament\Resources\NotificationRingtones;

use App\Filament\Resources\NotificationRingtones\Pages\CreateNotificationRingtone;
use App\Filament\Resources\NotificationRingtones\Pages\EditNotificationRingtone;
use App\Filament\Resources\NotificationRingtones\Pages\ListNotificationRingtones;
use App\Filament\Resources\NotificationRingtones\Schemas\NotificationRingtoneForm;
use App\Filament\Resources\NotificationRingtones\Tables\NotificationRingtonesTable;
use App\Models\NotificationRingtone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotificationRingtoneResource extends Resource
{
    protected static ?string $model = NotificationRingtone::class;

    protected static string | \UnitEnum | null $navigationGroup = 'System';

    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return NotificationRingtoneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationRingtonesTable::configure($table);
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
            'index' => ListNotificationRingtones::route('/'),
            'create' => CreateNotificationRingtone::route('/create'),
            'edit' => EditNotificationRingtone::route('/{record}/edit'),
        ];
    }
}
