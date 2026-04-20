<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('user.profile.index', [
            'user' => $user,
            'team' => $user->team // ← ini yang kurang
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

        // upload foto kalau ada
        if ($request->hasFile('photo')) {

            // hapus foto lama (opsional tapi disarankan)
            if ($user->photo && Storage::exists('public/' . $user->photo)) {
                Storage::delete('public/' . $user->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('public/profile', $filename);

            $data['photo'] = 'profile/' . $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully');
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