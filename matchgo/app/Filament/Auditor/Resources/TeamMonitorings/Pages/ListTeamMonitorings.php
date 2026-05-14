<?php

namespace App\Filament\Auditor\Resources\TeamMonitoringResource\Pages;

use App\Filament\Auditor\Resources\TeamMonitoringResource;
use Filament\Resources\Pages\ListRecords;

class ListTeamMonitorings extends ListRecords
{
    protected static string $resource =
        TeamMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}