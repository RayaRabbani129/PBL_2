<?php

namespace App\Services;

use App\Models\Matches;
use App\Models\Referee;
use App\Models\RefereeRental;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RefereeRentalService
{
    /**
     * Get available referees for a given match date and time
     */
    public function getAvailableReferees(Matches $match): Collection
    {
        if (!$match->match_datetime) {
            return collect();
        }

        $matchDate = $match->match_datetime->toDateString();
        $matchStart = $match->match_datetime->toTimeString();
        $matchEnd = $match->match_datetime->copy()->addMinutes($match->duration_minutes ?? 90)->toTimeString();

        return Referee::availableFor($matchDate, $matchStart, $matchEnd)
            ->withCount([
                'rentals as active_rentals_count' => fn ($query) => $query->whereIn('status', ['pending', 'confirmed']),
            ])
            ->get()
            ->sortByDesc(fn (Referee $referee) => $this->scoreRefereeForMatch($referee, $match))
            ->values();
    }

    public function getBestAvailableReferee(Matches $match): ?Referee
    {
        return $this->getAvailableReferees($match)->first();
    }

    /**
     * Check if a referee is available for a specific time slot
     */
    public function isRefereeAvailable(Referee $referee, string $date, string $startTime, string $endTime): bool
    {
        return Referee::availableFor($date, $startTime, $endTime)
            ->whereKey($referee->id)
            ->exists();
    }

    public function assignBestRefereeForMatch(Matches $match, ?string $notes = null): RefereeRental
    {
        if ($match->refereeRental) {
            return $match->refereeRental;
        }

        $referee = $this->getBestAvailableReferee($match);

        if (! $referee) {
            throw new \RuntimeException('Tidak ada wasit yang tersedia pada waktu tersebut.');
        }

        return $this->createRefereeRental($match, $referee, $notes ?? 'Dipilih otomatis oleh sistem.');
    }

    /**
     * Create a referee rental for a match
     */
    public function createRefereeRental(
        Matches $match,
        Referee $referee,
        ?string $notes = null
    ): RefereeRental {
        $matchDate = $match->match_datetime->toDateString();
        $startTime = $match->match_datetime->toTimeString();
        $endTime = $match->match_datetime->copy()->addMinutes($match->duration_minutes ?? 90)->toTimeString();

        $rental = new RefereeRental([
            'match_id' => $match->id,
            'referee_id' => $referee->id,
            'rental_date' => $matchDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'hourly_rate' => $referee->hourly_rate,
            'notes' => $notes,
            'status' => 'pending',
        ]);

        $rental->calculateCost();
        $rental->save();

        $match->update([
            'total_cost' => ($match->total_cost ?: 0) + $rental->rental_cost,
            'referee_id' => $referee->id,
        ]);

        $match->loadMissing(['cost', 'homeTeam.members', 'awayTeam.members']);

        if ($match->cost) {
            $homeCount = $match->cost->home_team_players ?: max(1, $match->homeTeam->members->count());
            $awayCount = $match->cost->away_team_players ?: max(1, $match->awayTeam->members->count());
            $totalAmount = round($match->cost->total_venue_cost + $rental->rental_cost, 2);

            $match->cost->update([
                'total_venue_cost'     => $totalAmount,
                'home_team_cost'       => round($totalAmount / 2, 2),
                'away_team_cost'       => round($totalAmount / 2, 2),
                'home_cost_per_player' => round($totalAmount / 2 / $homeCount, 2),
                'away_cost_per_player' => round($totalAmount / 2 / $awayCount, 2),
            ]);
        }

        return $rental;
    }

    /**
     * Update referee rental status
     */
    public function updateRentalStatus(RefereeRental $rental, string $status): RefereeRental
    {
        $rental->update(['status' => $status]);

        if ($status === 'completed') {
            // Update referee statistics
            $referee = $rental->referee;
            $referee->increment('total_matches_refereed');
        }

        return $rental;
    }

    /**
     * Cancel a referee rental
     */
    public function cancelRental(RefereeRental $rental): RefereeRental
    {
        $rental->update(['status' => 'cancelled']);

        // Refund the cost from match total
        if ($rental->match && $rental->match->total_cost) {
            $rental->match->update([
                'total_cost' => max(0, $rental->match->total_cost - $rental->rental_cost)
            ]);
        }

        return $rental;
    }

    /**
     * Get referee statistics
     */
    public function getRefereeStats(Referee $referee): array
    {
        return [
            'completed_matches' => $referee->rentals()
                ->where('status', 'completed')
                ->count(),
            'pending_rentals' => $referee->rentals()
                ->where('status', 'pending')
                ->count(),
            'confirmed_rentals' => $referee->rentals()
                ->where('status', 'confirmed')
                ->count(),
            'total_earnings' => $referee->rentals()
                ->where('status', 'completed')
                ->sum('rental_cost'),
            'average_rating' => $referee->rating,
        ];
    }

    private function scoreRefereeForMatch(Referee $referee, Matches $match): float
    {
        $certificationScore = [
            'basic' => 10,
            'intermediate' => 20,
            'advanced' => 30,
            'professional' => 40,
        ][$referee->certification_level] ?? 0;

        $sameCityScore = 0;
        $venueCity = strtolower((string) optional($match->venue)->city);
        $refereeCity = strtolower((string) $referee->city);

        if ($venueCity !== '' && $refereeCity !== '' && $venueCity === $refereeCity) {
            $sameCityScore = 25;
        }

        return $sameCityScore
            + $certificationScore
            + ((float) $referee->rating * 12)
            + min((int) $referee->experience_years, 20)
            - ((int) ($referee->active_rentals_count ?? 0) * 5)
            - ((float) $referee->hourly_rate / 100000);
    }
}
