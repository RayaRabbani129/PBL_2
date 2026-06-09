<?php

namespace App\Http\Controllers;

use App\Models\MatchPayment;
use App\Models\Matches;
use App\Models\Notification;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FakePaymentController extends Controller
{
    public function createPayment(Request $request, Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();

        $this->authorizeMatchPayment($match, $myTeam);

        if ($match->status !== 'awaiting_payment') {
            return response()->json([
                'message' => 'Pembayaran hanya tersedia saat match menunggu pembayaran.',
            ], 422);
        }

        $payment = MatchPayment::firstOrCreate(
            ['match_id' => $match->id, 'team_id' => $myTeam->id],
            [
                'user_id' => $myTeam->user_id,
                'amount' => $this->paymentAmountForTeam($match, $myTeam->id),
                'status' => 'pending',
            ]
        );

        $payment->update([
            'user_id' => $payment->user_id ?: $myTeam->user_id,
            'amount' => $this->paymentAmountForTeam($match, $myTeam->id),
            'status' => 'pending',
            'paid_at' => null,
            'notes' => 'fake payment: menunggu konfirmasi',
        ]);

        // Fake processing: buat terasa real dengan urutan
        // - create => pending
        // - client refresh => paid akan ter-set kalau kedua tim sudah bayar

        return response()->json([
            'status' => 'pending',
            'message' => 'Pembayaran dibuat (pending).',
            // untuk UI, kita kembalikan juga order_id agar tampak seperti transaksi
            'order_id' => $payment->order_id ?: ('FAKE-' . $match->id . '-' . $myTeam->id),
        ]);
    }

    public function markPaid(Request $request, Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->firstOrFail();
        $this->authorizeMatchPayment($match, $myTeam);

        if ($match->status !== 'awaiting_payment') {
            return response()->json([
                'message' => 'Match bukan dalam status awaiting_payment.',
            ], 422);
        }

        $payment = MatchPayment::firstOrCreate(
            ['match_id' => $match->id, 'team_id' => $myTeam->id],
            [
                'user_id' => $myTeam->user_id,
                'amount' => $this->paymentAmountForTeam($match, $myTeam->id),
                'status' => 'pending',
            ]
        );

        DB::transaction(function () use ($payment, $match): void {
            $payment->update([
                'status' => 'paid',
                'paid_at' => $payment->paid_at ?? now(),
                'notes' => 'fake payment: paid manual',
                'gateway_status' => 'fake_paid',
                'order_id' => $payment->order_id ?: ('FAKE-' . $match->id . '-' . $payment->team_id),
            ]);

            $freshMatch = $payment->match->refresh();
            if ($freshMatch->status === 'awaiting_payment' && $freshMatch->allTeamsPaid()) {
                $freshMatch->update(['status' => 'ongoing']);

                foreach ([$freshMatch->homeTeam, $freshMatch->awayTeam] as $team) {
                    Notification::create([
                        'user_id' => $team->user_id,
                        'type' => 'match_confirmed',
                        'title' => 'Pembayaran Lengkap',
                        'message' => "Pembayaran kedua tim untuk {$freshMatch->match_code} sudah lengkap. Match sekarang berjalan.",
                        'status' => 'unread',
                    ]);
                }
            }
        });

        return response()->json([
            'status' => 'paid',
            'match_status' => $match->fresh()->status,
        ]);
    }

    private function authorizeMatchPayment(Matches $match, Team $myTeam): void
    {
        if ($match->home_team_id !== $myTeam->id && $match->away_team_id !== $myTeam->id) {
            abort(403);
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
}

