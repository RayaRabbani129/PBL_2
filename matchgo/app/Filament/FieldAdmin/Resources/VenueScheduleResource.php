<?php

namespace App\Filament\FieldAdmin\Resources;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;
use App\Models\Field;
use App\Models\VenueSchedule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class VenueScheduleResource extends Resource
{
    protected static ?string $model = VenueSchedule::class;

    protected static ?string $navigationLabel = 'Jadwal Booking';

    protected static ?string $modelLabel = 'Jadwal';

    protected static ?string $pluralModelLabel = 'Jadwal Booking';

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-calendar-days';
    }

    /**
     * Hanya tampilkan jadwal venue milik admin login
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('venue.fieldAdmins', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Schema $form): Schema
    {
        $user = auth()->user();

        $venue = $user
            ? $user->managedVenues()->first()
            : null;

        return $form->schema([

            Section::make('Informasi Venue')
                ->icon('heroicon-o-building-office-2')
                ->description('Venue yang Anda kelola')
                ->schema([

                    Placeholder::make('venue_name')
                        ->label('Nama Venue')
                        ->content(
                            $venue?->name ?? 'Belum ada venue'
                        ),

                    Placeholder::make('venue_city')
                        ->label('Kota')
                        ->content(
                            $venue?->city ?? '-'
                        ),

                    Placeholder::make('venue_address')
                        ->label('Alamat')
                        ->content(
                            $venue?->address ?? '-'
                        )
                        ->columnSpanFull(),

                ])
                ->columns(2),

            Section::make('Pengaturan Jadwal')
                ->icon('heroicon-o-calendar')
                ->description('Atur jadwal lapangan venue')
                ->schema([

                    Select::make('field_id')
                        ->label('Lapangan')
                        ->options(function () use ($venue) {

                            if (! $venue) {
                                return [];
                            }

                            return Field::query()
                                ->where('venue_id', $venue->id)
                                ->where('status', 'active')
                                ->pluck('name', 'id');

                        })
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required()
                        ->placeholder('Pilih lapangan'),

                    DatePicker::make('date')
                        ->label('Tanggal')
                        ->required()
                        ->native(false)
                        ->minDate(now()),

                    TimePicker::make('start_time')
                        ->label('Jam Mulai')
                        ->required()
                        ->seconds(false),

                    TimePicker::make('end_time')
                        ->label('Jam Selesai')
                        ->required()
                        ->seconds(false)
                        ->after('start_time'),

                    Toggle::make('is_available')
                        ->label('Tersedia untuk booking')
                        ->default(true),

                ])
                ->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('date', 'asc')

            ->columns([

                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->badge()
                    ->color('success'),

                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->badge()
                    ->color('warning'),

                IconColumn::make('is_available')
                    ->label('Status')
                    ->boolean(),

                BadgeColumn::make('status_badge')
                    ->label('Kondisi')
                    ->getStateUsing(function ($record) {
                        return $record->is_available
                            ? 'Tersedia'
                            : 'Tidak Tersedia';
                    })
                    ->colors([
                        'success' => 'Tersedia',
                        'danger' => 'Tidak Tersedia',
                    ]),

            ])

            ->filters([

                SelectFilter::make('field_id')
                    ->label('Lapangan')
                    ->relationship('field', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_available')
                    ->label('Status Ketersediaan'),

            ])

            ->actions([

                EditAction::make()
                    ->modalWidth('2xl'),

                DeleteAction::make(),

            ])

            ->bulkActions([

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),

            ]);
    }

    /**
     * VALIDASI BENTROK JADWAL
     */
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        static::validateSchedule($data);

        $venue = auth()->user()?->managedVenues()?->first();

        $data['venue_id'] = $venue->id;

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        static::validateSchedule($data);

        return $data;
    }

    protected static function validateSchedule(array $data): void
    {
        $exists = VenueSchedule::query()
            ->where('field_id', $data['field_id'])
            ->where('date', $data['date'])

            ->where(function ($query) use ($data) {
                $query
                    ->whereBetween('start_time', [
                        $data['start_time'],
                        $data['end_time'],
                    ])
                    ->orWhereBetween('end_time', [
                        $data['start_time'],
                        $data['end_time'],
                    ]);
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'start_time' => 'Jadwal bentrok dengan jadwal lain.',
            ]);
        }
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