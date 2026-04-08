<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchCost extends Model
{
    protected $table = 'match_costs';

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
        'notes',
    ];

    protected $casts = [
        'total_venue_cost'      => 'decimal:2',
        'home_team_cost'        => 'decimal:2',
        'away_team_cost'        => 'decimal:2',
        'home_cost_per_player'  => 'decimal:2',
        'away_cost_per_player'  => 'decimal:2',
        'is_finalized'          => 'boolean',
    ];

    /**
     * Relasi ke Match (One-to-One)
     */
    public function match()
    {
        return $this->belongsTo(Matches::class);
    }

    /**
     * Auto hitung cost (optional helper method)
     */
    public function calculateCosts($venuePricePerHour, $durationMinutes)
    {
        $totalVenueCost = $venuePricePerHour * ($durationMinutes / 60);

        $this->total_venue_cost = $totalVenueCost;

        // Bagi 2 tim
        $this->home_team_cost = $totalVenueCost / 2;
        $this->away_team_cost = $totalVenueCost / 2;

        // Per pemain
        $this->home_cost_per_player = $this->home_team_players > 0
            ? $this->home_team_cost / $this->home_team_players
            : 0;

        $this->away_cost_per_player = $this->away_team_players > 0
            ? $this->away_team_cost / $this->away_team_players
            : 0;

        return $this;
    }
}