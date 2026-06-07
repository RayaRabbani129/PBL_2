<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamStatusLog extends Model
{
    protected $fillable = [
        'team_id',
        'status',
        'reason',
        'updated_by'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}