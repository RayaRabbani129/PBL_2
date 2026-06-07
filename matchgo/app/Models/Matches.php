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
        'field_id',
        'referee_id',
        'match_datetime',
        'duration_minutes',
        'home_score',
        'away_score',
        'status',
        'total_cost',
        'notes',
        'stats_processed',
    ];

    protected $casts = [
        'match_datetime' => 'datetime',
        'stats_processed' => 'boolean',
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

    public function audits()
    {
        return $this->hasMany(MatchAudit::class, 'match_id');
    }

    public function latestAudit()
    {
        return $this->hasOne(MatchAudit::class, 'match_id')
            ->latestOfMany();
    }

    public function audit()
    {
        return $this->hasOne(
            MatchAudit::class,
            'match_id'
        )->latestOfMany();
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function refereeRental()
    {
        return $this->hasOne(RefereeRental::class, 'match_id');
    }

    public function referee()
    {
        return $this->belongsTo(Referee::class, 'referee_id');
    }

    public function payments()
    {
        return $this->hasMany(MatchPayment::class, 'match_id');
    }

    public function paymentForTeam(int $teamId): ?MatchPayment
    {
        return $this->payments->firstWhere('team_id', $teamId);
    }

    public function allTeamsPaid(): bool
    {
        return $this->payments()
            ->whereIn('team_id', [$this->home_team_id, $this->away_team_id])
            ->where('status', 'paid')
            ->count() === 2;
    }
}
