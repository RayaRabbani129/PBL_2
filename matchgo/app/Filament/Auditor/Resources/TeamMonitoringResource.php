<?php

namespace App\Filament\Auditor\Resources;

use App\Filament\Auditor\Resources\TeamMonitoringResource\Forms\TeamMonitoringForm;
use App\Filament\Auditor\Resources\TeamMonitoringResource\Pages\EditTeamMonitoring;
use App\Filament\Auditor\Resources\TeamMonitoringResource\Pages\ListTeamMonitorings;
use App\Filament\Auditor\Resources\TeamMonitoringResource\Tables\TeamMonitoringTable;
use App\Models\Team;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeamMonitoringResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup =
        'Audit Management';

    protected static ?string $navigationLabel =
        'Team Monitoring';

    protected static ?string $modelLabel =
        'Team Monitoring';

    protected static ?string $pluralModelLabel =
        'Team Monitoring';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'stats',
                'members',
                'latestStatusLog',
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return TeamMonitoringForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamMonitoringTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeamMonitorings::route('/'),
            'edit'  => EditTeamMonitoring::route('/{record}/edit'),
        ];
    }
}