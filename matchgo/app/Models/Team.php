<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'city',
        'province',
        'latitude',
        'longitude',
        'level',
        'description',
        'logo_path',
        'status',
        'warning_points',
        'banned_at',
    ];

    protected $casts = [
        'banned_at' => 'datetime',
        'warning_points' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function schedules()
    {
        return $this->hasMany(TeamSchedule::class);
    }

    public function stats()
    {
        return $this->hasOne(TeamStat::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(TeamStatusLog::class);
    }

    public function matchRequests()
    {
        return $this->hasMany(MatchRequest::class);
    }

    public function homeMatches()
    {
        return $this->hasMany(Matches::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Matches::class, 'away_team_id');
    }
}