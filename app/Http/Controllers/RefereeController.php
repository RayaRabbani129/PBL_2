<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Referee;
use App\Models\RefereeRental;
use App\Services\RefereeRentalService;
use Illuminate\Http\Request;

class RefereeController extends Controller
{
    public function __construct(protected RefereeRentalService $refereeService) {}

    /**
     * Get available referees for a specific match
     */
    public function getAvailableReferees(Request $request, Matches $match)
    {
        $referees = $this->refereeService->getAvailableReferees($match);

        return response()->json([
            'success' => true,
            'data' => $referees->map(fn ($ref) => [
                'id' => $ref->id,
                'name' => $ref->name,
                'certification_level' => $ref->certification_level,
                'hourly_rate' => $ref->hourly_rate,
                'rating' => $ref->rating,
                'city' => $ref->city,
            ])->toArray(),
        ]);
    }

    /**
     * Assign a referee to a match
     */
    public function assignReferee(Request $request, Matches $match)
    {
        $validated = $request->validate([
            'referee_id' => 'required|exists:referees,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if referee is already assigned
        if ($match->refereeRental) {
            return response()->json([
                'success' => false,
                'message' => 'Wasit sudah ditugaskan untuk pertandingan ini',
            ], 422);
        }

        $referee = Referee::findOrFail($validated['referee_id']);

        // Check if referee is available
        if (!$this->refereeService->isRefereeAvailable(
            $referee,
            $match->match_datetime->toDateString(),
            $match->match_datetime->toTimeString(),
            $match->match_datetime->copy()->addMinutes($match->duration_minutes ?? 90)->toTimeString()
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Wasit tidak tersedia pada waktu pertandingan',
            ], 422);
        }

        $rental = $this->refereeService->createRefereeRental(
            $match,
            $referee,
            $validated['notes'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Wasit berhasil ditugaskan',
            'data' => $rental,
        ]);
    }

    /**
     * Remove referee from a match
     */
    public function removeReferee(Request $request, Matches $match)
    {
        if (!$match->refereeRental) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada wasit yang ditugaskan',
            ], 404);
        }

        $this->refereeService->cancelRental($match->refereeRental);

        return response()->json([
            'success' => true,
            'message' => 'Wasit berhasil dihapus',
        ]);
    }

    /**
     * Get referee details
     */
    public function show(Referee $referee)
    {
        $stats = $this->refereeService->getRefereeStats($referee);

        return response()->json([
            'success' => true,
            'data' => array_merge($referee->toArray(), $stats),
        ]);
    }

    /**
     * Get all referees
     */
    public function index(Request $request)
    {
        $query = Referee::where('is_available', true);

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('city', 'like', "%{$request->search}%");
        }

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('certification_level')) {
            $query->where('certification_level', $request->certification_level);
        }

        $referees = $query->orderByDesc('rating')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $referees,
        ]);
    }
}
