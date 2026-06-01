<?php

namespace App\Filament\Auditor\Resources;

use App\Filament\Auditor\Resources\MatchAuditResource\Forms\MatchAuditForm;
use App\Filament\Auditor\Resources\MatchAuditResource\Pages\EditMatchAudit;
use App\Filament\Auditor\Resources\MatchAuditResource\Pages\ListMatchAudits;
use App\Filament\Auditor\Resources\MatchAuditResource\Pages\ViewMatchAudit;
use App\Filament\Auditor\Resources\MatchAuditResource\Tables\MatchAuditTable;
use App\Models\Matches;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatchAuditResource extends Resource
{
    protected static ?string $model = Matches::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Audit Management';

    protected static ?string $navigationLabel = 'Match Audits';

    protected static ?string $modelLabel = 'Match Audit';

    protected static ?string $pluralModelLabel = 'Match Audits';

    protected static ?int $navigationSort = 1;

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

    public static function getNavigationBadge(): ?string
    {
        return Matches::query()
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'warning' : 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return MatchAuditForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatchAuditTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ringkasan Match')
                ->icon('heroicon-o-trophy')
                ->description('Informasi utama pertandingan yang diaudit.')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('match_code')
                                ->label('Kode Match')
                                ->badge()
                                ->color('primary'),

                            TextEntry::make('status')
                                ->label('Status Match')
                                ->badge()
                                ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state ?? '-')))
                                ->color(fn ($state) => match ($state) {
                                    'scheduled' => 'warning',
                                    'ongoing' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'gray',
                                }),

                            TextEntry::make('created_at')
                                ->label('Dibuat')
                                ->dateTime('d M Y H:i'),
                        ]),
                ]),

            Grid::make(2)
                ->schema([
                    Section::make('Tim Bertanding')
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            TextEntry::make('homeTeam.name')
                                ->label('Home Team')
                                ->badge()
                                ->color('success'),

                            TextEntry::make('awayTeam.name')
                                ->label('Away Team')
                                ->badge()
                                ->color('danger'),
                        ]),

                    Section::make('Venue')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            TextEntry::make('venue.name')
                                ->label('Nama Venue')
                                ->badge()
                                ->color('gray'),

                            TextEntry::make('venue.address')
                                ->label('Alamat')
                                ->placeholder('-'),

                            TextEntry::make('venue.city')
                                ->label('Kota')
                                ->placeholder('-'),
                        ]),
                ]),

            Section::make('Data Audit Terakhir')
                ->icon('heroicon-o-shield-check')
                ->description('Informasi audit terakhir pada match ini.')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('latestAudit.auditor.name')
                                ->label('Auditor')
                                ->placeholder('Belum diaudit')
                                ->badge()
                                ->color('primary'),

                            TextEntry::make('latestAudit.status')
                                ->label('Status Audit')
                                ->placeholder('Belum ada audit')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state ? ucfirst(str_replace('_', ' ', $state)) : '-')
                                ->color(fn ($state) => match ($state) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'pending' => 'warning',
                                    default => 'gray',
                                }),

                            TextEntry::make('latestAudit.created_at')
                                ->label('Tanggal Audit')
                                ->placeholder('-')
                                ->dateTime('d M Y H:i'),
                        ]),

                    TextEntry::make('latestAudit.notes')
                        ->label('Catatan Audit')
                        ->placeholder('Belum ada catatan audit.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMatchAudits::route('/'),
            'view'  => ViewMatchAudit::route('/{record}'),
            'edit'  => EditMatchAudit::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'match_code',
            'homeTeam.name',
            'awayTeam.name',
            'venue.name',
        ];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Match Code' => $record?->match_code,
            'Home Team' => $record?->homeTeam?->name,
            'Away Team' => $record?->awayTeam?->name,
            'Venue' => $record?->venue?->name,
            'Latest Auditor' => $record?->latestAudit?->auditor?->name,
        ];
    }
}