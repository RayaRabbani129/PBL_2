<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'team_id',
        'role',
        'status'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}