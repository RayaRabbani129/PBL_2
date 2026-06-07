<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Match_;

class MatchCost extends Model
{
    protected $fillable = [
        'match_id',
        'total_venue_cost',
        'home_team_cost',
        'away_team_cost',
        'home_team_players',
        'away_team_players',
        'home_cost_per_player',
        'away_cost_per_player',
        'is_finalized',
        'notes'
    ];

    public function match()
    {
        return $this->belongsTo(Matches::class, 'match_id');
    }
}