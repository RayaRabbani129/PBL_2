<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $table = 'venues';

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
    ];

    protected $casts = [
        'latitude'        => 'decimal:7',
        'longitude'       => 'decimal:7',
        'price_per_hour'  => 'decimal:2',
        'capacity'        => 'integer',
    ];

    /**
     * ========================
     * RELATIONSHIPS
     * ========================
     */

    // Admin pembuat venue
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Jadwal venue
    public function schedules()
    {
        return $this->hasMany(VenueSchedule::class);
    }

    // Match yang menggunakan venue ini
    public function matches()
    {
        return $this->hasMany(Matches::class);
    }

    /**
     * ========================
     * CONSTANTS
     * ========================
     */

    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * ========================
     * HELPER METHODS
     * ========================
     */

    // Cek apakah venue aktif
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Hitung jarak ke titik tertentu (lat, long)
    public function distanceTo($latitude, $longitude)
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo   = deg2rad($latitude);
        $lonTo   = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // km
    }

    // Hitung estimasi biaya berdasarkan durasi
    public function calculateCost($durationMinutes)
    {
        return $this->price_per_hour * ($durationMinutes / 60);
    }

    /**
     * ========================
     * QUERY SCOPES
     * ========================
     */

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }
}