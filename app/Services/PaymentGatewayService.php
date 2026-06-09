<?php

namespace App\Services;

use App\Models\MatchPayment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Midtrans\Transaction;
use Midtrans\Snap;

class PaymentGatewayService
{
    public function createInvoice(MatchPayment $payment): MatchPayment
    {
        return $this->createSnapTransaction($payment);
    }

    public function createSnapTransaction(MatchPayment $payment): MatchPayment
    {
        $this->ensureValidMidtransConfig();

        $payment->loadMissing([
            'match.cost',
            'match.homeTeam.owner',
            'match.awayTeam.owner',
            'match.refereeRental.referee',
            'team.owner',
            'user',
        ]);

        if ((float) $payment->amount <= 0) {
            throw new \InvalidArgumentException('Nominal pembayaran harus lebih dari Rp 0.');
        }

        $orderId = $payment->order_id ?: $this->makeOrderId($payment);
        $items = $this->itemDetails($payment);
        $customer = $this->customerDetails($payment);

        $transaction = Snap::createTransaction([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round((float) $payment->amount),
            ],
            'customer_details' => $customer,
            'item_details' => $items,
            'callbacks' => [
                'finish' => route('matches.payment.success', $payment->match),
                'error' => route('matches.payment.failed', $payment->match),
            ],
        ]);

        $payment->update([
            'gateway' => 'midtrans',
            'invoice_id' => $orderId,
            'order_id' => $orderId,
            'user_id' => $payment->user_id ?: $payment->team?->user_id,
            'snap_token' => $transaction->token,
            'payment_url' => $transaction->redirect_url ?? null,
            'expired_at' => $payment->expired_at ?: now()->addDay(),
            'status' => 'pending',
            'gateway_status' => 'pending',
            'raw_payload' => [
                'order_id' => $orderId,
                'customer_details' => $customer,
                'item_details' => $items,
            ],
        ]);

        return $payment->refresh();
    }

    public function validateSignature(
        string $orderId,
        string $statusCode,
        string $grossAmount,
        ?string $signature
    ): bool {
        $serverKey = (string) config('midtrans.server_key');

        if ($serverKey === '' || ! is_string($signature)) {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expected, $signature);
    }

    public function fetchTransactionStatus(string $orderId): array
    {
        $this->ensureValidMidtransConfig();

        return (array) Transaction::status($orderId);
    }

    public function mapTransactionStatus(?string $transactionStatus, ?string $fraudStatus = null): string
    {
        $transactionStatus = strtolower((string) $transactionStatus);
        $fraudStatus = strtolower((string) $fraudStatus);

        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? 'pending' : 'paid';
        }

        return match ($transactionStatus) {
            'settlement' => 'paid',
            'pending' => 'pending',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            'deny', 'failure' => 'failed',
            default => 'failed',
        };
    }

    public function isPaidStatus(?string $status): bool
    {
        return $this->mapTransactionStatus($status) === 'paid';
    }

    private function itemDetails(MatchPayment $payment): array
    {
        $amount = (int) round((float) $payment->amount);
        $refereeShare = 0;

        if ($payment->match->refereeRental) {
            $refereeShare = (int) round(((float) $payment->match->refereeRental->rental_cost) / 2);
        }

        $refereeShare = min($refereeShare, $amount);
        $venueShare = max(0, $amount - $refereeShare);
        $items = [];

        if ($venueShare > 0) {
            $items[] = [
                'id' => 'VENUE-' . $payment->match_id,
                'price' => $venueShare,
                'quantity' => 1,
                'name' => Str::limit('Bagian biaya venue ' . ($payment->team->name ?? 'Tim'), 50, ''),
            ];
        }

        if ($refereeShare > 0) {
            $items[] = [
                'id' => 'REF-' . $payment->match_id,
                'price' => $refereeShare,
                'quantity' => 1,
                'name' => Str::limit('Bagian biaya wasit ' . ($payment->team->name ?? 'Tim'), 50, ''),
            ];
        }

        if ($items === []) {
            $items[] = [
                'id' => 'MATCH-' . $payment->match_id,
                'price' => $amount,
                'quantity' => 1,
                'name' => Str::limit('Pembayaran match ' . ($payment->team->name ?? 'Tim'), 50, ''),
            ];
        }

        return $items;
    }

    private function customerDetails(MatchPayment $payment): array
    {
        $owner = $payment->team?->owner ?? $payment->user;

        return [
            'first_name' => $owner?->name ?? $payment->team?->name ?? 'MATCHGO User',
            'email' => $owner?->email,
            'phone' => $owner?->phone,
        ];
    }

    private function makeOrderId(MatchPayment $payment): string
    {
        return 'MATCH-' . $payment->match_id . '-' . $payment->team_id . '-' . Carbon::now()->format('Uu');
    }

    private function ensureValidMidtransConfig(): void
    {
        $serverKey = (string) config('midtrans.server_key');
        $clientKey = (string) config('midtrans.client_key');
        $isProduction = (bool) config('midtrans.is_production');

        if ($serverKey === '' || $clientKey === '') {
            throw new \RuntimeException('Konfigurasi Midtrans belum lengkap. Isi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di .env.');
        }

        if (! str_contains($serverKey, 'server') || ! str_contains($clientKey, 'client')) {
            throw new \RuntimeException('Format key Midtrans tidak sesuai. Pastikan MIDTRANS_SERVER_KEY berisi Server Key dan MIDTRANS_CLIENT_KEY berisi Client Key dari dashboard Midtrans.');
        }
    }
}
