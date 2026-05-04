<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = [
        'created_by',
        'name',
        'address',
        'city',
        'province',
        'latitude',
        'longitude',
        'price_per_hour',
        'capacity',
        'phone',
        'description',
        'photo_path',
        'status',
        'is_available',
    ];

    protected $casts = [
        'is_available'   => 'boolean',
        'price_per_hour' => 'decimal:2',
        'latitude'       => 'decimal:7',
        'longitude'      => 'decimal:7',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(VenueSchedule::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(Matches::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function fieldAdmins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'field_admin_venues');
    }
}