<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches;

class DashboardController extends Controller
{
    public function index()
    {
        // hanya pertandingan selesai
        $matches = Matches::where('status', 'completed');

        // total pertandingan
        $totalMatch = $matches->count();

        // total menang
        $totalWin = Matches::where('status', 'completed')
            ->whereColumn('home_score', '>', 'away_score')
            ->count();

        // total kalah
        $totalLoss = Matches::where('status', 'completed')
            ->whereColumn('home_score', '<', 'away_score')
            ->count();

        // total draw
        $totalDraw = Matches::where('status', 'completed')
            ->whereColumn('home_score', '=', 'away_score')
            ->count();

        // win rate
        $winRate = $totalMatch > 0
            ? round(($totalWin / $totalMatch) * 100)
            : 0;
        
        $lossRate = $totalMatch > 0
            ? round(($totalLoss / $totalMatch) * 100)
            : 0;

        // rating sederhana
        $teamRating = number_format((($totalWin * 3) + $totalDraw), 1);

        return view('user.dashboard.index', compact(
            'totalMatch',
            'totalWin',
            'totalLoss',
            'totalDraw',
            'winRate',
            'lossRate',
            'teamRating'
        ));
    }

    // public function profile()
    // {
    //     return view('user.dashboard.profile');
    // }

    // public function matches()
    // {
    //     return view('user.dashboard.matches');
    // }

    // public function statistics()
    // {
    //     return view('user.dashboard.statistics');
    // }

    // public function bookings()
    // {
    //     return view('user.dashboard.bookings');
    // }
}