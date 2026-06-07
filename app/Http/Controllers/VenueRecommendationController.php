<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\VenueSchedule;
use App\Models\Team;
use App\Services\VenueRecommendationService;

class VenueRecommendationController extends Controller
{
    public function __construct(protected VenueRecommendationService $venueService) {}

    /**
     * Halaman utama rekomendasi venue.
     * Bisa diakses standalone atau dari detail match (dengan opponent).
     */
    public function index(Request $request)
    {
        $myTeam = Team::with('schedules')->where('user_id', auth()->id())->first();

        if (!$myTeam) {
            return redirect()->route('team.create')
                ->with('warning', 'Buat tim terlebih dahulu.');
        }

        // Opponent team (opsional — dari matchmaking atau query param)
        $opponentTeam = null;
        if ($request->filled('opponent_id')) {
            $opponentTeam = Team::with('schedules')->find($request->opponent_id);
        }

        $venues      = collect();
        $searched    = false;
        $filters     = [];
        $midpoint    = null;

        if ($request->isMethod('post') || $request->has('search')) {
            $request->validate([
                'date'         => 'nullable|date|after_or_equal:today',
                'start_time'   => 'nullable|date_format:H:i',
                'end_time'     => 'nullable|date_format:H:i|after:start_time',
                'max_distance' => 'nullable|integer|min:1|max:100',
                'max_price'    => 'nullable|integer|min:0',
                'sort_by'      => 'nullable|in:distance,price,score',
            ]);

            $filters = $request->only([
                'date', 'start_time', 'end_time',
                'max_distance', 'max_price', 'sort_by',
            ]);

            // Hitung titik tengah antara dua tim (jika ada lawan)
            $midpoint = $this->venueService->calcMidpoint($myTeam, $opponentTeam);

            $venues   = $this->venueService->recommend($myTeam, $opponentTeam, $filters, $midpoint);
            $searched = true;
        }

        $venuesFormatted = $searched
        ? $venues->map(function ($v) {
            return [
                'id'          => $v['venue']->id,
                'name'        => $v['venue']->name,
                'address'     => $v['venue']->address ?? '',
                'lat'         => $v['venue']->latitude,
                'lng'         => $v['venue']->longitude,
                'price'       => $v['venue']->price_per_hour,
                'score'       => $v['score'],
                'distance_km' => $v['distance_km'],
            ];
        })->values()
        : collect();

        return view('user.venue.index', compact(
            'myTeam', 'opponentTeam',
            'venues', 'searched', 'filters', 'midpoint', 'venuesFormatted'
        ));
    }

    /**
     * Detail satu venue.
     */
    public function show(Venue $venue, Request $request)
    {
        $venue->load(['schedules', 'creator']);

        $date      = $request->get('date', today()->format('Y-m-d'));
        $available = $venue->schedules
            ->where('date', $date)
            ->where('is_available', true)
            ->sortBy('start_time');

        $upcomingDates = $venue->schedules
            ->where('date', '>=', today()->format('Y-m-d'))
            ->where('is_available', true)
            ->groupBy('date')
            ->take(7);

        return view('user.venue.show', compact('venue', 'date', 'available', 'upcomingDates'));
    }

    /**
     * AJAX — kembalikan venues sebagai JSON (untuk update map).
     */
    public function ajaxSearch(Request $request)
    {
        $myTeam = Team::with('schedules')->where('user_id', auth()->id())->firstOrFail();

        $opponentTeam = $request->filled('opponent_id')
            ? Team::with('schedules')->find($request->opponent_id)
            : null;

        $filters  = $request->only(['date', 'start_time', 'end_time', 'max_distance', 'max_price', 'sort_by']);
        $midpoint = $this->venueService->calcMidpoint($myTeam, $opponentTeam);
        $venues   = $this->venueService->recommend($myTeam, $opponentTeam, $filters, $midpoint);

        return response()->json([
            'success'  => true,
            'midpoint' => $midpoint,
            'count'    => $venues->count(),
            'venues'   => $venues->map(fn ($v) => [
                'id'             => $v['venue']->id,
                'name'           => $v['venue']->name,
                'address'        => $v['venue']->address,
                'city'           => $v['venue']->city,
                'latitude'       => $v['venue']->latitude,
                'longitude'      => $v['venue']->longitude,
                'price_per_hour' => $v['venue']->price_per_hour,
                'score'          => $v['score'],
                'distance_km'    => $v['distance_km'],
                'available_slots'=> $v['available_slots'],
            ])->values(),
        ]);
    }
}