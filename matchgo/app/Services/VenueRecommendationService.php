<?php

namespace App\Services;

use App\Models\Venue;
use App\Models\Team;
use Illuminate\Support\Collection;

class VenueRecommendationService
{
    /**
     * Rekomendasikan venue berdasarkan:
     *   - Titik tengah antara dua tim       → 40 poin (jarak dari midpoint)
     *   - Jadwal tersedia di waktu yang diminta → 35 poin
     *   - Harga terjangkau                  → 15 poin
     *   - Kapasitas & status active         → 10 poin
     */
    public function recommend(
        Team    $myTeam,
        ?Team   $opponentTeam,
        array   $filters   = [],
        ?array  $midpoint  = null
    ): Collection {
        $query = Venue::with('schedules')
            ->where('status', 'active')
            ->where('is_available', true);

        // ── Filter: ketersediaan tanggal + waktu ─────────────────
        if (!empty($filters['date'])) {
            $date = $filters['date'];

            $query->where(function ($q) use ($date, $filters) {
                $q->whereHas('schedules', function ($sq) use ($date, $filters) {
                    $sq->where('date', $date)
                       ->where('is_available', true);

                    if (!empty($filters['start_time'])) {
                        $sq->where('start_time', '<=', $filters['start_time']);
                    }
                    if (!empty($filters['end_time'])) {
                        $sq->where('end_time', '>=', $filters['end_time']);
                    }
                });
            });
        }

        // ── Filter: harga maksimal ───────────────────────────────
        if (!empty($filters['max_price'])) {
            $query->where('price_per_hour', '<=', $filters['max_price']);
        }

        $venues = $query->get();

        // ── Hitung jarak & skor tiap venue ───────────────────────
        $scored = $venues->map(function (Venue $venue) use ($myTeam, $opponentTeam, $filters, $midpoint) {

            $distanceFromMidpoint = null;
            $distanceFromMe       = null;

            if ($midpoint && $venue->latitude && $venue->longitude) {
                $distanceFromMidpoint = $this->haversine(
                    $midpoint['lat'], $midpoint['lng'],
                    (float) $venue->latitude, (float) $venue->longitude
                );
            }

            if ($myTeam->latitude && $myTeam->longitude && $venue->latitude && $venue->longitude) {
                $distanceFromMe = $this->haversine(
                    (float) $myTeam->latitude, (float) $myTeam->longitude,
                    (float) $venue->latitude,  (float) $venue->longitude
                );
            }

            // ── Pakai jarak dari midpoint jika ada, fallback ke jarak dari saya ──
            $displayDistance = $distanceFromMidpoint ?? $distanceFromMe;

            // ── Filter jarak ────────────────────────────────
            $maxDist = (int) ($filters['max_distance'] ?? 50);
            if ($displayDistance !== null && $displayDistance > $maxDist) {
                return null; // exclude
            }

            $score          = $this->calcScore($venue, $displayDistance, $filters);
            $availableSlots = $this->getAvailableSlots($venue, $filters);

            return [
                'venue'          => $venue,
                'score'          => $score,
                'score_label'    => $this->scoreLabel($score),
                'score_color'    => $this->scoreColor($score),
                'distance_km'    => $displayDistance ? round($displayDistance, 1) : null,
                'available_slots'=> $availableSlots,
            ];
        })->filter()->values(); // remove nulls

        // ── Sort ─────────────────────────────────────────────────
        $sortBy = $filters['sort_by'] ?? 'score';

        return match ($sortBy) {
            'distance' => $scored->sortBy(fn($v) => $v['distance_km'] ?? 9999)->values(),
            'price'    => $scored->sortBy(fn($v) => $v['venue']->price_per_hour ?? 9999999)->values(),
            default    => $scored->sortByDesc('score')->values(),
        };
    }

    /**
     * Temukan venue terbaik untuk pertandingan otomatis berdasarkan
     * jadwal, waktu, dan titik tengah kedua tim.
     */
    public function findBestVenueForMatch(
        Team    $myTeam,
        Team    $opponentTeam,
        string  $date,
        string  $startTime,
        string  $endTime
    ): ?Venue {
        $midpoint = $this->calcMidpoint($myTeam, $opponentTeam);
        $filters  = [
            'date'       => $date,
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ];

        $results = $this->recommend($myTeam, $opponentTeam, $filters, $midpoint);

        return $results->first()['venue'] ?? null;
    }

    // ─────────────────────────────────────────────────────────────
    //  MIDPOINT
    // ─────────────────────────────────────────────────────────────

    /**
     * Hitung titik tengah geografis antara dua tim.
     * Jika salah satu tidak punya koordinat, fallback ke yang ada.
     */
    public function calcMidpoint(Team $myTeam, ?Team $opponentTeam): ?array
    {
        $myLat  = (float) ($myTeam->latitude  ?? 0);
        $myLng  = (float) ($myTeam->longitude ?? 0);

        if (!$myLat && !$myLng) return null;

        if (!$opponentTeam || (!$opponentTeam->latitude && !$opponentTeam->longitude)) {
            return ['lat' => $myLat, 'lng' => $myLng, 'is_midpoint' => false];
        }

        $oppLat = (float) $opponentTeam->latitude;
        $oppLng = (float) $opponentTeam->longitude;

        // Konversi ke radian, hitung titik tengah di bola bumi, konversi balik
        $lat1 = deg2rad($myLat);  $lng1 = deg2rad($myLng);
        $lat2 = deg2rad($oppLat); $lng2 = deg2rad($oppLng);

        $dLng = $lng2 - $lng1;
        $bx   = cos($lat2) * cos($dLng);
        $by   = cos($lat2) * sin($dLng);

        $midLat = atan2(sin($lat1) + sin($lat2), sqrt((cos($lat1) + $bx) ** 2 + $by ** 2));
        $midLng = $lng1 + atan2($by, cos($lat1) + $bx);

        return [
            'lat'        => round(rad2deg($midLat), 6),
            'lng'        => round(rad2deg($midLng), 6),
            'is_midpoint'=> true,
        ];
    }

    // ─────────────────────────────────────────────────────────────
    //  SCORING
    // ─────────────────────────────────────────────────────────────

    private function calcScore(Venue $venue, ?float $distance, array $filters): int
    {
        $score = 0;

        // 1. Jarak dari titik tengah (40 poin) — semakin dekat semakin bagus
        if ($distance !== null) {
            if ($distance <= 2)       $score += 40;
            elseif ($distance <= 5)   $score += 32;
            elseif ($distance <= 10)  $score += 22;
            elseif ($distance <= 20)  $score += 12;
            else                      $score += 4;
        } else {
            $score += 20; // bonus netral jika koordinat tidak tersedia
        }

        // 2. Ketersediaan slot waktu (35 poin)
        $slotCount = $this->getAvailableSlots($venue, $filters)->count();
        if ($slotCount > 0) {
            $score += min(35, $slotCount * 10);
        }

        // 3. Harga terjangkau (15 poin)
        $price = $venue->price_per_hour ?? 0;
        if ($price === 0)           $score += 15;
        elseif ($price <= 100000)   $score += 15;
        elseif ($price <= 200000)   $score += 10;
        elseif ($price <= 350000)   $score += 5;
        else                        $score += 1;

        // 4. Kapasitas (10 poin)
        if (($venue->capacity ?? 0) >= 10) $score += 10;
        elseif (($venue->capacity ?? 0) >= 5) $score += 5;

        return min(100, $score);
    }

    // ─────────────────────────────────────────────────────────────
    //  SLOT HELPER
    // ─────────────────────────────────────────────────────────────

    public function getAvailableSlots(Venue $venue, array $filters): Collection
    {
        $slots = $venue->schedules->where('is_available', true);

        if (!empty($filters['date'])) {
            $slots = $slots->where('date', $filters['date']);
        } else {
            // Default: ambil slot dalam 14 hari ke depan
            $slots = $slots->filter(function ($s) {
                return $s->date >= today()->format('Y-m-d')
                    && $s->date <= today()->addDays(14)->format('Y-m-d');
            });
        }

        if (!empty($filters['start_time'])) {
            $slots = $slots->filter(fn($s) => $s->start_time <= $filters['start_time']);
        }

        if (!empty($filters['end_time'])) {
            $slots = $slots->filter(fn($s) => $s->end_time >= $filters['end_time']);
        }

        return $slots->sortBy('date')->values();
    }

    // ─────────────────────────────────────────────────────────────
    //  HAVERSINE DISTANCE (km)
    // ─────────────────────────────────────────────────────────────

    public function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R    = 6371; // radius bumi km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    // ─────────────────────────────────────────────────────────────

    private function scoreLabel(int $score): string
    {
        return match (true) {
            $score >= 80 => 'Sangat Direkomendasikan',
            $score >= 60 => 'Direkomendasikan',
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
}