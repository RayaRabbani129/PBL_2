<?php

namespace App\Filament\FieldAdmin\Resources;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;
use App\Models\Field;
use App\Models\VenueSchedule;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class VenueScheduleResource extends Resource
{
    protected static ?string $model = VenueSchedule::class;

    protected static ?string $navigationLabel = 'Jadwal Booking';
    protected static ?string $modelLabel       = 'Jadwal';
    protected static ?string $pluralModelLabel = 'Jadwal Booking';
    protected static ?int    $navigationSort   = 2;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-calendar-days';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('venue.fieldAdmins', fn ($q) => $q->where('user_id', auth()->id()))
            ->with(['field', 'venue']);
    }

    // ─────────────────────────────────────────────────────────────
    //  FORM
    // ─────────────────────────────────────────────────────────────
    public static function form(Schema $form): Schema
    {
        $venue = auth()->user()?->managedVenues()?->first();

        return $form->schema([
            Section::make('Informasi Venue')
                ->icon('heroicon-o-building-office-2')
                ->schema([
                    Placeholder::make('venue_name')
                        ->label('Nama Venue')
                        ->content($venue?->name ?? '-'),

                    Placeholder::make('venue_city')
                        ->label('Kota')
                        ->content($venue?->city ?? '-'),
                ])
                ->columns(2),

            Section::make('Detail Jadwal')
                ->icon('heroicon-o-calendar')
                ->schema([
                    Select::make('field_id')
                        ->label('Lapangan')
                        ->options(function () use ($venue) {
                            if (! $venue) return [];
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
                        ->default(true)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  TABLE
    // ─────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('date', 'asc')
            ->defaultGroup(
                Group::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->collapsible()
            )

            ->columns([
                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-map-pin'),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn ($record) => $record->date
                        ? \Carbon\Carbon::parse($record->date)->translatedFormat('l')
                        : null
                    ),

                // Jam start–end dalam satu kolom
                TextColumn::make('start_time')
                    ->label('Waktu')
                    ->formatStateUsing(fn ($record) =>
                        \Carbon\Carbon::parse($record->start_time)->format('H:i')
                        . ' – '
                        . \Carbon\Carbon::parse($record->end_time)->format('H:i')
                    )
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-clock'),

                TextColumn::make('duration')
                    ->label('Durasi')
                    ->getStateUsing(fn ($record) =>
                        \Carbon\Carbon::parse($record->start_time)
                            ->diffInMinutes(\Carbon\Carbon::parse($record->end_time)) . ' mnt'
                    )
                    ->badge()
                    ->color('info'),

                // Satu kolom gabungan (hilangkan IconColumn duplikat)
                TextColumn::make('status_label')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->is_available ? 'Tersedia' : 'Tutup')
                    ->badge()
                    ->color(fn ($state) => $state === 'Tersedia' ? 'success' : 'danger')
                    ->icon(fn ($state) => $state === 'Tersedia'
                        ? 'heroicon-m-check-circle'
                        : 'heroicon-m-x-circle'
                    ),
            ])

            ->filters([
                SelectFilter::make('field_id')
                    ->label('Lapangan')
                    ->relationship('field', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_available')
                    ->label('Status')
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tutup')
                    ->native(false),

                Filter::make('date_range')
                    ->label('Rentang Tanggal')
                    ->form([
                        DatePicker::make('from')->label('Dari')->native(false),
                        DatePicker::make('until')->label('Sampai')->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'],  fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'])  $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['from'])->format('d M Y');
                        if ($data['until']) $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['until'])->format('d M Y');
                        return $indicators;
                    }),

                Filter::make('upcoming')
                    ->label('Hanya Mendatang')
                    ->query(fn (Builder $q) => $q->whereDate('date', '>=', now()))
                    ->toggle(),

                Filter::make('today')
                    ->label('Hari Ini Saja')
                    ->query(fn (Builder $q) => $q->whereDate('date', today()))
                    ->toggle(),
            ])
            ->filtersLayout(FiltersLayout::Dropdown)
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter')
                    ->icon('heroicon-o-funnel')
            )

            ->actions([
                // Toggle slot — langsung terlihat di baris
                Action::make('toggle_available')
                    ->label(fn ($record) => $record->is_available ? 'Tutup' : 'Buka')
                    ->icon(fn ($record) => $record->is_available
                        ? 'heroicon-o-lock-closed'
                        : 'heroicon-o-lock-open'
                    )
                    ->color(fn ($record) => $record->is_available ? 'danger' : 'success')
                    ->action(fn ($record) => $record->update(['is_available' => ! $record->is_available]))
                    ->tooltip(fn ($record) => $record->is_available ? 'Tutup slot' : 'Buka slot'),

                // Edit & Delete dikumpulkan dalam satu dropdown
                ActionGroup::make([
                    EditAction::make()->modalWidth('2xl'),
                    DeleteAction::make()->requiresConfirmation(),
                ])->icon('heroicon-m-ellipsis-vertical'),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_open')
                        ->label('Buka Slot')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_available' => true]))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('bulk_close')
                        ->label('Tutup Slot')
                        ->icon('heroicon-o-lock-closed')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tutup semua slot yang dipilih?')
                        ->modalDescription('Slot yang ditutup tidak bisa dipesan oleh pengguna.')
                        ->action(fn ($records) => $records->each->update(['is_available' => false]))
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])

            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('Belum ada jadwal')
            ->emptyStateDescription('Gunakan "Generate Massal" untuk membuat banyak slot, atau "Tambah Manual" untuk satu slot.')
            ->emptyStateActions([
                Action::make('go_generate')
                    ->label('Generate Massal')
                    ->icon('heroicon-o-sparkles')
                    ->url(fn () => static::getUrl('index')),
            ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  VALIDASI BENTROK — dipakai dari CreateVenueSchedule & EditVenueSchedule
    // ─────────────────────────────────────────────────────────────
    public static function validateSchedule(array $data, ?int $excludeId = null): void
    {
        $query = VenueSchedule::query()
            ->where('field_id', $data['field_id'])
            ->where('date', $data['date'])
            ->where(fn ($q) => $q
                ->where('start_time', '<', $data['end_time'])
                ->where('end_time',   '>',  $data['start_time'])
            );

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'start_time' => 'Jadwal bentrok dengan jadwal lain pada lapangan dan tanggal yang sama.',
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  PAGES
    // ─────────────────────────────────────────────────────────────
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVenueSchedules::route('/'),
            'create' => Pages\CreateVenueSchedule::route('/create'),
            'edit'   => Pages\EditVenueSchedule::route('/{record}/edit'),
        ];
    }
}