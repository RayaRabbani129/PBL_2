<?php

namespace App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource;
use App\Filament\FieldAdmin\Widgets\VenueScheduleHeroWidget;
use App\Models\Field;
use App\Models\VenueSchedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class ListVenueSchedules extends ListRecords
{
    protected static string $resource = VenueScheduleResource::class;

    // ── Header actions ───────────────────────────────────────────
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate_bulk')
                ->label('Generate Massal')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->modalHeading('Generate Jadwal Massal')
                ->modalDescription('Buat banyak jadwal sekaligus berdasarkan rentang tanggal dan hari yang dipilih.')
                ->modalWidth('2xl')
                ->modalSubmitActionLabel('Generate Sekarang')
                ->form([
                    Section::make('Lapangan')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Select::make('field_id')
                                ->label('Pilih Lapangan')
                                ->options(function () {
                                    $venue = auth()->user()?->managedVenues()?->first();
                                    if (! $venue) return [];
                                    return Field::query()
                                        ->where('venue_id', $venue->id)
                                        ->where('status', 'active')
                                        ->pluck('name', 'id');
                                })
                                ->required()
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->placeholder('Pilih lapangan'),
                        ]),

                    Section::make('Rentang Tanggal')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            Grid::make(2)->schema([
                                DatePicker::make('date_from')
                                    ->label('Dari Tanggal')
                                    ->required()
                                    ->native(false)
                                    ->minDate(now())
                                    ->live(),

                                DatePicker::make('date_to')
                                    ->label('Sampai Tanggal')
                                    ->required()
                                    ->native(false)
                                    ->minDate(fn (Get $get) => $get('date_from') ?? now())
                                    ->live(),
                            ]),
                        ]),

                    Section::make('Pilih Hari')
                        ->icon('heroicon-o-calendar-days')
                        ->description('Centang hari-hari yang ingin dibuatkan jadwal')
                        ->schema([
                            CheckboxList::make('days')
                                ->label('')
                                ->options([
                                    '1' => 'Senin',
                                    '2' => 'Selasa',
                                    '3' => 'Rabu',
                                    '4' => 'Kamis',
                                    '5' => 'Jumat',
                                    '6' => 'Sabtu',
                                    '0' => 'Minggu',
                                ])
                                ->columns(4)
                                ->required()
                                ->bulkToggleable(),
                        ]),

                    Section::make('Jam Operasional')
                        ->icon('heroicon-o-clock')
                        ->description('Jadwal dibuat per slot sesuai durasi yang dipilih')
                        ->schema([
                            Grid::make(3)->schema([
                                TimePicker::make('open_time')
                                    ->label('Jam Buka')
                                    ->required()
                                    ->seconds(false)
                                    ->default('08:00'),

                                TimePicker::make('close_time')
                                    ->label('Jam Tutup')
                                    ->required()
                                    ->seconds(false)
                                    ->default('22:00'),

                                Select::make('slot_duration')
                                    ->label('Durasi Slot')
                                    ->required()
                                    ->native(false)
                                    ->default(60)
                                    ->options([
                                        30  => '30 menit',
                                        60  => '1 jam',
                                        90  => '1,5 jam',
                                        120 => '2 jam',
                                    ]),
                            ]),
                        ]),

                    Section::make('Opsi Tambahan')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            Toggle::make('skip_existing')
                                ->label('Lewati jadwal yang sudah ada')
                                ->helperText('Slot yang sudah ada tidak akan ditimpa')
                                ->default(true),

                            Toggle::make('is_available')
                                ->label('Tandai sebagai tersedia untuk booking')
                                ->default(true),
                        ]),
                ])
                ->action(function (array $data): void {
                    $venue        = auth()->user()?->managedVenues()?->first();
                    $field        = Field::findOrFail($data['field_id']);
                    $dateFrom     = Carbon::parse($data['date_from']);
                    $dateTo       = Carbon::parse($data['date_to']);
                    $selectedDays = $data['days'];
                    $slotMinutes  = (int) $data['slot_duration'];
                    $created = $skipped = 0;

                    foreach (CarbonPeriod::create($dateFrom, $dateTo) as $date) {
                        if (! in_array((string) $date->dayOfWeek, $selectedDays)) continue;

                        $current = Carbon::parse($date->format('Y-m-d') . ' ' . $data['open_time']);
                        $end     = Carbon::parse($date->format('Y-m-d') . ' ' . $data['close_time']);

                        while ($current->copy()->addMinutes($slotMinutes)->lte($end)) {
                            $slotStart = $current->format('H:i:s');
                            $slotEnd   = $current->copy()->addMinutes($slotMinutes)->format('H:i:s');

                            $exists = VenueSchedule::query()
                                ->where('field_id', $field->id)
                                ->where('date', $date->format('Y-m-d'))
                                ->where(fn ($q) => $q
                                    ->where('start_time', '<', $slotEnd)
                                    ->where('end_time', '>', $slotStart)
                                )->exists();

                            if ($exists && $data['skip_existing']) {
                                $skipped++;
                            } elseif (! $exists) {
                                VenueSchedule::create([
                                    'field_id'     => $field->id,
                                    'venue_id'     => $venue->id,
                                    'date'         => $date->format('Y-m-d'),
                                    'start_time'   => $slotStart,
                                    'end_time'     => $slotEnd,
                                    'is_available' => $data['is_available'],
                                ]);
                                $created++;
                            }

                            $current->addMinutes($slotMinutes);
                        }
                    }

                    Notification::make()
                        ->title('Generate Selesai!')
                        ->body("{$created} jadwal berhasil dibuat" . ($skipped > 0 ? ", {$skipped} dilewati." : "."))
                        ->success()
                        ->send();
                }),

            CreateAction::make()
                ->label('Tambah Manual')
                ->icon('heroicon-o-plus')
                ->color('gray'),
        ];
    }

    // ── venue_id otomatis terisi saat create manual ──────────────
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $venue = auth()->user()?->managedVenues()?->first();
        $data['venue_id'] = $venue?->id;
        return $data;
    }
}