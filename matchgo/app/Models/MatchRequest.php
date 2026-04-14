<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchRequest extends Model
{
    protected $fillable = [
        'team_id',
        'preferred_date',
        'start_time',
        'end_time',
        'status',
        'matched_with'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function matchedTeam()
    {
        return $this->belongsTo(Team::class, 'matched_with');
    }
}