<?php

namespace App\Filament\FieldAdmin\Resources\VenueScheduleResource\Pages;

use App\Filament\FieldAdmin\Resources\VenueScheduleResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVenueSchedule extends ViewRecord
{
    protected static string $resource = VenueScheduleResource::class;

    protected static ?string $title = 'Detail Jadwal Booking';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(VenueScheduleResource::getUrl('index')),

            EditAction::make()
                ->label('Edit')
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
        ];
    }
}