<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Matches;
use App\Models\TeamStat;

class TeamController extends Controller
{
    public function index()
    {
        $team = Team::where('user_id', auth()->id())
            ->with('members')
            ->with('stats')
            ->first();

        $members = $team?->members ?? collect();

        $stats = $team?->stats ?? (object) [
            'wins'            => 0,
            'losses'          => 0,
            'total_matches'   => 0,
            'goals_scored'    => 0,
            'goals_conceded'  => 0,
        ];

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

        // insert team stats
        $teamStats = [
            'total_matches' => 0,
            'wins' => 0,
            'losses' => 0,
            'goals_scored' => 0,
            'goals_conceded' => 0
        ];

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->file('logo_path')->store('teams', 'public');
        }

        $data['user_id'] = auth()->id();
        $team = Team::create($data);
        TeamStat::create(array_merge($teamStats, ['team_id' => $team->id]));

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