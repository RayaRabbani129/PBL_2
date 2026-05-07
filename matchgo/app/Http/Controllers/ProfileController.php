<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Team;
use App\Models\Matches;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $team = Team::where('user_id', auth()->id())
            ->with('members')
            ->first();

        $rating = '0.0';

        if ($team) {

            $teamId = $team->id;

            $wins = Matches::where('status', 'completed')
                ->where(function ($query) use ($teamId) {

                    $query->where(function ($q) use ($teamId) {

                        $q->where('home_team_id', $teamId)
                            ->whereColumn('home_score', '>', 'away_score');

                    })->orWhere(function ($q) use ($teamId) {

                        $q->where('away_team_id', $teamId)
                            ->whereColumn('away_score', '>', 'home_score');

                    });

                })
                ->count();

            $draws = Matches::where('status', 'completed')
                ->where(function ($query) use ($teamId) {

                    $query->where('home_team_id', $teamId)
                        ->orWhere('away_team_id', $teamId);

                })
                ->whereColumn('home_score', '=', 'away_score')
                ->count();

            $rating = number_format(($wins * 3) + $draws, 1);
        }

        return view('user.profile.index', [
            'user' => $user,
            'team' => $user->team,
            'rating' => $rating
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

        // ambil data text
        $data = collect($validated)->except('photo')->toArray();
        $user->update($data);

        return back()->with('success', 'Profile updated successfully');
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {

            // Hapus foto lama
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Simpan ke storage/app/public/profile
            $file->storeAs('profile', $filename, 'public');

            // Simpan path ke DB
            $user->update([
                'photo' => 'profile/' . $filename
            ]);
        }

        return back()->with('success', 'Profile photo updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password'     => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        if (!password_verify($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update(['password' => bcrypt($validated['password'])]);

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