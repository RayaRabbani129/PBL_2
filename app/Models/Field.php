<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    protected $fillable = [
        'venue_id',
        'name',
        'type',
        'capacity',
        'price_per_hour',
        'description',
        'photo_path',
        'status',
        'is_available',
    ];

    protected $casts = [
        'is_available'   => 'boolean',
        'price_per_hour' => 'decimal:2',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(VenueSchedule::class);
    }
}
