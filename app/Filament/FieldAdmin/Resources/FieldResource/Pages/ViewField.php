<?php

namespace App\Filament\FieldAdmin\Resources\FieldResource\Pages;

use App\Filament\FieldAdmin\Resources\FieldResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewField extends ViewRecord
{
    protected static string $resource = FieldResource::class;

    protected static ?string $title = 'Detail Lapangan';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(FieldResource::getUrl('index')),

            EditAction::make()
                ->label('Edit')
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
        ];
    }
}