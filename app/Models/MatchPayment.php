<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchPayment extends Model
{
    protected $fillable = [
        'match_id',
        'team_id',
        'user_id',
        'order_id',
        'amount',
        'gateway',
        'invoice_id',
        'snap_token',
        'payment_url',
        'expired_at',
        'method',
        'payer_name',
        'proof_path',
        'status',
        'gateway_status',
        'payment_type',
        'transaction_id',
        'raw_payload',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    public function match()
    {
        return $this->belongsTo(Matches::class, 'match_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
