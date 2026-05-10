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
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static ?string $navigationLabel = 'Lapangan';

    protected static ?string $modelLabel = 'Lapangan';

    protected static ?string $pluralModelLabel = 'Lapangan';

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-rectangle-group';
    }

    /**
     * Filter hanya field milik venue admin login
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
        $venue = Venue::whereHas('fieldAdmins', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();

        return $form->components([

            /**
             * Hidden Venue ID
             */
            Hidden::make('venue_id')
                ->default($venue?->id)
                ->required(),

            /**
             * SECTION INFORMASI
             */
            Section::make('Informasi Lapangan')
                ->icon('heroicon-o-building-office-2')
                ->description('Lengkapi data utama lapangan futsal.')
                ->schema([

                    Placeholder::make('venue_name')
                        ->label('Venue')
                        ->content($venue?->name ?? '-'),

                    Grid::make(2)
                        ->schema([

                            TextInput::make('name')
                                ->label('Nama Lapangan')
                                ->placeholder('Contoh: Lapangan Futsal A')
                                ->required()
                                ->maxLength(100)
                                ->minLength(3)
                                ->live(onBlur: true)
                                ->unique(ignoreRecord: true),

                            TextInput::make('type')
                                ->label('Jenis Lapangan')
                                ->default('Futsal')
                                ->disabled()
                                ->dehydrated()
                                ->formatStateUsing(fn () => 'futsal'),

                        ]),

                    Grid::make(2)
                        ->schema([

                            TextInput::make('capacity')
                                ->label('Kapasitas Pemain')
                                ->numeric()
                                ->required()
                                ->default(14)
                                ->minValue(2)
                                ->maxValue(50)
                                ->suffix('orang'),

                            TextInput::make('price_per_hour')
                                ->label('Harga per Jam')
                                ->numeric()
                                ->required()
                                ->prefix('Rp')
                                ->minValue(1000)
                                ->maxValue(10000000)
                                ->placeholder('50000'),

                        ]),

                    Textarea::make('description')
                        ->label('Deskripsi Lapangan')
                        ->rows(4)
                        ->placeholder('Masukkan deskripsi fasilitas lapangan...')
                        ->columnSpanFull()
                        ->maxLength(1000),

                ])
                ->columns(1),

            /**
             * SECTION FOTO
             */
            Section::make('Foto Lapangan')
                ->icon('heroicon-o-photo')
                ->description('Upload foto terbaik lapangan.')
                ->schema([

                    FileUpload::make('photo_path')
                        ->label('Foto')
                        ->image()
                        ->disk('public')
                        ->directory('fields')
                        ->visibility('public')
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->maxSize(4096)
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ])
                        ->helperText('Format: JPG, PNG, WEBP. Maksimal 4MB.')
                        ->columnSpanFull(),

                ]),

            /**
             * SECTION STATUS
             */
            Section::make('Status Lapangan')
                ->icon('heroicon-o-check-badge')
                ->schema([

                    Toggle::make('is_available')
                        ->label('Tersedia untuk Booking')
                        ->default(true)
                        ->inline(false),

                    Toggle::make('status')
                        ->label('Lapangan Aktif')
                        ->default(true)
                        ->formatStateUsing(fn ($state) => $state === 'active')
                        ->dehydrateStateUsing(fn ($state) => $state ? 'active' : 'inactive'),

                ])
                ->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')

            ->columns([

                ImageColumn::make('photo_path')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->height(52)
                    ->width(52)
                    ->defaultImageUrl(
                        'https://ui-avatars.com/api/?name=Venue&background=F3F4F6&color=6B7280'
                    ),

                TextColumn::make('name')
                    ->label('Nama Lapangan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('venue.name')
                    ->label('Venue')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->badge()
                    ->color('info')
                    ->suffix(' Orang'),

                TextColumn::make('price_per_hour')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('schedules_count')
                    ->label('Jadwal')
                    ->counts('schedules')
                    ->badge()
                    ->color('warning')
                    ->suffix(' Slot'),

                IconColumn::make('is_available')
                    ->label('Booking')
                    ->boolean(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state === 'active'
                        ? 'Aktif'
                        : 'Nonaktif'
                    )
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),

            ])

            ->filters([

                TernaryFilter::make('is_available')
                    ->label('Tersedia'),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ]),

            ])

            ->actions([

                EditAction::make()
                    ->iconButton(),

                DeleteAction::make()
                    ->iconButton(),

            ])

            ->bulkActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ])

            ->emptyStateHeading('Belum ada lapangan')
            ->emptyStateDescription('Tambahkan lapangan futsal pertama.')
            ->emptyStateIcon('heroicon-o-rectangle-group');
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
            'index' => Pages\ListFields::route('/'),
            'create' => Pages\CreateField::route('/create'),
            'edit' => Pages\EditField::route('/{record}/edit'),
        ];
    }
}