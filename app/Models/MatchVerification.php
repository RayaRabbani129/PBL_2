<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchVerification extends Model
{
    protected static function booted(): void
    {
        static::creating(function (MatchVerification $verification): void {
            $verification->verified_by ??= auth()->id();
        });

        static::saving(function (MatchVerification $verification): void {
            if ($verification->status === 'valid') {
                $verification->status = 'verified';
            }

            if ($verification->status === 'cheating') {
                $verification->status = 'rejected';
            }

            $verification->verified_by ??= auth()->id();
        });
    }

    protected $fillable = [
        'match_id',
        'score_team_a',
        'score_team_b',
        'status',
        'notes',
        'verified_by'
    ];

    public function match()
    {
        return $this->belongsTo(Matches::class, 'match_id');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
