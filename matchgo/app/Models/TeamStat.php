<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamStat extends Model
{
    protected $fillable = [
        'team_id',
        'total_matches',
        'wins',
        'losses',
        'goals_scored',
        'goals_conceded'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}