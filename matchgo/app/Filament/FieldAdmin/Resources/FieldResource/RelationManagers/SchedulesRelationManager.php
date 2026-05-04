<?php

namespace App\Filament\FieldAdmin\Resources\FieldResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Jadwal Lapangan';

    protected static ?string $modelLabel = 'Jadwal';

    public function form(Schema $form): Schema
    {
        return $form->schema([
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
                ->label('Tersedia')
                ->default(true),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
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
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Ketersediaan'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Jadwal')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Pastikan venue_id terisi otomatis dari parent (field → venue)
                        $data['venue_id'] = $this->getOwnerRecord()->venue_id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'asc');
    }
}