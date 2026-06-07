<?php

namespace App\Filament\FieldAdmin\Resources\FieldResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Jadwal Lapangan';

    protected static ?string $modelLabel = 'Jadwal';

    protected static ?string $pluralModelLabel = 'Jadwal Lapangan';

    public function form(Schema $form): Schema
    {
        return $form
            ->components([

                Section::make('Informasi Jadwal')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                DatePicker::make('date')
                                    ->label('Tanggal Bermain')
                                    ->required()
                                    ->native(false)
                                    ->minDate(now())
                                    ->displayFormat('d M Y'),

                                Toggle::make('is_available')
                                    ->label('Tersedia')
                                    ->default(true)
                                    ->inline(false),

                            ]),

                        Grid::make(2)
                            ->schema([

                                TimePicker::make('start_time')
                                    ->label('Jam Mulai')
                                    ->required()
                                    ->seconds(false),

                                TimePicker::make('end_time')
                                    ->label('Jam Selesai')
                                    ->required()
                                    ->seconds(false)
                                    ->after('start_time'),

                            ]),

                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')

            ->columns([

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->badge(),

                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->badge()
                    ->color('success'),

                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->badge()
                    ->color('danger'),

                IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),

            ])

            ->filters([
                TernaryFilter::make('is_available')
                    ->label('Ketersediaan'),
            ])

            ->headerActions([

                CreateAction::make()
                    ->label('Tambah Jadwal')

                    ->mutateFormDataUsing(function (array $data): array {

                        $field = $this->getOwnerRecord();

                        // otomatis isi venue_id
                        $data['venue_id'] = $field->venue_id;

                        return $data;
                    }),

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
}