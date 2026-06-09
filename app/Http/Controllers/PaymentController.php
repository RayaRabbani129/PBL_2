<?php

namespace App\Http\Controllers;

use App\Models\MatchPayment;
use App\Models\Matches;
use App\Models\Notification;
use App\Models\Team;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(protected PaymentGatewayService $paymentGateway) {}

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
        ]);

        if ($payment->status === 'paid') {
            return response()->json([
                'message' => 'Tim kamu sudah membayar.',
                'status' => 'paid',
            ]);
        }

        if (in_array($payment->status, ['failed', 'expired', 'cancelled', 'rejected'], true)) {
            $payment->update([
                'order_id' => null,
                'invoice_id' => null,
                'snap_token' => null,
                'payment_url' => null,
                'payment_type' => null,
                'transaction_id' => null,
                'gateway_status' => null,
                'raw_payload' => null,
                'expired_at' => null,
                'paid_at' => null,
                'status' => 'pending',
            ]);
        }

        if (! $payment->snap_token || ($payment->expired_at && $payment->expired_at->isPast())) {
            if ($payment->expired_at && $payment->expired_at->isPast()) {
                $payment->update([
                    'order_id' => null,
                    'invoice_id' => null,
                    'snap_token' => null,
                    'payment_url' => null,
                    'expired_at' => null,
                    'status' => 'pending',
                ]);
            }

            $payment = $this->paymentGateway->createSnapTransaction($payment);
        }

        return response()->json([
            'snap_token' => $payment->snap_token,
            'order_id' => $payment->order_id,
            'status' => $payment->status,
        ]);
    }

    public function callback(Request $request)
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signature = $request->input('signature_key');

        if (! $this->paymentGateway->validateSignature($orderId, $statusCode, $grossAmount, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payment = MatchPayment::with(['match.homeTeam', 'match.awayTeam'])
            ->where('order_id', $orderId)
            ->first();

        if (! $payment) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (abs(((float) $payment->amount) - ((float) $grossAmount)) > 0.01) {
            return response()->json(['message' => 'Invalid gross amount'], 422);
        }

        $mappedStatus = $this->paymentGateway->mapTransactionStatus(
            $request->input('transaction_status'),
            $request->input('fraud_status')
        );

        DB::transaction(function () use ($payment, $request, $mappedStatus): void {
            $payload = $request->all();

            if ($payment->status === 'paid' && $mappedStatus !== 'paid') {
                $payment->update([
                    'gateway_status' => $request->input('transaction_status'),
                    'raw_payload' => $payload,
                ]);

                return;
            }

            $payment->update([
                'status' => $mappedStatus,
                'gateway_status' => $request->input('transaction_status'),
                'payment_type' => $request->input('payment_type'),
                'transaction_id' => $request->input('transaction_id'),
                'paid_at' => $mappedStatus === 'paid' ? ($payment->paid_at ?? now()) : $payment->paid_at,
                'raw_payload' => $payload,
            ]);

            if ($mappedStatus === 'paid') {
                $this->markMatchOngoingIfReady($payment->match->refresh());
            }
        });

        return response()->json(['message' => 'Callback processed']);
    }

    public function success(Matches $match)
    {
        $myTeam = Team::where('user_id', auth()->id())->first();

        if ($myTeam && in_array($myTeam->id, [$match->home_team_id, $match->away_team_id], true)) {
            $payment = MatchPayment::with(['match.homeTeam', 'match.awayTeam'])
                ->where('match_id', $match->id)
                ->where('team_id', $myTeam->id)
                ->whereNotNull('order_id')
                ->first();

            if ($payment && $payment->status !== 'paid') {
                $this->syncPaymentFromMidtrans($payment);
            }
        }

        return redirect()->route('matches.show', $match)
            ->with('success', 'Pembayaran sedang diproses. Status akan otomatis berubah setelah callback Midtrans diterima.');
    }

    public function failed(Matches $match)
    {
        return redirect()->route('matches.show', $match)
            ->with('error', 'Pembayaran belum berhasil. Silakan coba lagi dari halaman detail match.');
    }

    private function authorizeMatchPayment(Matches $match, Team $myTeam): void
    {
        if ($match->home_team_id !== $myTeam->id && $match->away_team_id !== $myTeam->id) {
            abort(403);
        }
    }

    private function markMatchOngoingIfReady(Matches $match): void
    {
        if ($match->status !== 'awaiting_payment' || ! $match->allTeamsPaid()) {
            return;
        }

        $match->update(['status' => 'ongoing']);

        foreach ([$match->homeTeam, $match->awayTeam] as $team) {
            Notification::create([
                'user_id' => $team->user_id,
                'type' => 'match_confirmed',
                'title' => 'Pembayaran Lengkap',
                'message' => "Pembayaran kedua tim untuk {$match->match_code} sudah lengkap. Match sekarang berjalan.",
                'status' => 'unread',
            ]);
        }
    }

    private function syncPaymentFromMidtrans(MatchPayment $payment): void
    {
        try {
            $status = $this->paymentGateway->fetchTransactionStatus($payment->order_id);
        } catch (\Throwable $e) {
            return;
        }

        $mappedStatus = $this->paymentGateway->mapTransactionStatus(
            $status['transaction_status'] ?? null,
            $status['fraud_status'] ?? null
        );

        if ($mappedStatus === 'pending') {
            return;
        }

        DB::transaction(function () use ($payment, $status, $mappedStatus): void {
            if ($payment->status === 'paid' && $mappedStatus !== 'paid') {
                return;
            }

            $payment->update([
                'status' => $mappedStatus,
                'gateway_status' => $status['transaction_status'] ?? null,
                'payment_type' => $status['payment_type'] ?? $payment->payment_type,
                'transaction_id' => $status['transaction_id'] ?? $payment->transaction_id,
                'paid_at' => $mappedStatus === 'paid' ? ($payment->paid_at ?? now()) : $payment->paid_at,
                'raw_payload' => $status,
            ]);

            if ($mappedStatus === 'paid') {
                $this->markMatchOngoingIfReady($payment->match->refresh());
            }
        });
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
