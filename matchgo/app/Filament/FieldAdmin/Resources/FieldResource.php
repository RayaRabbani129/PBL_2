<?php

namespace App\Filament\FieldAdmin\Resources;

use App\Filament\FieldAdmin\Resources\FieldResource\Pages;
use App\Models\Field;
use App\Models\Venue;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static ?string $navigationLabel = 'Lapangan';

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-rectangle-group';
    }

    protected static ?string $modelLabel = 'Lapangan';

    protected static ?string $pluralModelLabel = 'Lapangan';

    protected static ?int $navigationSort = 1;

    // Hanya tampilkan lapangan milik venue yang dikelola field admin ini
    public static function getEloquentQuery(): Builder
    {
        $venueIds = auth()->user()
            ->venues()                 // via field_admin_venues pivot
            ->pluck('venues.id');

        return parent::getEloquentQuery()
            ->whereIn('venue_id', $venueIds);
    }

    public static function form(Schema $form): Schema
    {
        // Venue options dibatasi hanya milik field admin yang login
        $venueOptions = Venue::whereHas('fieldAdmins', function (Builder $q) {
            $q->where('users.id', auth()->id());
        })->pluck('name', 'id');

        return $form->schema([
            Section::make('Informasi Lapangan')
                ->schema([
                    Forms\Components\Select::make('venue_id')
                        ->label('Venue')
                        ->options($venueOptions)
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Lapangan')
                        ->placeholder('Contoh: Lapangan A')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\Select::make('type')
                        ->label('Jenis Lapangan')
                        ->options([
                            'futsal'    => 'Futsal',
                            'badminton' => 'Badminton',
                            'basket'    => 'Basket',
                            'voli'      => 'Voli',
                            'tenis'     => 'Tenis',
                            'lainnya'   => 'Lainnya',
                        ])
                        ->required()
                        ->default('futsal'),

                    Forms\Components\TextInput::make('capacity')
                        ->label('Kapasitas Pemain')
                        ->numeric()
                        ->default(14)
                        ->required()
                        ->minValue(2)
                        ->maxValue(100),

                    Forms\Components\TextInput::make('price_per_hour')
                        ->label('Harga per Jam (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->minValue(0),
                ])
                ->columns(2),

            Section::make('Detail & Status')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('photo_path')
                        ->label('Foto Lapangan')
                        ->image()
                        ->directory('fields')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active'   => 'Aktif',
                            'inactive' => 'Tidak Aktif',
                        ])
                        ->default('active')
                        ->required(),

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
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->circular(false)
                    ->height(50)
                    ->width(80),

                Tables\Columns\TextColumn::make('venue.name')
                    ->label('Venue')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lapangan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'primary' => 'futsal',
                        'success' => 'badminton',
                        'warning' => 'basket',
                        'info'    => 'voli',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' orang')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_per_hour')
                    ->label('Harga/Jam')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Aktif' : 'Tidak Aktif'),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),

                Tables\Columns\TextColumn::make('schedules_count')
                    ->label('Jadwal')
                    ->counts('schedules')
                    ->suffix(' slot')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('venue_id')
                    ->label('Venue')
                    ->relationship('venue', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'futsal'    => 'Futsal',
                        'badminton' => 'Badminton',
                        'basket'    => 'Basket',
                        'voli'      => 'Voli',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ]),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Tersedia'),
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
            ->defaultSort('venue_id');
    }

    public static function getRelationManagers(): array
    {
        return [
            FieldResource\RelationManagers\SchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFields::route('/'),
            'create' => Pages\CreateField::route('/create'),
            'edit'   => Pages\EditField::route('/{record}/edit'),
        ];
    }
}