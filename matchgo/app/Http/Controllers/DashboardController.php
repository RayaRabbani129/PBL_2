<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches;
use App\Models\Team;
use App\Models\TeamSchedule;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        /* ── Tim milik user ── */
        $myTeam = Team::where('user_id', $userId)
            ->withCount('members')
            ->first();

        /* ── Jadwal aktif tim milik user ── */
        $mySchedules = TeamSchedule::whereHas('team', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('is_available', true)
            ->orderBy('day_of_week')
            ->get();

        /* ── Statistik: pertandingan selesai ── */
        $totalMatch = Matches::where('status', 'completed')->count();

        $totalWin = Matches::where('status', 'completed')
            ->whereColumn('home_score', '>', 'away_score')
            ->count();

        $totalLoss = Matches::where('status', 'completed')
            ->whereColumn('home_score', '<', 'away_score')
            ->count();

        $totalDraw = Matches::where('status', 'completed')
            ->whereColumn('home_score', '=', 'away_score')
            ->count();

        $winRate  = $totalMatch > 0 ? round(($totalWin  / $totalMatch) * 100) : 0;
        $lossRate = $totalMatch > 0 ? round(($totalLoss / $totalMatch) * 100) : 0;

        /* Rating sederhana: 3 poin per menang, 1 poin per draw */
        $teamRating = ($totalWin * 3) + ($totalDraw * 1);

        /* ── Pertandingan mendatang (pending / confirmed) ── */
        $upcomingMatches = Matches::whereIn('status', ['pending', 'confirmed'])
            ->orderBy('match_datetime')       // kolom benar sesuai skema
            ->limit(5)
            ->get();

        /* ── Riwayat pertandingan selesai ── */
        $recentMatches = Matches::where('status', 'completed')
            ->orderByDesc('match_datetime')   // kolom benar sesuai skema
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