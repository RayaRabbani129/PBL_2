<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefereeRental extends Model
{
    protected $fillable = [
        'match_id',
        'referee_id',
        'rental_date',
        'start_time',
        'end_time',
        'hourly_rate',
        'total_hours',
        'rental_cost',
        'status',
        'notes',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'total_hours' => 'float',
        'rental_cost' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function match()
    {
        return $this->belongsTo(Matches::class);
    }

    public function referee()
    {
        return $this->belongsTo(Referee::class);
    }

    public function calculateCost()
    {
        $startTime = \Carbon\Carbon::parse($this->start_time);
        $endTime = \Carbon\Carbon::parse($this->end_time);
        $hours = $startTime->diffInMinutes($endTime) / 60;
        
        $this->total_hours = $hours;
        $this->rental_cost = $hours * $this->hourly_rate;
        
        return $this->rental_cost;
    }
}
