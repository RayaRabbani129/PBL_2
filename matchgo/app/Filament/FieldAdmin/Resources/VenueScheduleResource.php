<?php

namespace App\Filament\FieldAdmin\Resources;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;
use App\Models\Field;
use App\Models\Venue;
use App\Models\VenueSchedule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VenueScheduleResource extends Resource
{
    protected static ?string $model = VenueSchedule::class;

    protected static ?string $navigationLabel = 'Jadwal';

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-calendar-days';
    }

    protected static ?string $modelLabel = 'Jadwal';

    protected static ?string $pluralModelLabel = 'Jadwal';

    protected static ?int $navigationSort = 2;

    // Hanya tampilkan jadwal milik lapangan di venue yang dikelola admin ini
    public static function getEloquentQuery(): Builder
    {
        $venueIds = auth()->user()
            ->venues()
            ->pluck('venues.id');

        return parent::getEloquentQuery()
            ->whereIn('venue_id', $venueIds);
    }

    public static function form(Schema $form): Schema
    {
        // Venue milik field admin yang login
        $venueOptions = Venue::whereHas('fieldAdmins', function (Builder $q) {
            $q->where('users.id', auth()->id());
        })->pluck('name', 'id');

        return $form->schema([
            Forms\Components\Section::make('Pilih Venue & Lapangan')
                ->schema([
                    Forms\Components\Select::make('venue_id')
                        ->label('Venue')
                        ->options($venueOptions)
                        ->required()
                        ->searchable()
                        ->reactive()               // update dropdown lapangan saat berubah
                        ->afterStateUpdated(fn (callable $set) => $set('field_id', null)),

                    Forms\Components\Select::make('field_id')
                        ->label('Lapangan')
                        ->options(function (Get $get) {
                            $venueId = $get('venue_id');
                            if (! $venueId) {
                                return [];
                            }
                            return Field::where('venue_id', $venueId)
                                ->where('status', 'active')
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->disabled(fn (Get $get): bool => ! $get('venue_id')),
                ])
                ->columns(2),

            Forms\Components\Section::make('Slot Waktu')
                ->schema([
                    Forms\Components\DatePicker::make('date')
                        ->label('Tanggal')
                        ->required()
                        ->minDate(now())
                        ->native(false),

                    Forms\Components\TimePicker::make('start_time')
                        ->label('Jam Mulai')
                        ->required()
                        ->seconds(false),

                    Forms\Components\TimePicker::make('end_time')
                        ->label('Jam Selesai')
                        ->required()
                        ->seconds(false)
                        ->after('start_time'),

                    Forms\Components\Toggle::make('is_available')
                        ->label('Tersedia untuk Booking')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('venue.name')
                    ->label('Venue')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->time('H:i'),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('venue_id')
                    ->label('Venue')
                    ->relationship('venue', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('field_id')
                    ->label('Lapangan')
                    ->relationship('field', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')->label('Dari Tanggal')->native(false),
                        Forms\Components\DatePicker::make('date_until')->label('Sampai Tanggal')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['date_from'], fn (Builder $q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['date_until'], fn (Builder $q, $date) => $q->whereDate('date', '<=', $date));
                    }),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Ketersediaan'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVenueSchedules::route('/'),
            'create' => Pages\CreateVenueSchedule::route('/create'),
            'edit'   => Pages\EditVenueSchedule::route('/{record}/edit'),
        ];
    }
}