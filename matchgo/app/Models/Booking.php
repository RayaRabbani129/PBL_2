<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'match_id',
        'venue_id',
        'field_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'created_by'
    ];

    public function match()
    {
        return $this->belongsTo(Matches::class, 'match_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'created_by');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}