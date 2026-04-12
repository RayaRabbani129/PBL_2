<?php

namespace App\Models;

use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
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
    const ROLE_ADMIN_LAPANGAN  = 'admin_lapangan';

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

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_ADMIN_LAPANGAN]);
    }

    public function isAdminUtama(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAdminLapangan(): bool
    {
        return $this->role === self::ROLE_ADMIN_LAPANGAN;
    }

    public function isPlayer(): bool
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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
}