<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'experience_years',
        'certification_level',
        'hourly_rate',
        'is_available',
        'city',
        'latitude',
        'longitude',
        'rating',
        'total_matches_refereed',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'experience_years' => 'integer',
        'hourly_rate' => 'decimal:2',
        'rating' => 'decimal:1',
        'total_matches_refereed' => 'integer',
    ];

    public function setRatingAttribute($value)
    {
        $rating = is_numeric($value) ? (float) $value : 0.0;
        $rating = min(5.0, max(0.0, round($rating, 1)));
        $this->attributes['rating'] = $rating;
    }

    public function rentals()
    {
        return $this->hasMany(RefereeRental::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeFreeBetween($query, string $date, string $startTime, string $endTime)
    {
        return $query->whereDoesntHave('rentals', function ($query) use ($date, $startTime, $endTime) {
            $query->where('rental_date', $date)
                ->where('status', '!=', 'cancelled')
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime);
        });
    }

    public static function availableFor(string $date, string $startTime, string $endTime)
    {
        return static::query()
            ->available()
            ->freeBetween($date, $startTime, $endTime);
    }

    public function getAvailableReferees($date, $startTime, $endTime)
    {
        return static::availableFor($date, $startTime, $endTime)
            ->orderByDesc('rating')
            ->orderByDesc('experience_years')
            ->orderBy('hourly_rate')
            ->get();
    }
}
