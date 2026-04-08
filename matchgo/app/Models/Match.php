<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $table = 'matches';

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
        'notes',
    ];

    protected $casts = [
        'match_datetime'   => 'datetime',
        'duration_minutes' => 'integer',
        'home_score'       => 'integer',
        'away_score'       => 'integer',
        'total_cost'       => 'decimal:2',
    ];

    /**
     * ========================
     * RELATIONSHIPS
     * ========================
     */

    // Relasi ke tim tuan rumah
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    // Relasi ke tim tamu
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    // Relasi ke venue/lapangan
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // Relasi ke biaya pertandingan (1:1)
    public function cost()
    {
        return $this->hasOne(MatchCost::class);
    }

    /**
     * ========================
     * HELPER METHODS
     * ========================
     */

    // Cek apakah match sudah selesai
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    // Cek apakah match masih pending
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Cek apakah match sedang berlangsung
    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }

    // Tentukan pemenang
    public function winner()
    {
        if (!$this->isCompleted()) {
            return null;
        }

        if ($this->home_score > $this->away_score) {
            return $this->homeTeam;
        }

        if ($this->away_score > $this->home_score) {
            return $this->awayTeam;
        }

        return null; // draw
    }

    // Cek draw
    public function isDraw()
    {
        return $this->isCompleted() &&
               $this->home_score === $this->away_score;
    }
}