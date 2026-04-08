<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueSchedule extends Model
{
    protected $table = 'venue_schedules';

    protected $fillable = [
        'venue_id',
        'date',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'date'        => 'date',
        'start_time'  => 'datetime:H:i',
        'end_time'    => 'datetime:H:i',
        'is_available'=> 'boolean',
    ];

    /**
     * ========================
     * RELATIONSHIP
     * ========================
     */

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * ========================
     * HELPER METHODS
     * ========================
     */

    // Cek apakah slot tersedia
    public function isAvailable()
    {
        return $this->is_available;
    }

    // Cek apakah waktu overlap (dipakai untuk validasi booking)
    public function isTimeOverlap($start, $end)
    {
        return $this->start_time < $end && $this->end_time > $start;
    }

    // Cek apakah jadwal cocok dengan match
    public function isMatchTime($date, $start, $end)
    {
        return $this->date == $date &&
               $this->is_available &&
               $this->isTimeOverlap($start, $end);
    }

    /**
     * ========================
     * QUERY SCOPES
     * ========================
     */

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeByVenue($query, $venueId)
    {
        return $query->where('venue_id', $venueId);
    }
}