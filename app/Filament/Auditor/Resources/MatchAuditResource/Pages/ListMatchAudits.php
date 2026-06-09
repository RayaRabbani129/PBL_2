<?php

namespace App\Filament\Auditor\Resources\MatchAuditResource\Pages;

use App\Filament\Auditor\Resources\MatchAuditResource;
use Filament\Resources\Pages\ListRecords;

class ListMatchAudits extends ListRecords
{
    protected static string $resource = MatchAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}