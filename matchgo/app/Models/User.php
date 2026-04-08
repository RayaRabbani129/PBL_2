<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ========================
     * CONSTANTS
     * ========================
     */

    const ROLE_PLAYER = 'player';
    const ROLE_ADMIN  = 'admin';

    /**
     * ========================
     * RELATIONSHIPS
     * ========================
     */

    // User punya banyak team (via pivot)
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members')
                    ->withPivot(['role', 'status', 'joined_at'])
                    ->withTimestamps();
    }

    // User sebagai owner/captain tim
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'user_id');
    }

    // Relasi ke team_members (detail pivot)
    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }

    // Venue yang dibuat (khusus admin)
    public function venues()
    {
        return $this->hasMany(Venue::class, 'created_by');
    }

    /**
     * ========================
     * HELPER METHODS
     * ========================
     */

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPlayer()
    {
        return $this->role === self::ROLE_PLAYER;
    }

    // Cek apakah user adalah captain di tim tertentu
    public function isCaptainOf($teamId)
    {
        return $this->teamMemberships()
                    ->where('team_id', $teamId)
                    ->where('role', TeamMember::ROLE_CAPTAIN)
                    ->exists();
    }
}