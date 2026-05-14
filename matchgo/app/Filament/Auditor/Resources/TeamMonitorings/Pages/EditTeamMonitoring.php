<?php

namespace App\Filament\Auditor\Resources\TeamMonitoringResource\Pages;

use App\Filament\Auditor\Resources\TeamMonitoringResource;
use App\Models\TeamStatusLog;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeamMonitoring extends EditRecord
{
    protected static string $resource =
        TeamMonitoringResource::class;

    /*
    |--------------------------------------------------------------------------
    | FILL DATA
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $latest = $this->record->latestStatusLog;

        $data['status'] = $latest?->status;
        $data['reason'] = $latest?->reason;

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE MONITORING
    |--------------------------------------------------------------------------
    */

    protected function handleRecordUpdate(
        Model $record,
        array $data
    ): Model {

        TeamStatusLog::create([

            'team_id' => $record->id,

            'status' => $data['status'],

            'reason' => $data['reason'],

            'updated_by' => auth()->id(),

        ]);

        return $record;
    }

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Monitoring team berhasil diperbarui');
    }
}