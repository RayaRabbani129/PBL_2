<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Matches;
use App\Models\MatchCost;
use App\Models\MatchRequest;
use App\Models\MatchVerification;
use App\Models\Notification;
use App\Models\Team;
use App\Models\VenueSchedule;
use App\Services\VenueRecommendationService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MatchController extends Controller
{
    public function __construct(protected VenueRecommendationService $venueRecommendation) {}

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

        $incoming = MatchRequest::with(['team', 'team.stats'])
            ->where('matched_with', $myTeam->id)
            ->where('status', 'searching')
            ->orderByDesc('created_at')
            ->get();

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

        $match->load([
            'homeTeam.members',
            'awayTeam.members',
            'venue',
            'field',
            'verification.auditor',
            'booking',
            'cost'
        ]);

        $isHome         = $match->home_team_id === $myTeam->id;
        $myTeamInMatch  = $isHome ? $match->homeTeam : $match->awayTeam;
        $oppTeamInMatch = $isHome ? $match->awayTeam : $match->homeTeam;

        $homeTeamMembers = $match->homeTeam->members->count();
        $awayTeamMembers = $match->awayTeam->members->count();

        $homeTeamShare = round($match->total_cost / 2, 2);
        $awayTeamShare = round($match->total_cost / 2, 2);

        $homeCostPerMember = $homeTeamMembers > 0 ? round($homeTeamShare / $homeTeamMembers, 2) : null;
        $awayCostPerMember = $awayTeamMembers > 0 ? round($awayTeamShare / $awayTeamMembers, 2) : null;

        return view('user.match.show', compact(
            'match', 'myTeam', 'isHome',
            'myTeamInMatch', 'oppTeamInMatch',
            'homeTeamMembers', 'awayTeamMembers',
            'homeTeamShare', 'awayTeamShare',
            'homeCostPerMember', 'awayCostPerMember'
        ));
    }

    public function poll()
    {
        $myTeam = Team::where('user_id', auth()->id())->first();

        if (!$myTeam) {
            return response()->json(['error' => 'No team'], 403);
        }

        $incoming = MatchRequest::with(['team'])
            ->where('matched_with', $myTeam->id)
            ->where('status', 'searching')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($req) => [
                'id'             => $req->id,
                'team_name'      => $req->team->name,
                'team_initials'  => strtoupper(substr($req->team->name, 0, 2)),
                'team_city'      => $req->team->city ?? null,
                'team_level'     => $req->team->level
                                        ? ucfirst(str_replace('_', ' ', $req->team->level))
                                        : null,
                'preferred_date' => Carbon::parse($req->preferred_date)
                                        ->translatedFormat('l, d M Y'),
                'start_time'     => Carbon::parse($req->start_time)->format('H:i'),
                'end_time'       => Carbon::parse($req->end_time)->format('H:i'),
                'accept_url'     => route('matches.challenge.accept', $req),
                'reject_url'     => route('matches.challenge.reject', $req),
            ]);

        $upcomingCount = Matches::where(function ($q) use ($myTeam) {
                $q->where('home_team_id', $myTeam->id)
                  ->orWhere('away_team_id', $myTeam->id);
            })
            ->whereIn('status', ['matched', 'confirmed', 'ongoing'])
            ->count();

        $outgoingCount = MatchRequest::where('team_id', $myTeam->id)
            ->whereNotNull('matched_with')
            ->where('status', 'searching')
            ->count();

        return response()->json([
            'incoming'       => $incoming,
            'incoming_count' => $incoming->count(),
            'upcoming_count' => $upcomingCount,
            'outgoing_count' => $outgoingCount,
        ]);
    }

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
            $match = $this->createAutoMatch(
                $matchRequest->team,
                $myTeam,
                $matchRequest->preferred_date,
                $matchRequest->start_time,
                $matchRequest->end_time,
                'confirmed'
            );

            $matchRequest->update(['status' => 'matched']);

            Notification::create([
                'user_id' => $matchRequest->team->user_id,
                'type'    => 'match_confirmed',
                'title'   => 'Tantangan Diterima',
                'message' => "{$myTeam->name} menerima tantanganmu! Pertandingan dijadwalkan pada {$matchRequest->preferred_date} pukul {$matchRequest->start_time}. ✅",
                'status'  => 'unread',
            ]);

            \DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => true,
                    'message'   => '✅ Tantangan diterima! Match telah terjadwal.',
                    'match_url' => route('matches.show', $match),
                ]);
            }

            return redirect()->route('matches.show', $match)
                ->with('success', '✅ Tantangan diterima! Match telah terjadwal.');

        } catch (\Exception $e) {
            \DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

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
            'title'   => 'Tantangan Ditolak',
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

        $matches = Matches::with(['homeTeam', 'awayTeam', 'venue', 'verification'])
            ->where(function ($q) use ($myTeam) {
                $q->where('home_team_id', $myTeam->id)
                ->orWhere('away_team_id', $myTeam->id);
            })
            ->where('status', 'completed')
            ->orderByDesc('match_datetime')
            ->get();

        return view('user.match.history', compact('matches', 'myTeam'));
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

    private function createAutoMatch(
        Team   $homeTeam,
        Team   $awayTeam,
        string $date,
        string $startTime,
        string $endTime,
        string $status = 'confirmed'
    ): Matches {
        $duration = $this->calcDuration($startTime, $endTime) ?? 0;

        $result = $this->venueRecommendation->findBestVenueForMatch(
            $homeTeam,
            $awayTeam,
            $date,
            $startTime,
            $endTime
        );

        /** @var \App\Models\Venue|null $venue */
        $venue = $result['venue'] ?? null;

        /** @var \App\Models\Field|null $field */
        $field = $result['field'] ?? null;

        $totalCost = ($field && $duration > 0)
            ? round((float) $field->price_per_hour * ($duration / 60), 2)
            : 0;

        $match = Matches::create([
            'match_code'       => 'MG-' . strtoupper(Str::random(8)),
            'home_team_id'     => $homeTeam->id,
            'away_team_id'     => $awayTeam->id,
            'venue_id'         => $venue?->id,
            'field_id'         => $field?->id,
            'match_datetime'   => "$date $startTime",
            'duration_minutes' => $duration,
            'status'           => $status,
            'total_cost'       => $totalCost,
        ]);

        if ($venue && $field) {
            Booking::create([
                'match_id'     => $match->id,
                'venue_id'     => $venue->id,
                'field_id'     => $field->id,
                'booking_date' => $date,
                'start_time'   => $startTime,
                'end_time'     => $endTime,
                'status'       => 'approved',
                'created_by'   => auth()->id(),
            ]);

            // ─────────────────────────────────────────────────────────
            // Mark semua VenueSchedule yang overlap dengan slot ini
            // menjadi tidak tersedia, agar tidak bisa dipesan lagi.
            //
            // Kondisi overlap:
            //   schedule.start_time < request.end_time
            //   AND schedule.end_time   > request.start_time
            // ─────────────────────────────────────────────────────────
            VenueSchedule::where('field_id', $field->id)
                ->whereDate('date', Carbon::parse($date)->toDateString())
                ->where('start_time', '<', $endTime)
                ->where('end_time',   '>',  $startTime)
                ->update(['is_available' => false]);

            \Log::info('[Booking] Schedule dinonaktifkan', [
                'field_id'   => $field->id,
                'date'       => $date,
                'start_time' => $startTime,
                'end_time'   => $endTime,
            ]);

            $homeCount = max(1, $homeTeam->members()->count());
            $awayCount = max(1, $awayTeam->members()->count());
            $teamShare  = round($totalCost / 2, 2);

            MatchCost::create([
                'match_id'             => $match->id,
                'total_venue_cost'     => $totalCost,
                'home_team_cost'       => $teamShare,
                'away_team_cost'       => $teamShare,
                'home_team_players'    => $homeCount,
                'away_team_players'    => $awayCount,
                'home_cost_per_player' => round($teamShare / $homeCount, 2),
                'away_cost_per_player' => round($teamShare / $awayCount, 2),
                'is_finalized'         => false,
                'notes'                => 'Auto split bill 50:50 berdasarkan jumlah anggota tim.',
            ]);
        }

        return $match;
    }

    private function ajaxOrAbort(Request $request, int $code, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], $code);
        }
        abort($code, $message);
    }

    private function ajaxOrBack(Request $request, string $type, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }
        return back()->with($type, $message);
    }
}