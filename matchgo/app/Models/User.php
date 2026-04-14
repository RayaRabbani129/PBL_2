<?php

namespace App\Models;

use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    protected $guard_name = 'web';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'created_by');
    }

    public function managedVenues()
    {
        return $this->belongsToMany(Venue::class, 'field_admin_venues');
    }

    public function verifications()
    {
        return $this->hasMany(MatchVerification::class, 'verified_by');
    }
}