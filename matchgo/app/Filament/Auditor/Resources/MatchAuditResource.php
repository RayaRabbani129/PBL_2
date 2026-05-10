<?php

namespace App\Filament\Auditor\Resources;

use App\Filament\Auditor\Resources\MatchAuditResource\Forms\MatchAuditForm;
use App\Filament\Auditor\Resources\MatchAuditResource\Pages\CreateMatchAudit;
use App\Filament\Auditor\Resources\MatchAuditResource\Pages\EditMatchAudit;
use App\Filament\Auditor\Resources\MatchAuditResource\Pages\ListMatchAudits;
use App\Filament\Auditor\Resources\MatchAuditResource\Tables\MatchAuditTable;
use App\Models\Matches;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatchAuditResource extends Resource
{
    /*
    |--------------------------------------------------------------------------
    | MODEL
    |--------------------------------------------------------------------------
    */

    protected static ?string $model = Matches::class;

    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup =
        'Audit Management';

    protected static ?string $navigationLabel = 'Match Audits';

    protected static ?string $modelLabel = 'Match Audit';

    protected static ?string $pluralModelLabel = 'Match Audits';

    protected static ?int $navigationSort = 1;

    /*
    |--------------------------------------------------------------------------
    | QUERY
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'homeTeam',
                'awayTeam',
                'venue',
                'latestAudit.auditor',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BADGE
    |--------------------------------------------------------------------------
    */

    public static function getNavigationBadge(): ?string
    {
        return Matches::query()
            ->whereIn('status', [
                'scheduled',
                'ongoing',
            ])
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0
            ? 'warning'
            : 'success';
    }

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Schema $schema): Schema
    {
        return MatchAuditForm::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return MatchAuditTable::configure($table);
    }

    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [
            'index' => ListMatchAudits::route('/'),
            'edit'  => EditMatchAudit::route('/{record}/edit'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'match_code',
            'homeTeam.name',
            'awayTeam.name',
            'venue.name',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH DETAILS
    |--------------------------------------------------------------------------
    */

    public static function getGlobalSearchResultDetails($record): array
    {
        return [

            'Match Code' =>
                $record?->match_code,

            'Home Team' =>
                $record?->homeTeam?->name,

            'Away Team' =>
                $record?->awayTeam?->name,

            'Venue' =>
                $record?->venue?->name,

            'Latest Auditor' =>
                $record?->latestAudit?->auditor?->name,
        ];
    }
}