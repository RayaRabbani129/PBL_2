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

        return Referee::where('is_available', true)
            ->whereDoesntHave('rentals', function ($query) use ($matchDate, $matchStart, $matchEnd) {
                $query->where('rental_date', $matchDate)
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($q) use ($matchStart, $matchEnd) {
                        $q->whereBetween('start_time', [$matchStart, $matchEnd])
                            ->orWhereBetween('end_time', [$matchStart, $matchEnd])
                            ->orWhere(function ($q2) use ($matchStart, $matchEnd) {
                                $q2->where('start_time', '<=', $matchStart)
                                    ->where('end_time', '>=', $matchEnd);
                            });
                    });
            })
            ->orderByDesc('rating')
            ->get();
    }

    /**
     * Check if a referee is available for a specific time slot
     */
    public function isRefereeAvailable(Referee $referee, string $date, string $startTime, string $endTime): bool
    {
        return !RefereeRental::where('referee_id', $referee->id)
            ->where('rental_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();
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

        // Update match total cost if it exists
        if ($match->total_cost) {
            $match->update([
                'total_cost' => $match->total_cost + $rental->rental_cost
            ]);
        } else {
            $match->update([
                'total_cost' => $rental->rental_cost
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
}
