<?php

namespace App\Filament\Auditor\Resources\MatchAuditResource\Pages;

use App\Filament\Auditor\Resources\MatchAuditResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMatchAudit extends ViewRecord
{
    protected static string $resource = MatchAuditResource::class;

    protected static ?string $title = 'Detail Match Audit';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(MatchAuditResource::getUrl('index')),

            EditAction::make()
                ->label('Audit / Edit')
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
        ];
    }
}