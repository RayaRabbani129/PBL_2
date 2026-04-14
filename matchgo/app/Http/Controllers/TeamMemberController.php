<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamMemberController extends Controller
{
    /**
     * Pastikan user punya tim aktif sebelum aksi apapun.
     */
    private function getUserTeam()
    {
        return Team::where('user_id', Auth::id())->firstOrFail();
    }

    /**
     * Show form tambah member.
     */
    public function create()
    {
        $team = $this->getUserTeam();

        return view('user.team.members.create', compact('team'));
    }

    /**
     * Simpan member baru.
     */
    public function store(Request $request)
    {
        $team = $this->getUserTeam();

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:100'],
            'role'   => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive,substitute'],
        ]);

        $team->members()->create($validated);

        return redirect()
            ->route('team.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Show form edit member.
     */
    public function edit(TeamMember $member)
    {
        $this->authorizeTeamMember($member);

        $team = $member->team;

        return view('user.team.members.edit', compact('team', 'member'));
    }

    /**
     * Update member.
     */
    public function update(Request $request, TeamMember $member)
    {
        $this->authorizeTeamMember($member);

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:100'],
            'role'   => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive,substitute'],
        ]);

        $member->update($validated);

        return redirect()
            ->route('team.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Hapus member.
     */
    public function destroy(TeamMember $member)
    {
        $this->authorizeTeamMember($member);

        $member->delete();

        return redirect()
            ->route('team.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    /**
     * Pastikan member milik tim milik user yang sedang login.
     */
    private function authorizeTeamMember(TeamMember $member)
    {
        $team = Team::where('user_id', Auth::id())->first();

        abort_if(!$team || $member->team_id !== $team->id, 403, 'Akses ditolak.');
    }
}