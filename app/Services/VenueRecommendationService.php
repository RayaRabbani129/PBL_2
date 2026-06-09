<?php

namespace App\Services;

use App\Models\Venue;
use App\Models\Team;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class VenueRecommendationService
{
    /**
     * Cari venue terbaik untuk match.
     *
     * Return array:
     * [
     *   'venue'           => Venue,
     *   'field'           => Field,
     *   'score'           => int,
     *   'distance_km'     => float,
     *   'available_slots' => array,
     * ]
     * atau null jika tidak ada venue tersedia.
     */
    public function findBestVenueForMatch(
        Team   $homeTeam,
        Team   $awayTeam,
        string $date,
        string $startTime,
        string $endTime
    ): ?array {
        $midpoint = $this->calcMidpoint($homeTeam, $awayTeam);

        $venues = $this->recommend(
            $homeTeam,
            $awayTeam,
            [
                'date'       => $date,
                'start_time' => $startTime,
                'end_time'   => $endTime,
                'sort_by'    => 'distance',
            ],
            $midpoint
        );

        if ($venues->isEmpty()) {
            \Log::warning('[VenueRecommendation] Tidak ada venue tersedia', [
                'date'       => $date,
                'start_time' => $startTime,
                'end_time'   => $endTime,
            ]);

            return null;
        }

        // Kembalikan elemen pertama (array, bukan object Venue)
        return $venues->first();
    }

    /**
     * Rekomendasi venue — selalu mengembalikan Collection of arrays.
     *
     * Setiap elemen:
     * [
     *   'venue'           => Venue model,
     *   'field'           => Field model,
     *   'score'           => int,
     *   'distance_km'     => float,
     *   'available_slots' => array,
     * ]
     */
    public function recommend(
        Team   $myTeam,
        ?Team  $opponentTeam,
        array  $filters = [],
        ?array $midpoint = null
    ): Collection {
        $date        = $filters['date']         ?? null;
        $startTime   = $filters['start_time']   ?? null;
        $endTime     = $filters['end_time']     ?? null;
        $maxDistance = $filters['max_distance'] ?? null;
        $maxPrice    = $filters['max_price']    ?? null;
        $sortBy      = $filters['sort_by']      ?? 'distance';

        $venues = Venue::query()
            ->with(['fields.schedules'])
            ->where('status', 'active')
            ->where('is_available', true)
            ->get();

        \Log::info('[VenueRecommendation] Total venue aktif: ' . $venues->count());

        $results = collect();

        foreach ($venues as $venue) {
            \Log::info('[VenueRecommendation] CHECK VENUE', [
                'venue'       => $venue->name,
                'field_count' => $venue->fields->count(),
            ]);

            $bestField       = null;
            $matchedSchedule = null;

            foreach ($venue->fields as $field) {
                if (!$field->is_available || $field->status !== 'active') {
                    \Log::info('[VenueRecommendation] Skip field nonaktif', [
                        'field' => $field->name,
                    ]);
                    continue;
                }

                foreach ($field->schedules as $schedule) {
                    if (!$schedule->is_available) {
                        continue;
                    }

                    // Normalisasi tanggal
                    $scheduleDate = Carbon::parse($schedule->date)->format('Y-m-d');
                    $requestDate  = Carbon::parse($date)->format('Y-m-d');

                    if ($scheduleDate !== $requestDate) {
                        continue;
                    }

                    // Normalisasi jam (HH:MM)
                    $sStart = substr($schedule->start_time, 0, 5);
                    $sEnd   = substr($schedule->end_time,   0, 5);
                    $rStart = substr($startTime,            0, 5);
                    $rEnd   = substr($endTime,              0, 5);

                    // Overlap: jadwal mencakup seluruh rentang yang diminta
                    // Schedule harus mulai <= request start DAN berakhir >= request end
                    $isCovered = $sStart <= $rStart && $sEnd >= $rEnd;

                    \Log::info('[VenueRecommendation] CHECK TIME', [
                        'field'    => $field->name,
                        'schedule' => "$sStart-$sEnd",
                        'request'  => "$rStart-$rEnd",
                        'covered'  => $isCovered,
                    ]);

                    if ($isCovered) {
                        $bestField       = $field;
                        $matchedSchedule = [
                            'date'       => $requestDate,
                            'start_time' => $rStart,
                            'end_time'   => $rEnd,
                        ];
                        break 2;
                    }
                }
            }

            if (!$bestField) {
                \Log::warning('[VenueRecommendation] Venue gagal — tidak ada field tersedia', [
                    'venue' => $venue->name,
                ]);
                continue;
            }

            // Hitung jarak
            $distance = $this->distanceFromMidpoint(
                $midpoint,
                $venue->latitude,
                $venue->longitude
            );

            if ($maxDistance !== null && $distance > $maxDistance) {
                continue;
            }

            $fieldPrice = (float) $bestField->price_per_hour;

            if ($maxPrice !== null && $fieldPrice > $maxPrice) {
                continue;
            }

            $score = $this->calculateVenueScore($distance, $fieldPrice);

            $results->push([
                'venue'           => $venue,
                'field'           => $bestField,
                'score'           => $score,
                'distance_km'     => round($distance, 2),
                'available_slots' => [$matchedSchedule],
            ]);
        }

        // Sorting
        $results = match ($sortBy) {
            'price' => $results->sortBy(fn($v) => $v['field']->price_per_hour),
            'score' => $results->sortByDesc('score'),
            default => $results->sortBy('distance_km'),
        };

        \Log::info('[VenueRecommendation] Venue lolos: ' . $results->count());

        return $results->values();
    }

    /**
     * Hitung titik tengah dua tim.
     */
    public function calcMidpoint(Team $teamA, ?Team $teamB): ?array
    {
        if (!$teamA->latitude || !$teamA->longitude) {
            return null;
        }

        if (!$teamB || !$teamB->latitude || !$teamB->longitude) {
            return [
                'lat' => (float) $teamA->latitude,
                'lng' => (float) $teamA->longitude,
            ];
        }

        return [
            'lat' => ((float) $teamA->latitude  + (float) $teamB->latitude)  / 2,
            'lng' => ((float) $teamA->longitude + (float) $teamB->longitude) / 2,
        ];
    }

    private function calculateVenueScore(float $distanceKm, float $pricePerHour): int
    {
        $distanceScore = max(0, 70 - ($distanceKm * 2));
        $priceScore    = max(0, 30 - ($pricePerHour / 10000));

        return (int) round(min(100, $distanceScore + $priceScore));
    }

    private function distanceFromMidpoint(?array $midpoint, $venueLat, $venueLng): float
    {
        if (!$midpoint || !$venueLat || !$venueLng) {
            return 999;
        }

        return $this->haversine(
            (float) $midpoint['lat'],
            (float) $midpoint['lng'],
            (float) $venueLat,
            (float) $venueLng
        );
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}