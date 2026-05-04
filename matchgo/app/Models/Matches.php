<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $fillable = [
        'match_code',
        'home_team_id',
        'away_team_id',
        'venue_id',
        'match_datetime',
        'duration_minutes',
        'home_score',
        'away_score',
        'status',
        'total_cost',
        'notes'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function cost()
    {
        return $this->hasOne(MatchCost::class, 'match_id');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'match_id');
    }

    public function verification()
    {
        return $this->hasOne(MatchVerification::class, 'match_id');
    }
}