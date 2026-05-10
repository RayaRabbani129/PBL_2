<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Matches;
use App\Models\MatchRequest;
use Illuminate\Support\Collection;

class MatchmakingService
{
    /**
     * Cari lawan yang cocok untuk tim tertentu.
     */
    public function findOpponents(Team $myTeam, array $filters = []): Collection
    {
        // Load relation
        $myTeam->loadMissing([
            'schedules',
            'stats',
        ]);

        // =========================================================
        // TEAM YANG SEDANG MATCH AKTIF
        // =========================================================

        /**
         * HANYA BLOK:
         * - pending
         * - confirmed
         * - ongoing
         *
         * completed => boleh muncul lagi
         * cancelled => boleh muncul lagi
         */

        $busyMatchTeamIds = Matches::query()
            ->whereIn('status', [
                'pending',
                'confirmed',
                'ongoing',
            ])
            ->get()
            ->flatMap(function ($match) {

                return [
                    $match->home_team_id,
                    $match->away_team_id,
                ];
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // =========================================================
        // TEAM YANG SEDANG SEARCHING MATCH REQUEST
        // =========================================================

        /**
         * Team yang sedang mencari lawan
         * tidak boleh muncul di matchmaking
         */

        $searchingRequestTeamIds = MatchRequest::query()
            ->where('status', 'searching')
            ->pluck('matched_with')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // =========================================================
        // GABUNGKAN SEMUA TEAM YANG HARUS DIBLOK
        // =========================================================

        $blockedTeamIds = array_unique(array_merge(
            $busyMatchTeamIds,
            $searchingRequestTeamIds
        ));

        /**
         * Jangan blok tim sendiri
         */
        $blockedTeamIds = array_filter(
            $blockedTeamIds,
            fn ($id) => $id != $myTeam->id
        );

        // =========================================================
        // STATUS TEAM YANG MASIH BOLEH MATCHMAKING
        // =========================================================

        $allowedStatuses = [
            'active',
            'warning',
            'under_review',
        ];

        // =========================================================
        // QUERY TEAM
        // =========================================================

        $query = Team::with([
                'schedules',
                'owner',
                'stats',
            ])
            ->where('id', '!=', $myTeam->id)

            // Status team aman
            ->whereIn('status', $allowedStatuses)

            // Exclude team sibuk
            ->whereNotIn('id', $blockedTeamIds);

        // =========================================================
        // FILTER LEVEL
        // =========================================================

        if (!empty($filters['level'])) {

            $query->where('level', $filters['level']);
        }

        // =========================================================
        // FILTER HARI
        // =========================================================

        if (
            isset($filters['day_of_week']) &&
            $filters['day_of_week'] !== ''
        ) {

            $query->whereHas('schedules', function ($q) use ($filters) {

                $q->where('day_of_week', $filters['day_of_week'])
                    ->where('is_available', true);
            });
        }

        // =========================================================
        // FILTER BERDASARKAN JADWAL SAYA
        // =========================================================

        if (
            (!isset($filters['day_of_week']) ||
            $filters['day_of_week'] === '')
            &&
            isset($filters['use_my_schedule'])
            &&
            $filters['use_my_schedule'] === true
        ) {

            $myAvailableDays = $myTeam->schedules
                ->where('is_available', true)
                ->pluck('day_of_week')
                ->unique()
                ->values()
                ->toArray();

            if (!empty($myAvailableDays)) {

                $query->whereHas('schedules', function ($q) use ($myAvailableDays) {

                    $q->whereIn('day_of_week', $myAvailableDays)
                        ->where('is_available', true);
                });
            }
        }

        // =========================================================
        // AMBIL TEAM
        // =========================================================

        $candidates = $query->get();

        // =========================================================
        // HITUNG SKOR
        // =========================================================

        $scored = $candidates->map(function (Team $opponent) use ($myTeam, $filters) {

            $score = $this->calculateScore(
                $myTeam,
                $opponent,
                $filters
            );

            return [
                'team' => $opponent,

                'score' => $score,

                'score_label' => $this->scoreLabel($score),

                'score_color' => $this->scoreColor($score),

                'match_reasons' => $this->matchReasons(
                    $myTeam,
                    $opponent
                ),

                'overlap_days' => $this->getOverlapDays(
                    $myTeam,
                    $opponent
                ),

                'overlap_slots' => $this->getOverlapTimeSlots(
                    $myTeam,
                    $opponent
                ),
            ];
        });

        return $scored
            ->sortByDesc('score')
            ->values();
    }

    // =============================================================
    // SCORING ENGINE
    // =============================================================

    private function calculateScore(
        Team $mine,
        Team $opponent,
        array $filters
    ): int {

        $score = 0;

        // =========================================================
        // 1. LEVEL
        // =========================================================

        if ($mine->level === $opponent->level) {

            $score += 35;

        } elseif (
            $this->levelDistance(
                $mine->level,
                $opponent->level
            ) === 1
        ) {

            $score += 17;
        }

        // =========================================================
        // 2. OVERLAP JADWAL
        // =========================================================

        $mySchedules = $mine->schedules
            ->where('is_available', true);

        $opponentSchedules = $opponent->schedules
            ->where('is_available', true);

        $myDays = $mySchedules
            ->pluck('day_of_week')
            ->unique();

        $opponentDays = $opponentSchedules
            ->pluck('day_of_week')
            ->unique();

        $dayOverlap = $myDays
            ->intersect($opponentDays)
            ->count();

        if ($dayOverlap > 0) {

            $dayPoints = min(35, $dayOverlap * 7);

            $timeBonus = 0;

            foreach ($myDays->intersect($opponentDays) as $day) {

                $mySlot = $mySchedules
                    ->firstWhere('day_of_week', $day);

                $oppSlot = $opponentSchedules
                    ->firstWhere('day_of_week', $day);

                if (
                    $mySlot &&
                    $oppSlot &&
                    $this->timeSlotsOverlap($mySlot, $oppSlot)
                ) {
                    $timeBonus += 2;
                }
            }

            $score += min(35, $dayPoints + $timeBonus);
        }

        // =========================================================
        // 3. LOKASI
        // =========================================================

        if (
            $mine->city &&
            $opponent->city &&
            strtolower($mine->city) === strtolower($opponent->city)
        ) {

            $score += 20;

        } elseif (
            $mine->province &&
            $opponent->province &&
            strtolower($mine->province) === strtolower($opponent->province)
        ) {

            $score += 10;
        }

        // =========================================================
        // 4. WIN RATE
        // =========================================================

        $myWr  = $mine->stats->win_rate ?? 50;
        $oppWr = $opponent->stats->win_rate ?? 50;

        $diff = abs($myWr - $oppWr);

        $score += max(0, 10 - (int) ($diff / 10));

        return min(100, $score);
    }

    // =============================================================
    // OVERLAP HELPERS
    // =============================================================

    public function getOverlapDays(
        Team $mine,
        Team $opponent
    ): array {

        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $myDays = $mine->schedules
            ->where('is_available', true)
            ->pluck('day_of_week')
            ->unique();

        $opponentDays = $opponent->schedules
            ->where('is_available', true)
            ->pluck('day_of_week')
            ->unique();

        return $myDays
            ->intersect($opponentDays)
            ->map(fn ($d) => $dayNames[$d] ?? '?')
            ->values()
            ->toArray();
    }

    public function getOverlapTimeSlots(
        Team $mine,
        Team $opponent
    ): array {

        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $mySchedules = $mine->schedules
            ->where('is_available', true);

        $opponentSchedules = $opponent->schedules
            ->where('is_available', true);

        $myDays = $mySchedules
            ->pluck('day_of_week')
            ->unique();

        $opponentDays = $opponentSchedules
            ->pluck('day_of_week')
            ->unique();

        $commonDays = $myDays->intersect($opponentDays);

        $slots = [];

        foreach ($commonDays as $day) {

            $mySlot = $mySchedules
                ->firstWhere('day_of_week', $day);

            $oppSlot = $opponentSchedules
                ->firstWhere('day_of_week', $day);

            if ($mySlot && $oppSlot) {

                [$start, $end] = $this->timeOverlapRange(
                    $mySlot,
                    $oppSlot
                );

                if ($start && $end) {

                    $slots[] =
                        ($dayNames[$day] ?? '?')
                        . ' '
                        . $start
                        . '–'
                        . $end;
                }
            }
        }

        return $slots;
    }

    private function timeSlotsOverlap(
        object $a,
        object $b
    ): bool {

        $aStart = strtotime($a->start_time);
        $aEnd   = strtotime($a->end_time);

        $bStart = strtotime($b->start_time);
        $bEnd   = strtotime($b->end_time);

        return $aStart < $bEnd && $bStart < $aEnd;
    }

    private function timeOverlapRange(
        object $a,
        object $b
    ): array {

        $aStart = strtotime($a->start_time);
        $aEnd   = strtotime($a->end_time);

        $bStart = strtotime($b->start_time);
        $bEnd   = strtotime($b->end_time);

        $start = max($aStart, $bStart);
        $end   = min($aEnd, $bEnd);

        if ($start >= $end) {
            return [null, null];
        }

        return [
            date('H:i', $start),
            date('H:i', $end),
        ];
    }

    // =============================================================
    // LABELS
    // =============================================================

    private function scoreLabel(int $score): string
    {
        return match (true) {
            $score >= 80 => 'Sangat Cocok',
            $score >= 60 => 'Cocok',
            $score >= 40 => 'Cukup Cocok',
            default      => 'Kurang Cocok',
        };
    }

    private function scoreColor(int $score): string
    {
        return match (true) {
            $score >= 80 => 'success',
            $score >= 60 => 'accent',
            $score >= 40 => 'warning',
            default      => 'muted',
        };
    }

    private function matchReasons(
        Team $mine,
        Team $opponent
    ): array {

        $reasons = [];

        if ($mine->level === $opponent->level) {

            $reasons[] = [
                'icon' => 'bi-trophy',
                'text' => 'Level sama: '
                    . $this->levelLabel($mine->level),
            ];
        }

        $overlapSlots = $this->getOverlapTimeSlots(
            $mine,
            $opponent
        );

        if (!empty($overlapSlots)) {

            $reasons[] = [
                'icon' => 'bi-calendar-check',
                'text' => count($overlapSlots)
                    . ' slot waktu cocok',
            ];
        }

        if (
            $mine->city &&
            $opponent->city &&
            strtolower($mine->city) === strtolower($opponent->city)
        ) {

            $reasons[] = [
                'icon' => 'bi-geo-alt',
                'text' => 'Kota sama: ' . $mine->city,
            ];
        }

        return $reasons;
    }

    // =============================================================
    // LEVEL HELPERS
    // =============================================================

    private function levelDistance(
        ?string $a,
        ?string $b
    ): int {

        $order = [
            'casual' => 0,
            'semi_pro' => 1,
            'competitive' => 2,
        ];

        if (!isset($order[$a], $order[$b])) {
            return 99;
        }

        return abs($order[$a] - $order[$b]);
    }

    public function levelLabel(?string $level): string
    {
        return match ($level) {
            'casual' => 'Casual',
            'semi_pro' => 'Semi Pro',
            'competitive' => 'Competitive',
            default => ucfirst(str_replace('_', ' ', $level ?? '-')),
        };
    }
}