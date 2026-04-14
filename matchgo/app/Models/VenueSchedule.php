<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueSchedule extends Model
{
    protected $fillable = [
        'venue_id',
        'date',
        'start_time',
        'end_time',
        'is_available'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}