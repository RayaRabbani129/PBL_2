<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matches;
use App\Models\MatchRequest;
use App\Models\MatchVerification;
use App\Models\Notification;
use App\Models\Team;
use Illuminate\Support\Str;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $myTeam = Team::where('user_id', auth()->id())->first();

        if (!$myTeam) {
            return redirect()->route('team.create')
                ->with('warning', 'Buat tim terlebih dahulu.');
        }

        $tab = $request->get('tab', 'upcoming');

        $baseQuery = Matches::with(['homeTeam', 'awayTeam', 'venue', 'verification'])
            ->where(function ($q) use ($myTeam) {
                $q->where('home_team_id', $myTeam->id)
                  ->orWhere('away_team_id', $myTeam->id);
            });

        $upcoming = (clone $baseQuery)
            ->whereIn('status', ['matched', 'confirmed', 'ongoing'])
            ->orderBy('match_datetime')
            ->get();

        $completed = (clone $baseQuery)
            ->where('status', 'completed')
            ->orderByDesc('match_datetime')
            ->get();

        $cancelled = (clone $baseQuery)
            ->where('status', 'cancelled')
            ->orderByDesc('match_datetime')
            ->get();

        // Tantangan masuk ke tim saya
        $incoming = MatchRequest::with(['team', 'team.stats'])
            ->where('matched_with', $myTeam->id)
            ->where('status', 'searching')
            ->orderByDesc('created_at')
            ->get();

        // Tantangan yang saya kirim, masih menunggu
        $outgoing = MatchRequest::with(['matchedTeam', 'matchedTeam.stats'])
            ->where('team_id', $myTeam->id)
            ->whereNotNull('matched_with')
            ->where('status', 'searching')
            ->orderByDesc('created_at')
            ->get();

        $counts = [
            'upcoming'  => $upcoming->count(),
            'completed' => $completed->count(),
            'incoming'  => $incoming->count(),
            'outgoing'  => $outgoing->count(),
        ];

        return view('user.match.index', compact(
            'myTeam', 'tab',
            'upcoming', 'completed', 'cancelled',
            'incoming', 'outgoing',
            'counts'
        ));
    }

    public function show(Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();
        $this->authorizeMatch($match, $myTeam);

        $match->load(['homeTeam', 'awayTeam', 'venue', 'verification.auditor', 'booking', 'cost']);

        $isHome         = $match->home_team_id === $myTeam->id;
        $myTeamInMatch  = $isHome ? $match->homeTeam : $match->awayTeam;
        $oppTeamInMatch = $isHome ? $match->awayTeam : $match->homeTeam;

        return view('user.match.show', compact(
            'match', 'myTeam', 'isHome',
            'myTeamInMatch', 'oppTeamInMatch'
        ));
    }

    /**
     * POST /matches/challenge/{matchRequest}/accept
     * Mendukung request biasa maupun AJAX (Accept: application/json).
     */
    public function acceptChallenge(Request $request, MatchRequest $matchRequest)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        if ($matchRequest->matched_with !== $myTeam->id) {
            return $this->ajaxOrAbort($request, 403, 'Tantangan ini bukan untukmu.');
        }

        if ($matchRequest->status !== 'searching') {
            return $this->ajaxOrBack($request, 'error', 'Tantangan ini sudah diproses.');
        }

        \DB::beginTransaction();

        try {
            $match = Matches::create([
                'match_code'       => 'MG-' . strtoupper(Str::random(8)),
                'home_team_id'     => $matchRequest->team_id,
                'away_team_id'     => $myTeam->id,
                'match_datetime'   => $matchRequest->preferred_date . ' ' . $matchRequest->start_time,
                'duration_minutes' => $this->calcDuration($matchRequest->start_time, $matchRequest->end_time),
                'status'           => 'confirmed',
            ]);

            $matchRequest->update(['status' => 'matched']);

            // Notifikasi ke penantang
            Notification::create([
                'user_id' => $matchRequest->team->user_id,
                'type'    => 'match_confirmed',
                'message' => "{$myTeam->name} menerima tantanganmu! Pertandingan dijadwalkan pada {$matchRequest->preferred_date} pukul {$matchRequest->start_time}. ✅",
                'status'  => 'unread',
            ]);

            \DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => true,
                    'message'   => '✅ Tantangan diterima! Match telah terjadwal.',
                    'match_url' => route('match.show', $match),
                ]);
            }

            return redirect()->route('matches.show', $match)
                ->with('success', '✅ Tantangan diterima! Match telah terjadwal.');

        } catch (\Exception $e) {
            \DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan. Silakan coba lagi.',
                    'error'   => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * POST /matches/challenge/{matchRequest}/reject
     * Mendukung request biasa maupun AJAX (Accept: application/json).
     */
    public function rejectChallenge(Request $request, MatchRequest $matchRequest)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        if ($matchRequest->matched_with !== $myTeam->id) {
            return $this->ajaxOrAbort($request, 403, 'Forbidden.');
        }

        if ($matchRequest->status !== 'searching') {
            return $this->ajaxOrBack($request, 'error', 'Tantangan ini sudah diproses.');
        }

        $validated = $request->validate([
            'reject_reason' => 'nullable|string|max:255',
        ]);

        $matchRequest->update(['status' => 'rejected']);

        $reason = $validated['reject_reason'] ?? 'Tidak ada alasan yang diberikan.';

        Notification::create([
            'user_id' => $matchRequest->team->user_id,
            'type'    => 'challenge_rejected',
            'message' => "{$myTeam->name} menolak tantanganmu. Alasan: {$reason} ❌",
            'status'  => 'unread',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tantangan berhasil ditolak.',
            ]);
        }

        return back()->with('info', 'Tantangan telah ditolak.');
    }

    public function cancel(Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();
        $this->authorizeMatch($match, $myTeam);

        if (!in_array($match->status, ['matched', 'accepted'])) {
            return back()->with('error', 'Match tidak bisa dibatalkan.');
        }

        $match->update(['status' => 'cancelled']);

        return redirect()->route('match.index')
            ->with('success', 'Match berhasil dibatalkan.');
    }

    public function inputScore(Request $request, Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();
        $this->authorizeMatch($match, $myTeam);

        if (!in_array($match->status, ['matched', 'accepted'])) {
            return back()->with('error', 'Skor hanya bisa diinput untuk match yang terjadwal.');
        }

        $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'notes'      => 'nullable|string|max:500',
        ]);

        MatchVerification::updateOrCreate(
            ['match_id' => $match->id],
            [
                'score_team_a' => $request->home_score,
                'score_team_b' => $request->away_score,
                'status'       => 'pending',
                'notes'        => $request->notes,
            ]
        );

        $match->update([
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
            'status'     => 'completed',
        ]);

        return redirect()->route('match.show', $match)
            ->with('success', 'Skor berhasil diinput, menunggu verifikasi admin.');
    }

    public function history()
    {
        $myTeam = Team::where('user_id', auth()->id())->first();

        if (!$myTeam) {
            return redirect()->route('team.create')
                ->with('warning', 'Buat tim terlebih dahulu.');
        }

        $matches = Matches::with([
                'homeTeam',
                'awayTeam',
                'venue',
                'verification'
            ])
            ->where(function ($q) use ($myTeam) {
                $q->where('home_team_id', $myTeam->id)
                ->orWhere('away_team_id', $myTeam->id);
            })
            ->where('status', 'completed')
            ->orderByDesc('match_datetime')
            ->get();

        return view('user.match.history', compact(
            'matches',
            'myTeam'
        ));
    }

    // ─────────────────────────────────────────────
    private function authorizeMatch(Matches $match, Team $myTeam): void
    {
        if ($match->home_team_id !== $myTeam->id && $match->away_team_id !== $myTeam->id) {
            abort(403);
        }
    }

    private function calcDuration(?string $start, ?string $end): ?int
    {
        if (!$start || !$end) return null;
        $s = strtotime($start);
        $e = strtotime($end);
        return $e > $s ? (int)(($e - $s) / 60) : null;
    }

    /**
     * Helper: abort dengan JSON jika request AJAX, abort biasa jika tidak.
     */
    private function ajaxOrAbort(Request $request, int $code, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], $code);
        }
        abort($code, $message);
    }

    /**
     * Helper: kembalikan JSON error atau redirect back dengan flash message.
     */
    private function ajaxOrBack(Request $request, string $type, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }
        return back()->with($type, $message);
    }
}