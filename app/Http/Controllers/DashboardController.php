<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Team;
use App\Models\TeamSchedule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | TEAM USER
        |--------------------------------------------------------------------------
        */

        $myTeam = Team::where('user_id', $userId)
            ->with([
                'stats',
            ])
            ->withCount('members')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | TEAM SCHEDULE
        |--------------------------------------------------------------------------
        */

        $mySchedules = TeamSchedule::whereHas(
                'team',
                fn ($q) => $q->where('user_id', $userId)
            )
            ->where('is_available', true)
            ->orderBy('day_of_week')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TEAM STATS
        |--------------------------------------------------------------------------
        */

        $stats = $myTeam?->stats;

        $totalMatch = $stats?->total_matches ?? 0;

        $totalWin = $stats?->wins ?? 0;

        $totalLoss = $stats?->losses ?? 0;

        $totalDraw = max(
            0,
            $totalMatch - ($totalWin + $totalLoss)
        );

        /*
        |--------------------------------------------------------------------------
        | WIN / LOSS RATE
        |--------------------------------------------------------------------------
        */

        $winRate = $totalMatch > 0
            ? round(($totalWin / $totalMatch) * 100)
            : 0;

        $lossRate = $totalMatch > 0
            ? round(($totalLoss / $totalMatch) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TEAM RATING
        |--------------------------------------------------------------------------
        | Rating maksimal 5.0
        |
        | Menang = 3 poin
        | Seri   = 1 poin
        | Kalah  = 0 poin
        |
        | Rumus:
        | rating = (poin diperoleh / poin maksimal) * 5
        |--------------------------------------------------------------------------
        */

        $teamRating = '0.0';

        if ($totalMatch > 0) {
            $earnedPoints = ($totalWin * 3) + ($totalDraw * 1);
            $maxPoints = $totalMatch * 3;

            $ratingValue = ($earnedPoints / $maxPoints) * 5;

            // Pastikan rating tidak lebih dari 5 dan tidak kurang dari 0
            $ratingValue = max(0, min($ratingValue, 5));

            $teamRating = number_format($ratingValue, 1);
        }

        /*
        |--------------------------------------------------------------------------
        | UPCOMING MATCHES
        |--------------------------------------------------------------------------
        */

        $upcomingMatches = Matches::where(function ($query) use ($myTeam) {
                $query
                    ->where('home_team_id', $myTeam?->id)
                    ->orWhere('away_team_id', $myTeam?->id);
            })
            ->whereIn('status', [
                'scheduled',
                'ongoing',
                'confirmed',
            ])
            ->with([
                'homeTeam',
                'awayTeam',
                'venue',
            ])
            ->orderBy('match_datetime')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT MATCHES
        |--------------------------------------------------------------------------
        */

        $recentMatches = Matches::where(function ($query) use ($myTeam) {
                $query
                    ->where('home_team_id', $myTeam?->id)
                    ->orWhere('away_team_id', $myTeam?->id);
            })
            ->where('status', 'completed')
            ->with([
                'homeTeam',
                'awayTeam',
                'venue',
            ])
            ->latest('match_datetime')
            ->limit(5)
            ->get();

        return view('user.dashboard.index', compact(
            'myTeam',
            'mySchedules',

            'totalMatch',
            'totalWin',
            'totalLoss',
            'totalDraw',

            'winRate',
            'lossRate',

            'teamRating',

            'upcomingMatches',
            'recentMatches',
        ));
    }
}