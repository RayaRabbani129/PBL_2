<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchVerification extends Model
{
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
        return $this->belongsTo(Matches::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}