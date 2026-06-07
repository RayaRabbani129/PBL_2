<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Team;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $team = Team::where('user_id', Auth::id())
            ->with('members')
            ->with('stats')
            ->withCount('members')
            ->first();

        $rating = '0.0';

        if ($team && $team->stats) {
            $wins = $team->stats->wins ?? 0;
            $losses = $team->stats->losses ?? 0;
            $totalMatches = $team->stats->total_matches ?? 0;

            $draws = max(
                0,
                $totalMatches - $wins - $losses
            );

            /*
             * Sistem poin:
             * Menang = 3 poin
             * Seri   = 1 poin
             * Kalah  = 0 poin
             *
             * Rating dikonversi ke skala 0 - 5
             */
            if ($totalMatches > 0) {
                $earnedPoints = ($wins * 3) + $draws;
                $maxPoints = $totalMatches * 3;

                $ratingValue = ($earnedPoints / $maxPoints) * 5;

                // Pastikan rating tidak lebih dari 5
                $ratingValue = min($ratingValue, 5);

                $rating = number_format($ratingValue, 1);
            }
        }

        return view('user.profile.index', [
            'user' => $user,
            'team' => $team,
            'rating' => $rating,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'city'  => 'nullable|string|max:100',
            'bio'   => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = collect($validated)->except('photo')->toArray();

        $user->update($data);

        return back()->with('success', 'Profile updated successfully');
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('profile', $filename, 'public');

            $user->update([
                'photo' => 'profile/' . $filename,
            ]);
        }

        return back()->with('success', 'Profile photo updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        if (!password_verify($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect',
            ]);
        }

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    public function updateTeam(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'team_name'    => 'required|string|max:255',
            'position'     => 'required|string|max:100',
            'member_count' => 'required|integer|min:1',
            'team_city'    => 'nullable|string|max:100',
        ]);

        if ($user->team) {
            $user->team->update([
                'team_name'    => $validated['team_name'],
                'position'     => $validated['position'],
                'member_count' => $validated['member_count'],
                'city'         => $validated['team_city'],
            ]);
        } else {
            $user->team()->create([
                'team_name'    => $validated['team_name'],
                'position'     => $validated['position'],
                'member_count' => $validated['member_count'],
                'city'         => $validated['team_city'],
            ]);
        }

        return back()->with('success', 'Team updated successfully');
    }
}