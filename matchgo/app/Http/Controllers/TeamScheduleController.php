<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamSchedule;
use App\Models\Team;

class TeamScheduleController extends Controller
{
    public function index()
    {
        $team = Team::where('user_id', auth()->id())->first();

        if (!$team) {
            return redirect()->route('team.index')
                ->with('warning', 'Buat tim terlebih dahulu sebelum mencari lawan.');
        }

        $schedules = $team
            ? TeamSchedule::where('team_id', $team->id)->get()
            : collect();

        return view('user.schedule.index', compact('team', 'schedules'));
    }

    public function create()
    {
        return view('user.schedule.create');
    }

    public function store(Request $request)
    {
        $team = Team::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
        ]);

        TeamSchedule::create([
            'team_id'      => $team->id,
            'day_of_week'  => $request->day_of_week,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'is_available' => $request->is_available ?? true,
        ]);

        return redirect()->route('schedule.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(TeamSchedule $schedule)
    {
        $this->authorizeTeam($schedule);

        return view('user.schedule.edit', compact('schedule'));
    }

    public function update(Request $request, TeamSchedule $schedule)
    {
        $this->authorizeTeam($schedule);

        $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
        ]);

        $schedule->update($request->all());

        return redirect()->route('schedule.index')->with('success', 'Jadwal diupdate');
    }

    public function destroy(TeamSchedule $schedule)
    {
        $this->authorizeTeam($schedule);

        $schedule->delete();

        return back()->with('success', 'Jadwal dihapus');
    }

    private function authorizeTeam($schedule)
    {
        $team = Team::where('user_id', auth()->id())->first();

        if (!$team || $schedule->team_id !== $team->id) {
            abort(403);
        }
    }
}