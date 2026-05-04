<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Team;
use App\Models\TeamSchedule;
use App\Models\MatchRequest;
use App\Models\Matches;
use App\Models\Notification;
use App\Services\MatchmakingService;

class MatchmakingController extends Controller
{
    public function __construct(protected MatchmakingService $matchmaking) {}

    /**
     * Halaman utama matchmaking.
     * GET  → tampilkan form + jadwal saya (idle state)
     * POST → jalankan matchmaking dengan filter
     */
    public function index(Request $request)
    {
        $myTeam = Team::with(['schedules', 'stats'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$myTeam) {
            return redirect()->route('team.create')
                ->with('warning', 'Buat tim terlebih dahulu sebelum mencari lawan.');
        }

        $mySchedules = $myTeam->schedules
            ->where('is_available', true)
            ->sortBy('day_of_week');

        $results  = collect();
        $searched = false;
        $filters  = [];

        if ($request->isMethod('post') || $request->has('search')) {
            $request->validate([
                'level'           => 'nullable|in:casual,semi_pro,pro',
                'max_distance'    => 'nullable|integer|min:1|max:100',
                'day_of_week'     => 'nullable|string',
                'use_my_schedule' => 'nullable|boolean',
            ]);

            $filters = $request->only(['level', 'max_distance', 'day_of_week', 'use_my_schedule']);

            if (isset($filters['day_of_week']) && $filters['day_of_week'] === '') {
                unset($filters['day_of_week']);
            }

            $results  = $this->matchmaking->findOpponents($myTeam, $filters);
            $searched = true;
        }

        return view('user.matchmaking.index', compact(
            'myTeam',
            'mySchedules',
            'results',
            'searched',
            'filters'
        ));
    }

    /**
     * AJAX — kembalikan hasil sebagai JSON.
     */
    public function search(Request $request)
    {
        $myTeam = Team::with(['schedules', 'stats'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'level'           => 'nullable|in:casual,semi_pro,pro',
            'max_distance'    => 'nullable|integer|min:1|max:100',
            'day_of_week'     => 'nullable|integer|min:0|max:6',
            'use_my_schedule' => 'nullable|boolean',
        ]);

        $filters = $request->only(['level', 'max_distance', 'day_of_week', 'use_my_schedule']);
        $results = $this->matchmaking->findOpponents($myTeam, $filters);

        return response()->json([
            'success' => true,
            'count'   => $results->count(),
            'results' => $results->values(),
        ]);
    }

    public function challenge(Request $request, Team $opponent)
    {
        // Pastikan hanya terima AJAX / JSON request
        if (! ($request->expectsJson() || $request->ajax())) {
            abort(403, 'Direct form submission tidak diizinkan.');
        }

        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        if ($myTeam->id === $opponent->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menantang tim sendiri.',
            ], 422);
        }

        $validated = $request->validate([
            'preferred_date' => 'required|date|after_or_equal:today',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
        ]);
        // Jika validasi gagal, Laravel otomatis return JSON 422 karena request->expectsJson()

        $alreadyChallenged = MatchRequest::where('team_id', $myTeam->id)
            ->where('matched_with', $opponent->id)
            ->where('status', 'searching')
            ->exists();

        if ($alreadyChallenged) {
            return response()->json([
                'success' => false,
                'message' => "Tantangan ke {$opponent->name} sudah terkirim dan masih menunggu respons.",
            ], 422);
        }

        $incomingRequest = MatchRequest::where('team_id', $opponent->id)
            ->where('preferred_date', $validated['preferred_date'])
            ->where('start_time', $validated['start_time'])
            ->where('status', 'searching')
            ->whereNull('matched_with')
            ->first();

        DB::beginTransaction();

        try {
            if ($incomingRequest) {
                // Auto-match
                $incomingRequest->update([
                    'matched_with' => $myTeam->id,
                    'status'       => 'matched',
                ]);

                MatchRequest::create([
                    'team_id'        => $myTeam->id,
                    'preferred_date' => $validated['preferred_date'],
                    'start_time'     => $validated['start_time'],
                    'end_time'       => $validated['end_time'],
                    'status'         => 'matched',
                    'matched_with'   => $opponent->id,
                ]);

                Matches::create([
                    'match_code'       => 'MCH-' . strtoupper(uniqid()),
                    'home_team_id'     => $opponent->id,
                    'away_team_id'     => $myTeam->id,
                    'match_datetime'   => $validated['preferred_date'] . ' ' . $validated['start_time'],
                    'duration_minutes' => $this->calcDuration($validated['start_time'], $validated['end_time']),
                    'status'           => 'scheduled',
                ]);

                $this->notify($opponent->user_id, 'match_confirmed',
                    "Pertandingan melawan {$myTeam->name} dikonfirmasi pada {$validated['preferred_date']} pukul {$validated['start_time']}! ⚽");
                $this->notify(auth()->id(), 'match_confirmed',
                    "Pertandingan melawan {$opponent->name} dikonfirmasi pada {$validated['preferred_date']} pukul {$validated['start_time']}! ⚽");

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => "Match langsung terkonfirmasi! ⚽ Kamu akan bertanding melawan {$opponent->name}.",
                ]);
            }

            // Targeted challenge
            MatchRequest::create([
                'team_id'        => $myTeam->id,
                'preferred_date' => $validated['preferred_date'],
                'start_time'     => $validated['start_time'],
                'end_time'       => $validated['end_time'],
                'status'         => 'searching',
                'matched_with'   => $opponent->id,
            ]);

            $this->notify($opponent->user_id, 'match_challenge',
                "{$myTeam->name} menantangmu pada {$validated['preferred_date']} pukul {$validated['start_time']}. Terima atau tolak tantangan ini!");

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Tantangan berhasil dikirim ke {$opponent->name}! 🔥 Tunggu konfirmasi dari mereka.",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim tantangan. Silakan coba lagi.',
                'debug'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan daftar tantangan masuk untuk tim saya.
     */
    public function incomingChallenges()
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        $challenges = MatchRequest::with(['team', 'team.stats'])
            ->where('matched_with', $myTeam->id)
            ->where('status', 'searching')
            ->latest()
            ->paginate(10);

        return view('user.matchmaking.incoming', compact('myTeam', 'challenges'));
    }

    /**
     * Tampilkan daftar tantangan yang saya kirim.
     */
    public function outgoingChallenges()
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        $challenges = MatchRequest::with(['matchedTeam', 'matchedTeam.stats'])
            ->where('team_id', $myTeam->id)
            ->whereNotNull('matched_with')
            ->latest()
            ->paginate(10);

        return view('user.matchmaking.outgoing', compact('myTeam', 'challenges'));
    }

    /**
     * Terima tantangan dari tim lawan.
     */
    public function acceptChallenge(Request $request, MatchRequest $matchRequest)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        if ($matchRequest->matched_with !== $myTeam->id) {
            abort(403, 'Tantangan ini bukan untukmu.');
        }

        if ($matchRequest->status !== 'searching') {
            return back()->with('error', 'Tantangan ini sudah tidak aktif.');
        }

        DB::beginTransaction();

        try {
            $matchRequest->update(['status' => 'matched']);

            // Buat sisi tantangan untuk tim kita
            MatchRequest::create([
                'team_id'        => $myTeam->id,
                'preferred_date' => $matchRequest->preferred_date,
                'start_time'     => $matchRequest->start_time,
                'end_time'       => $matchRequest->end_time,
                'status'         => 'matched',
                'matched_with'   => $matchRequest->team_id,
            ]);

            $match = Matches::create([
                'match_code'       => 'MCH-' . strtoupper(uniqid()),
                'home_team_id'     => $matchRequest->team_id,
                'away_team_id'     => $myTeam->id,
                'match_datetime'   => $matchRequest->preferred_date . ' ' . $matchRequest->start_time,
                'duration_minutes' => $this->calcDuration($matchRequest->start_time, $matchRequest->end_time),
                'status'           => 'scheduled',
            ]);

            $challenger = $matchRequest->team;

            $this->notify(
                $challenger->user_id,
                'challenge_accepted',
                "{$myTeam->name} menerima tantanganmu! Pertandingan dijadwalkan pada {$matchRequest->preferred_date} pukul {$matchRequest->start_time}. ✅"
            );

            $this->notify(
                auth()->id(),
                'match_confirmed',
                "Kamu menerima tantangan dari {$challenger->name}. Pertandingan pada {$matchRequest->preferred_date} pukul {$matchRequest->start_time}. ⚽"
            );

            DB::commit();

            return redirect()->route('matches.show', $match->id)
                ->with('success', "Tantangan diterima! Pertandingan melawan {$challenger->name} telah dijadwalkan. ⚽");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menerima tantangan. Silakan coba lagi.');
        }
    }

    /**
     * Tolak tantangan dari tim lawan.
     */
    public function rejectChallenge(Request $request, MatchRequest $matchRequest)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        if ($matchRequest->matched_with !== $myTeam->id) {
            abort(403, 'Tantangan ini bukan untukmu.');
        }

        if ($matchRequest->status !== 'searching') {
            return back()->with('error', 'Tantangan ini sudah tidak aktif.');
        }

        $validated = $request->validate([
            'reject_reason' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $matchRequest->update(['status' => 'rejected']);

            $challenger = $matchRequest->team;
            $reason     = $validated['reject_reason'] ?? 'Tidak ada alasan yang diberikan.';

            $this->notify(
                $challenger->user_id,
                'challenge_rejected',
                "{$myTeam->name} menolak tantanganmu pada {$matchRequest->preferred_date} pukul {$matchRequest->start_time}. Alasan: {$reason} ❌"
            );

            DB::commit();

            return back()->with('info', "Tantangan dari {$challenger->name} berhasil ditolak.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menolak tantangan. Silakan coba lagi.');
        }
    }

    /**
     * Batalkan tantangan yang sudah saya kirim (selama masih searching).
     */
    public function cancelChallenge(MatchRequest $matchRequest)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        if ($matchRequest->team_id !== $myTeam->id) {
            abort(403, 'Ini bukan tantanganmu.');
        }

        if ($matchRequest->status !== 'searching') {
            return back()->with('error', 'Tantangan sudah tidak bisa dibatalkan.');
        }

        DB::beginTransaction();

        try {
            $matchRequest->update(['status' => 'cancelled']);

            $opponent = $matchRequest->matchedTeam;

            if ($opponent) {
                $this->notify(
                    $opponent->user_id,
                    'challenge_cancelled',
                    "{$myTeam->name} membatalkan tantangan yang dikirim pada {$matchRequest->preferred_date} pukul {$matchRequest->start_time}. 🚫"
                );
            }

            DB::commit();

            return back()->with('info', 'Tantangan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membatalkan tantangan. Silakan coba lagi.');
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Hitung durasi dalam menit antara dua string waktu H:i.
     */
    private function calcDuration(string $start, string $end): int
    {
        return \Carbon\Carbon::createFromFormat('H:i', $start)
            ->diffInMinutes(\Carbon\Carbon::createFromFormat('H:i', $end));
    }

    /**
     * Buat notifikasi untuk user tertentu.
     */
    private function notify(int $userId, string $type, string $message): void
    {
        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'message' => $message,
            'status'  => 'unread',
        ]);
    }
}