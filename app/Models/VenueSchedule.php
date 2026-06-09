<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenueSchedule extends Model
{
    protected $fillable = [
        'venue_id',
        'field_id',
        'date',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'date'         => 'date',
        'is_available' => 'boolean',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}