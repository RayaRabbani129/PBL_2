<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Matches;
use App\Models\MatchCost;
use App\Models\MatchPayment;
use App\Models\MatchRequest;
use App\Models\MatchVerification;
use App\Models\Notification;
use App\Models\Team;
use App\Models\VenueSchedule;
use App\Services\PaymentGatewayService;
use App\Services\RefereeRentalService;
use App\Services\VenueRecommendationService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MatchController extends Controller
{
    public function __construct(
        protected VenueRecommendationService $venueRecommendation,
        protected RefereeRentalService $refereeService,
        protected PaymentGatewayService $paymentGateway
    ) {}

    public function index(Request $request)
    {
        $myTeam = Team::where('user_id', auth()->id())->first();

        if (!$myTeam) {
            return redirect()->route('team.create')
                ->with('warning', 'Buat tim terlebih dahulu.');
        }

        $tab = $request->get('tab', 'upcoming');

        $baseQuery = Matches::with(['homeTeam', 'awayTeam', 'venue', 'verification', 'payments'])
            ->where(function ($q) use ($myTeam) {
                $q->where('home_team_id', $myTeam->id)
                  ->orWhere('away_team_id', $myTeam->id);
            });

        $upcoming = (clone $baseQuery)
            ->whereIn('status', ['matched', 'confirmed', 'scheduled', 'awaiting_payment', 'ongoing'])
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
            'cost',
            'refereeRental.referee',
            'payments.team',
        ]);

        $isHome         = $match->home_team_id === $myTeam->id;
        $myTeamInMatch  = $isHome ? $match->homeTeam : $match->awayTeam;
        $oppTeamInMatch = $isHome ? $match->awayTeam : $match->homeTeam;

        $homeTeamMembers = $match->homeTeam->members->count();
        $awayTeamMembers = $match->awayTeam->members->count();

        $refereeCost = optional($match->refereeRental)->rental_cost ?? 0;
        $costRecord = $match->cost;

        if ($costRecord) {
            $homeTeamShare = $costRecord->home_team_cost;
            $awayTeamShare = $costRecord->away_team_cost;
            $homeCostPerMember = $costRecord->home_cost_per_player;
            $awayCostPerMember = $costRecord->away_cost_per_player;
        } else {
            $homeTeamShare = round($match->total_cost / 2, 2);
            $awayTeamShare = round($match->total_cost / 2, 2);
            $homeCostPerMember = $homeTeamMembers > 0 ? round($homeTeamShare / $homeTeamMembers, 2) : null;
            $awayCostPerMember = $awayTeamMembers > 0 ? round($awayTeamShare / $awayTeamMembers, 2) : null;
        }

        $myPayment = $match->paymentForTeam($myTeam->id);
        $homePayment = $match->paymentForTeam($match->home_team_id);
        $awayPayment = $match->paymentForTeam($match->away_team_id);

        return view('user.match.show', compact(
            'match', 'myTeam', 'isHome',
            'myTeamInMatch', 'oppTeamInMatch',
            'homeTeamMembers', 'awayTeamMembers',
            'homeTeamShare', 'awayTeamShare',
            'homeCostPerMember', 'awayCostPerMember',
            'myPayment', 'homePayment', 'awayPayment'
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
                'use_referee'    => (bool) $req->use_referee,
                'accept_url'     => route('matches.challenge.accept', $req),
                'reject_url'     => route('matches.challenge.reject', $req),
            ]);

        $upcomingCount = Matches::where(function ($q) use ($myTeam) {
                $q->where('home_team_id', $myTeam->id)
                  ->orWhere('away_team_id', $myTeam->id);
            })
            ->whereIn('status', ['matched', 'confirmed', 'scheduled', 'awaiting_payment', 'ongoing'])
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
                'awaiting_payment'
            );

            $matchRequest->update(['status' => 'matched']);

            MatchRequest::firstOrCreate(
                [
                    'team_id' => $myTeam->id,
                    'matched_with' => $matchRequest->team_id,
                    'preferred_date' => $matchRequest->preferred_date,
                    'start_time' => $matchRequest->start_time,
                    'end_time' => $matchRequest->end_time,
                ],
                [
                    'status' => 'matched',
                    'use_referee' => $matchRequest->use_referee,
                ]
            );

            if ($matchRequest->use_referee) {
                $this->refereeService->assignBestRefereeForMatch($match);
                $match->refresh();
            }

            $this->ensurePaymentRecords($match->fresh(['cost']));

            $successMessage = $matchRequest->use_referee
                ? 'Tantangan diterima! Silakan lanjutkan pembayaran. Wasit otomatis sudah dipilih.'
                : 'Tantangan diterima! Silakan lanjutkan pembayaran.';

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
                    'message'   => $successMessage,
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

    public function cancel(Request $request, Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();
        $this->authorizeMatch($match, $myTeam);

        if (!in_array($match->status, ['matched', 'accepted', 'confirmed', 'scheduled', 'awaiting_payment'])) {
            return back()->with('error', 'Match tidak bisa dibatalkan.');
        }

        $validated = $request->validate([
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        $refundSummary = [
            'refunded' => 0,
            'pending' => 0,
        ];

        \DB::transaction(function () use ($match, $myTeam, $validated, &$refundSummary): void {
            $match->loadMissing([
                'homeTeam',
                'awayTeam',
                'booking',
                'payments.team',
                'refereeRental',
            ]);

            $reason = $validated['cancel_reason'] ?? 'Match dibatalkan saat proses pembayaran.';
            $cancelledBy = $myTeam->name;
            $hasPaidPayment = $match->payments->contains(fn ($payment) => $payment->status === 'paid');

            $match->update([
                'status' => 'cancelled',
                'notes' => trim(($match->notes ? $match->notes . "\n\n" : '') .
                    "Dibatalkan oleh {$cancelledBy}. Alasan: {$reason}"),
            ]);

            if ($match->booking) {
                $match->booking->update(['status' => 'rejected']);

                VenueSchedule::where('field_id', $match->booking->field_id)
                    ->whereDate('date', Carbon::parse($match->booking->booking_date)->toDateString())
                    ->where('start_time', '<', $match->booking->end_time)
                    ->where('end_time', '>', $match->booking->start_time)
                    ->update(['is_available' => true]);
            }

            if ($match->refereeRental && $match->refereeRental->status !== 'cancelled') {
                $match->refereeRental->update(['status' => 'cancelled']);
            }

            foreach ($match->payments as $payment) {
                if ($payment->status === 'paid') {
                    try {
                        $reverseResult = $this->paymentGateway->reverseTransaction(
                            $payment,
                            "Match {$match->match_code} dibatalkan. {$reason}"
                        );

                        $payment->update([
                            'status' => $reverseResult['status'],
                            'gateway_status' => $reverseResult['gateway_status'],
                            'raw_payload' => array_merge($payment->raw_payload ?? [], [
                                'auto_refund' => [
                                    'processed_at' => now()->toDateTimeString(),
                                    'action' => $reverseResult['action'],
                                    'response' => $reverseResult['response'],
                                ],
                            ]),
                            'notes' => trim(($payment->notes ? $payment->notes . "\n\n" : '') .
                                "Match dibatalkan. Refund otomatis berhasil diproses untuk {$payment->team?->name}."),
                        ]);

                        $refundSummary['refunded']++;
                    } catch (\Throwable $e) {
                        $payment->update([
                            'status' => 'refund_pending',
                            'raw_payload' => array_merge($payment->raw_payload ?? [], [
                                'auto_refund' => [
                                    'processed_at' => now()->toDateTimeString(),
                                    'error' => $e->getMessage(),
                                ],
                            ]),
                            'notes' => trim(($payment->notes ? $payment->notes . "\n\n" : '') .
                                "Match dibatalkan. Refund otomatis gagal: {$e->getMessage()}. Dana perlu dikembalikan ke {$payment->team?->name}."),
                        ]);

                        $refundSummary['pending']++;
                    }

                    continue;
                }

                if (in_array($payment->status, ['pending', 'expired', 'failed', 'rejected'], true)) {
                    $payment->update([
                        'status' => 'cancelled',
                        'notes' => trim(($payment->notes ? $payment->notes . "\n\n" : '') .
                            'Invoice dibatalkan karena match dibatalkan.'),
                    ]);
                }
            }

            foreach ([$match->homeTeam, $match->awayTeam] as $team) {
                Notification::create([
                    'user_id' => $team->user_id,
                    'type' => 'challenge_cancelled',
                    'title' => 'Match Dibatalkan',
                    'message' => $hasPaidPayment
                        ? "Match {$match->match_code} dibatalkan oleh {$cancelledBy}. Pembayaran yang sudah masuk ditandai untuk refund."
                        : "Match {$match->match_code} dibatalkan oleh {$cancelledBy}.",
                    'status' => 'unread',
                ]);
            }
        });

        $message = 'Match berhasil dibatalkan.';

        if ($refundSummary['refunded'] > 0 && $refundSummary['pending'] === 0) {
            $message .= ' Pembayaran yang sudah masuk berhasil diproses refund otomatis.';
        } elseif ($refundSummary['refunded'] > 0 && $refundSummary['pending'] > 0) {
            $message .= ' Sebagian refund otomatis berhasil, sebagian masih perlu diproses admin.';
        } elseif ($refundSummary['pending'] > 0) {
            $message .= ' Refund otomatis belum berhasil, pembayaran ditandai untuk diproses admin.';
        }

        return redirect()->route('matches.index', ['tab' => 'cancelled'])
            ->with('success', $message);
    }

    public function inputScore(Request $request, Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();
        $this->authorizeMatch($match, $myTeam);

        if ($match->status !== 'ongoing') {
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

        return redirect()->route('matches.show', $match)
            ->with('success', 'Skor berhasil diinput, menunggu verifikasi admin.');
    }

    public function submitPayment(Request $request, Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();
        $this->authorizeMatch($match, $myTeam);

        if ($match->status !== 'awaiting_payment') {
            return back()->with('error', 'Pembayaran hanya bisa dikirim saat match menunggu pembayaran.');
        }

        $payment = MatchPayment::firstOrCreate(
            ['match_id' => $match->id, 'team_id' => $myTeam->id],
            ['amount' => $this->paymentAmountForTeam($match, $myTeam->id)]
        );

        if ($payment->status === 'paid') {
            return back()->with('info', 'Tim kamu sudah membayar untuk match ini.');
        }

        if (! $payment->payment_url || ($payment->expired_at && $payment->expired_at->isPast())) {
            $payment->update(['amount' => $this->paymentAmountForTeam($match, $myTeam->id)]);
            $payment = $this->paymentGateway->createInvoice($payment);
        }

        return redirect()->away($payment->payment_url);
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
        string $status = 'awaiting_payment'
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
                'created_by'   => $homeTeam->id,
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

    private function ensurePaymentRecords(Matches $match): void
    {
        $homePayment = MatchPayment::firstOrCreate(
            ['match_id' => $match->id, 'team_id' => $match->home_team_id],
            [
                'user_id' => $match->homeTeam?->user_id,
                'amount' => $this->paymentAmountForTeam($match, $match->home_team_id),
                'status' => 'pending',
            ]
        );

        $awayPayment = MatchPayment::firstOrCreate(
            ['match_id' => $match->id, 'team_id' => $match->away_team_id],
            [
                'user_id' => $match->awayTeam?->user_id,
                'amount' => $this->paymentAmountForTeam($match, $match->away_team_id),
                'status' => 'pending',
            ]
        );

        foreach ([$homePayment, $awayPayment] as $payment) {
            $amount = $this->paymentAmountForTeam($match, $payment->team_id);
            $payment->loadMissing('team');
            $payment->update([
                'amount' => $amount,
                'user_id' => $payment->user_id ?: $payment->team?->user_id,
            ]);

            if (! $payment->snap_token) {
                $this->paymentGateway->createInvoice($payment);
            }
        }
    }

    private function paymentAmountForTeam(Matches $match, int $teamId): float
    {
        $match->loadMissing('cost');

        if ($match->cost) {
            return (float) (
                $teamId === $match->home_team_id
                    ? $match->cost->home_team_cost
                    : $match->cost->away_team_cost
            );
        }

        return round(((float) $match->total_cost) / 2, 2);
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
