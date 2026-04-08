<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table = 'team_members';

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * ========================
     * RELATIONSHIPS
     * ========================
     */

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ========================
     * CONSTANTS
     * ========================
     */

    const ROLE_CAPTAIN = 'captain';
    const ROLE_PLAYER  = 'player';

    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * ========================
     * HELPER METHODS
     * ========================
     */

    public function isCaptain()
    {
        return $this->role === self::ROLE_CAPTAIN;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
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

    public function scopeCaptain($query)
    {
        return $query->where('role', self::ROLE_CAPTAIN);
    }
}