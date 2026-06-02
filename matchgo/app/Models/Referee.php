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

    public function getAvailableReferees($date, $startTime, $endTime)
    {
        return $this->where('is_available', true)
            ->whereDoesntHave('rentals', function ($query) use ($date, $startTime, $endTime) {
                $query->where('rental_date', $date)
                    ->where(function ($q) use ($startTime, $endTime) {
                        $q->whereBetween('start_time', [$startTime, $endTime])
                            ->orWhereBetween('end_time', [$startTime, $endTime])
                            ->orWhere(function ($q2) use ($startTime, $endTime) {
                                $q2->where('start_time', '<=', $startTime)
                                    ->where('end_time', '>=', $endTime);
                            });
                    });
            })
            ->orderByDesc('rating')
            ->get();
    }
}
