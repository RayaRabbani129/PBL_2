<?php

namespace App\Filament\Auditor\Resources\MatchAuditResource\Pages;

use App\Filament\Auditor\Resources\MatchAuditResource;
use App\Models\MatchAudit;
use App\Models\Matches;
use App\Models\Team;
use App\Models\TeamStat;
use App\Models\TeamStatusLog;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditMatchAudit extends EditRecord
{
    protected static string $resource = MatchAuditResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $match = $this->record;
        $audit = $match->audit;

        return [
            'home_score' => $match->home_score,
            'away_score' => $match->away_score,
            'status' => $match->status,
            'home_team_review' => $audit?->home_team_review ?? 'fair_play',
            'away_team_review' => $audit?->away_team_review ?? 'fair_play',
            'sportsmanship_rating' => $audit?->sportsmanship_rating,
            'audit_notes' => $audit?->audit_notes,
            'game_review' => $audit?->game_review,
            'audited_at' => $audit?->audited_at ?? now(),
        ];
    }

    protected function afterSave(): void
    {
        $match = $this->record;
        $formData = $this->data;

        DB::transaction(function () use ($match, $formData) {
            $match->update([
                'home_score' => $formData['home_score'],
                'away_score' => $formData['away_score'],
                'status'     => $formData['status'],
            ]);

            $audit = MatchAudit::updateOrCreate(
                ['match_id' => $match->id],
                [
                    'auditor_id' => auth()->id(),
                    'home_team_review' => $formData['home_team_review'],
                    'away_team_review' => $formData['away_team_review'],
                    'sportsmanship_rating' => $formData['sportsmanship_rating'],
                    'audit_notes' => $formData['audit_notes'],
                    'game_review' => is_array($formData['game_review']) 
                                    ? json_encode($formData['game_review']) 
                                    : $formData['game_review'],
                    'audited_at' => $formData['audited_at'] ?? now(),
                ]
            );

            if ($formData['status'] === 'completed' && !$match->stats_processed) {
                $this->updateStats($match);
                $match->update(['stats_processed' => true]);
            }

            $this->reviewTeam($match->homeTeam, $formData['home_team_review'], $formData['audit_notes']);
            $this->reviewTeam($match->awayTeam, $formData['away_team_review'], $formData['audit_notes']);
        });

        Notification::make()
            ->success()
            ->title('Audit berhasil diperbarui')
            ->send();
    }

    private function updateStats(Matches $match): void
    {
        $home = TeamStat::firstOrCreate(['team_id' => $match->home_team_id]);
        $away = TeamStat::firstOrCreate(['team_id' => $match->away_team_id]);

        $home->increment('total_matches');
        $away->increment('total_matches');

        $home->increment('goals_scored', $match->home_score);
        $home->increment('goals_conceded', $match->away_score);

        $away->increment('goals_scored', $match->away_score);
        $away->increment('goals_conceded', $match->home_score);

        if ($match->home_score > $match->away_score) {
            $home->increment('wins');
            $away->increment('losses');
        } elseif ($match->away_score > $match->home_score) {
            $away->increment('wins');
            $home->increment('losses');
        }
    }

    private function reviewTeam(Team $team, string $review, ?string $notes): void
    {
        $points = match ($review) {
            'warning'        => 1,
            'under_review'   => 2,
            'toxic_behavior' => 2,
            'fake_player'    => 3,
            'violence'       => 4,
            default          => 0,
        };

        if (in_array($review, ['cheating', 'match_fixing'])) {
            $team->update([
                'status' => 'banned',
                'banned_at' => now(),
            ]);
        } else {
            $newPoints = $team->warning_points + $points;
            $status = match (true) {
                $newPoints >= 7 => 'banned',
                $newPoints >= 5 => 'suspended',
                $newPoints >= 3 => 'under_review',
                $newPoints >= 1 => 'warning',
                default         => 'active',
            };

            $team->update([
                'warning_points' => $newPoints,
                'status' => $status,
                'banned_at' => $status === 'banned' ? now() : null,
            ]);
        }

        TeamStatusLog::create([
            'team_id' => $team->id,
            'status' => $review,
            'reason' => $notes,
            'updated_by' => auth()->id(),
        ]);
    }
}