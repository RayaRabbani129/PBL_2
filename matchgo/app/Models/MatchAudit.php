<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchAudit extends Model
{
    protected $fillable = [

        'match_id',
        'auditor_id',

        'home_team_review',
        'away_team_review',

        'sportsmanship_rating',

        'audit_notes',
        'game_review',

        'audited_at',
    ];

    protected $casts = [
        'audited_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function match()
    {
        return $this->belongsTo(Matches::class, 'match_id');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}