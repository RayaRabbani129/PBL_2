<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'user_id',
        'name',
        'city',
        'province',
        'latitude',
        'longitude',
        'level',
        'total_matches',
        'total_wins',
        'total_losses',
        'total_draws',
        'total_goals_scored',
        'total_goals_conceded',
        'description',
        'logo_path',
        'status',
    ];

    protected $casts = [
        'latitude'               => 'decimal:7',
        'longitude'              => 'decimal:7',
        'total_matches'          => 'integer',
        'total_wins'             => 'integer',
        'total_losses'           => 'integer',
        'total_draws'            => 'integer',
        'total_goals_scored'     => 'integer',
        'total_goals_conceded'   => 'integer',
    ];

    /**
     * ========================
     * RELATIONSHIPS
     * ========================
     */

    // Kapten / owner tim
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Semua member tim
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    // Relasi many-to-many ke user
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->withPivot(['role', 'status', 'joined_at'])
                    ->withTimestamps();
    }

    // Jadwal tim
    public function schedules()
    {
        return $this->hasMany(TeamSchedule::class);
    }

    // Match sebagai home
    public function homeMatches()
    {
        return $this->hasMany(Matches::class, 'home_team_id');
    }

    // Match sebagai away
    public function awayMatches()
    {
        return $this->hasMany(Matches::class, 'away_team_id');
    }

    /**
     * ========================
     * CONSTANTS
     * ========================
     */

    const LEVEL_CASUAL      = 'casual';
    const LEVEL_SEMI_PRO    = 'semi_pro';
    const LEVEL_COMPETITIVE = 'competitive';

    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * ========================
     * HELPER METHODS
     * ========================
     */

    // Hitung jumlah pemain aktif
    public function activePlayersCount()
    {
        return $this->members()
                    ->where('status', TeamMember::STATUS_ACTIVE)
                    ->count();
    }

    // Goal difference
    public function goalDifference()
    {
        return $this->total_goals_scored - $this->total_goals_conceded;
    }

    // Win rate (%)
    public function winRate()
    {
        if ($this->total_matches == 0) {
            return 0;
        }

        return ($this->total_wins / $this->total_matches) * 100;
    }

    // Cek apakah tim aktif
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * ========================
     * MATCHMAKING HELPERS
     * ========================
     */

    // Cek apakah level sama
    public function isSameLevel(Team $team)
    {
        return $this->level === $team->level;
    }

    // Hitung jarak (Haversine sederhana)
    public function distanceTo(Team $team)
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo   = deg2rad($team->latitude);
        $lonTo   = deg2rad($team->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // km
    }

    /**
     * ========================
     * QUERY SCOPES
     * ========================
     */

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeSameCity($query, $city)
    {
        return $query->where('city', $city);
    }
}