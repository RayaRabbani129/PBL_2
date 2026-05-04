<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamSchedule;
use Illuminate\Support\Collection;

class MatchmakingService
{
    /**
     * Cari lawan yang cocok untuk tim tertentu.
     *
     * Skor kecocokan (0–100):
     *   - Kesamaan level                → 35 poin
     *   - Overlap jadwal (hari + waktu) → 35 poin  ← pakai jadwal nyata tim saya
     *   - Jarak / kota / provinsi       → 20 poin
     *   - Win-rate seimbang             → 10 poin
     *
     * Bisa diganti model ML (SVM / Decision Tree) dengan swap calculateScore().
     */
    public function findOpponents(Team $myTeam, array $filters = []): Collection
    {
        // Pastikan jadwal myTeam sudah di-load
        $myTeam->loadMissing('schedules');

        $query = Team::with(['schedules', 'owner', 'stats'])
            ->where('id', '!=', $myTeam->id)
            ->where('status', 'active');

        // ── Filter level ──────────────────────────────────────────
        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        // ── Filter hari — hanya tampilkan tim yang tersedia di hari tsb ──
        if (isset($filters['day_of_week']) && $filters['day_of_week'] !== '') {
            $query->whereHas('schedules', function ($q) use ($filters) {
                $q->where('day_of_week', $filters['day_of_week'])
                  ->where('is_available', true);
            });
        }

        // ── Filter berdasarkan jadwal SAYA — hanya hari saya tersedia ──
        // Kalau tidak ada filter hari spesifik, auto-filter ke hari yg saya bisa
        if ((!isset($filters['day_of_week']) || $filters['day_of_week'] === '') && $filters['use_my_schedule'] ?? false) {
            $myAvailableDays = $myTeam->schedules
                ->where('is_available', true)
                ->pluck('day_of_week')
                ->toArray();

            if (!empty($myAvailableDays)) {
                $query->whereHas('schedules', function ($q) use ($myAvailableDays) {
                    $q->whereIn('day_of_week', $myAvailableDays)
                      ->where('is_available', true);
                });
            }
        }

        $candidates = $query->get();

        // ── Hitung skor kecocokan ─────────────────────────────────
        $scored = $candidates->map(function (Team $opponent) use ($myTeam, $filters) {
            $score          = $this->calculateScore($myTeam, $opponent, $filters);
            $overlapDays    = $this->getOverlapDays($myTeam, $opponent);
            $overlapSlots   = $this->getOverlapTimeSlots($myTeam, $opponent);

            return [
                'team'          => $opponent,
                'score'         => $score,
                'score_label'   => $this->scoreLabel($score),
                'score_color'   => $this->scoreColor($score),
                'match_reasons' => $this->matchReasons($myTeam, $opponent),
                'overlap_days'  => $overlapDays,
                'overlap_slots' => $overlapSlots,
            ];
        });

        return $scored->sortByDesc('score')->values();
    }

    // ─────────────────────────────────────────────────────────────
    //  SCORING ENGINE
    // ─────────────────────────────────────────────────────────────

    private function calculateScore(Team $mine, Team $opponent, array $filters): int
    {
        $score = 0;

        // 1. Kesamaan level (35 poin)
        if ($mine->level === $opponent->level) {
            $score += 35;
        } elseif ($this->levelDistance($mine->level, $opponent->level) === 1) {
            $score += 17; // level berdekatan
        }

        // 2. Overlap jadwal (35 poin) — berdasarkan jadwal nyata myTeam
        $mySchedules       = $mine->schedules->where('is_available', true);
        $opponentSchedules = $opponent->schedules->where('is_available', true);

        $myDays       = $mySchedules->pluck('day_of_week')->unique();
        $opponentDays = $opponentSchedules->pluck('day_of_week')->unique();
        $dayOverlap   = $myDays->intersect($opponentDays)->count();

        if ($dayOverlap > 0) {
            // Setiap hari overlap = 7 poin, max 35
            $dayPoints = min(35, $dayOverlap * 7);

            // Bonus: waktu juga overlap dalam hari yang sama
            $timeBonus = 0;
            foreach ($myDays->intersect($opponentDays) as $day) {
                $mySlot  = $mySchedules->firstWhere('day_of_week', $day);
                $oppSlot = $opponentSchedules->firstWhere('day_of_week', $day);

                if ($mySlot && $oppSlot && $this->timeSlotsOverlap($mySlot, $oppSlot)) {
                    $timeBonus += 2;
                }
            }

            $score += min(35, $dayPoints + $timeBonus);
        }

        // 3. Lokasi (20 poin)
        if ($mine->city && $opponent->city && strtolower($mine->city) === strtolower($opponent->city)) {
            $score += 20;
        } elseif ($mine->province && $opponent->province && strtolower($mine->province) === strtolower($opponent->province)) {
            $score += 10;
        }

        // 4. Win-rate seimbang (10 poin) — pakai stats relation
        $myWr  = $mine->stats->win_rate   ?? 50;
        $oppWr = $opponent->stats->win_rate ?? 50;
        $diff  = abs($myWr - $oppWr);
        $score += max(0, 10 - (int) ($diff / 10));

        return min(100, $score);
    }

    // ─────────────────────────────────────────────────────────────
    //  SCHEDULE HELPERS
    // ─────────────────────────────────────────────────────────────

    /**
     * Kembalikan daftar hari yang overlap antara dua tim (nama hari).
     */
    public function getOverlapDays(Team $mine, Team $opponent): array
    {
        $dayNames = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];

        $myDays       = $mine->schedules->where('is_available', true)->pluck('day_of_week')->unique();
        $opponentDays = $opponent->schedules->where('is_available', true)->pluck('day_of_week')->unique();

        return $myDays->intersect($opponentDays)
            ->map(fn ($d) => $dayNames[$d] ?? '?')
            ->values()
            ->toArray();
    }

    /**
     * Kembalikan slot waktu yang benar-benar overlap (hari + jam).
     * Format: ['Senin 08:00–10:00', ...]
     */
    public function getOverlapTimeSlots(Team $mine, Team $opponent): array
    {
        $dayNames = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];

        $mySchedules       = $mine->schedules->where('is_available', true);
        $opponentSchedules = $opponent->schedules->where('is_available', true);

        $myDays       = $mySchedules->pluck('day_of_week')->unique();
        $opponentDays = $opponentSchedules->pluck('day_of_week')->unique();
        $commonDays   = $myDays->intersect($opponentDays);

        $slots = [];
        foreach ($commonDays as $day) {
            $mySlot  = $mySchedules->firstWhere('day_of_week', $day);
            $oppSlot = $opponentSchedules->firstWhere('day_of_week', $day);

            if ($mySlot && $oppSlot) {
                [$overlapStart, $overlapEnd] = $this->timeOverlapRange($mySlot, $oppSlot);

                if ($overlapStart && $overlapEnd) {
                    $slots[] = ($dayNames[$day] ?? '?') . ' ' . $overlapStart . '–' . $overlapEnd;
                }
            }
        }

        return $slots;
    }

    /**
     * Apakah dua slot waktu pada hari yang sama saling overlap?
     */
    private function timeSlotsOverlap(object $a, object $b): bool
    {
        $aStart = strtotime($a->start_time);
        $aEnd   = strtotime($a->end_time);
        $bStart = strtotime($b->start_time);
        $bEnd   = strtotime($b->end_time);

        return $aStart < $bEnd && $bStart < $aEnd;
    }

    /**
     * Hitung range overlap waktu dua slot. Return [start, end] atau [null, null].
     */
    private function timeOverlapRange(object $a, object $b): array
    {
        $aStart = strtotime($a->start_time);
        $aEnd   = strtotime($a->end_time);
        $bStart = strtotime($b->start_time);
        $bEnd   = strtotime($b->end_time);

        $start = max($aStart, $bStart);
        $end   = min($aEnd, $bEnd);

        if ($start >= $end) return [null, null];

        return [date('H:i', $start), date('H:i', $end)];
    }

    // ─────────────────────────────────────────────────────────────
    //  LABEL HELPERS
    // ─────────────────────────────────────────────────────────────

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

    private function matchReasons(Team $mine, Team $opponent): array
    {
        $reasons = [];

        if ($mine->level === $opponent->level) {
            $reasons[] = ['icon' => 'bi-trophy', 'text' => 'Level sama: ' . $this->levelLabel($mine->level)];
        }

        $overlapSlots = $this->getOverlapTimeSlots($mine, $opponent);
        if (!empty($overlapSlots)) {
            $count = count($overlapSlots);
            $reasons[] = ['icon' => 'bi-calendar-check', 'text' => "{$count} slot waktu cocok"];
        } else {
            $overlapDays = $this->getOverlapDays($mine, $opponent);
            if (!empty($overlapDays)) {
                $reasons[] = ['icon' => 'bi-calendar2', 'text' => count($overlapDays) . ' hari jadwal sama'];
            }
        }

        if ($mine->city && $opponent->city && strtolower($mine->city) === strtolower($opponent->city)) {
            $reasons[] = ['icon' => 'bi-geo-alt', 'text' => 'Kota sama: ' . $mine->city];
        } elseif ($mine->province && $opponent->province && strtolower($mine->province) === strtolower($opponent->province)) {
            $reasons[] = ['icon' => 'bi-map', 'text' => 'Provinsi sama: ' . $mine->province];
        }

        return $reasons;
    }

    private function levelDistance(?string $a, ?string $b): int
    {
        $order = ['casual' => 0, 'semi_pro' => 1, 'pro' => 2];
        if (!isset($order[$a], $order[$b])) return 99;
        return abs($order[$a] - $order[$b]);
    }

    public function levelLabel(?string $level): string
    {
        return match ($level) {
            'casual'   => 'Casual',
            'semi_pro' => 'Semi Pro',
            'pro'      => 'Pro',
            default    => ucfirst(str_replace('_', ' ', $level ?? '-')),
        };
    }
}