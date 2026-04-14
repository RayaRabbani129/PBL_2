<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'is_available'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(VenueSchedule::class);
    }

    public function matches()
    {
        return $this->hasMany(Matches::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function fieldAdmins()
    {
        return $this->belongsToMany(User::class, 'field_admin_venues');
    }
}