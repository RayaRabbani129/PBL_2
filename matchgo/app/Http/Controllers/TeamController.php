<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Matches;

class TeamController extends Controller
{
    public function index()
    {
        $team = Team::where('user_id', auth()->id())
            ->with('members')
            ->first();

        $members = $team?->members ?? collect();

        $stats = (object) [
            'wins' => 0,
            'losses' => 0,
            'total_matches' => 0,
        ];

        if ($team) {

            $teamId = $team->id;

            // total win
            $wins = Matches::where('status', 'completed')
                ->where(function ($query) use ($teamId) {

                    $query->where(function ($q) use ($teamId) {
                        $q->where('home_team_id', $teamId)
                            ->whereColumn('home_score', '>', 'away_score');
                    })

                    ->orWhere(function ($q) use ($teamId) {
                        $q->where('away_team_id', $teamId)
                            ->whereColumn('away_score', '>', 'home_score');
                    });

                })
                ->count();

            // total loss
            $losses = Matches::where('status', 'completed')
                ->where(function ($query) use ($teamId) {

                    $query->where(function ($q) use ($teamId) {
                        $q->where('home_team_id', $teamId)
                            ->whereColumn('home_score', '<', 'away_score');
                    })

                    ->orWhere(function ($q) use ($teamId) {
                        $q->where('away_team_id', $teamId)
                            ->whereColumn('away_score', '<', 'home_score');
                    });

                })
                ->count();

            // total match
            $totalMatches = Matches::where('status', 'completed')
                ->where(function ($query) use ($teamId) {

                    $query->where('home_team_id', $teamId)
                        ->orWhere('away_team_id', $teamId);

                })
                ->count();

            $stats = (object) [
                'wins' => $wins,
                'losses' => $losses,
                'total_matches' => $totalMatches,
            ];
        }

        return view('user.team.index', compact(
            'team',
            'members',
            'stats'
        ));
    }

    public function create()
    {
        return view('user.team.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'province'    => 'required|string|max:100',
            'level'       => 'required|string',
            'description' => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'status'      => 'required|in:active,inactive',
            'logo_path'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->file('logo_path')->store('teams', 'public');
        }

        $data['user_id'] = auth()->id();
        Team::create($data);

        return redirect()->route('team.index')->with('success', 'Tim berhasil dibuat!');
    }

    public function edit(Team $team)
    {
        abort_if($team->user_id !== auth()->id(), 403);

        return view('user.team.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        abort_if($team->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'province'    => 'required|string|max:100',
            'level'       => 'required|string',
            'description' => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'status'      => 'required|in:active,inactive',
            'logo_path'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo_path')) {
            if ($team->logo_path) {
                Storage::disk('public')->delete($team->logo_path);
            }
            $data['logo_path'] = $request->file('logo_path')->store('teams', 'public');
        }

        $team->update($data);

        return redirect()->route('team.index')->with('success', 'Tim berhasil diperbarui!');
    }

    public function destroy(Team $team)
    {
        abort_if($team->user_id !== auth()->id(), 403);

        if ($team->logo_path) {
            Storage::disk('public')->delete($team->logo_path);
        }

        $team->delete();

        return redirect()->route('team.index')->with('success', 'Tim berhasil dihapus.');
    }
}