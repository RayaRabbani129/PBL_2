<?php

namespace App\Filament\Auditor\Resources\MatchAuditResource\Pages;

use App\Filament\Auditor\Resources\MatchAuditResource;
use App\Models\Matches;
use App\Models\Team;
use App\Models\TeamStat;
use App\Models\TeamStatusLog;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMatchAudit extends CreateRecord
{
    protected static string $resource = MatchAuditResource::class;

    /*
    |--------------------------------------------------------------------------
    | MUTATE DATA
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['auditor_id'] = auth()->id();

        $data['audited_at'] = now();

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | AFTER CREATE
    |--------------------------------------------------------------------------
    */

    protected function afterCreate(): void
    {
        $audit = $this->record;

        $match = $audit->match;

        if (! $match) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE MATCH RESULT
        |--------------------------------------------------------------------------
        */

        $match->update([

            'home_score' => $audit->home_score,

            'away_score' => $audit->away_score,

            'status' => 'completed',
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE TEAM STATS
        |--------------------------------------------------------------------------
        */

        $this->updateTeamStats($match);

        /*
        |--------------------------------------------------------------------------
        | PROCESS TEAM REVIEW
        |--------------------------------------------------------------------------
        */

        $this->processTeamReview(
            team: $match->homeTeam,
            review: $audit->home_team_review,
            notes: $audit->audit_notes,
        );

        $this->processTeamReview(
            team: $match->awayTeam,
            review: $audit->away_team_review,
            notes: $audit->audit_notes,
        );

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION
        |--------------------------------------------------------------------------
        */

        Notification::make()
            ->title('Audit pertandingan berhasil dibuat')
            ->success()
            ->send();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TEAM STATS
    |--------------------------------------------------------------------------
    */

    private function updateTeamStats($match): void
    {
        $homeStat = TeamStat::firstOrCreate(
            ['team_id' => $match->homeTeam->id]
        );

        $awayStat = TeamStat::firstOrCreate(
            ['team_id' => $match->awayTeam->id]
        );

        $homeStat->increment('total_matches');
        $awayStat->increment('total_matches');

        $homeStat->increment('goals_scored', $match->home_score);
        $homeStat->increment('goals_conceded', $match->away_score);

        $awayStat->increment('goals_scored', $match->away_score);
        $awayStat->increment('goals_conceded', $match->home_score);

        if ($match->home_score > $match->away_score) {

            $homeStat->increment('wins');

            $awayStat->increment('losses');

        } elseif ($match->away_score > $match->home_score) {

            $awayStat->increment('wins');

            $homeStat->increment('losses');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS TEAM REVIEW
    |--------------------------------------------------------------------------
    */

    private function processTeamReview(
        Team $team,
        string $review,
        ?string $notes = null
    ): void {

        $points = match ($review) {

            'warning'        => 1,
            'under_review'   => 2,
            'toxic_behavior' => 2,
            'fake_player'    => 3,
            'violence'       => 4,

            default => 0,
        };

        /*
        |--------------------------------------------------------------------------
        | INSTANT BAN
        |--------------------------------------------------------------------------
        */

        if (in_array($review, [
            'cheating',
            'match_fixing',
        ])) {

            $team->update([
                'status' => 'banned',
                'banned_at' => now(),
            ]);

            TeamStatusLog::create([
                'team_id' => $team->id,
                'status' => $review,
                'reason' => $notes ?: 'Pelanggaran berat terdeteksi.',
                'updated_by' => auth()->id(),
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE POINTS
        |--------------------------------------------------------------------------
        */

        $newPoints = $team->warning_points + $points;

        $status = match (true) {

            $newPoints >= 7 => 'banned',
            $newPoints >= 5 => 'suspended',
            $newPoints >= 3 => 'under_review',
            $newPoints >= 1 => 'warning',

            default => 'active',
        };

        $team->update([

            'warning_points' => $newPoints,

            'status' => $status,

            'banned_at' =>
                $status === 'banned'
                    ? now()
                    : null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SAVE LOG
        |--------------------------------------------------------------------------
        */

        TeamStatusLog::create([

            'team_id' => $team->id,

            'status' => $review,

            'reason' =>
                $notes ?: 'Audit pertandingan dilakukan.',

            'updated_by' => auth()->id(),
        ]);
    }
}