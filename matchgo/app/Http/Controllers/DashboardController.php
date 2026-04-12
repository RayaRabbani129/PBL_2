<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('user.dashboard.index');
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