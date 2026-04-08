<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamSchedule extends Model
{
    protected $table = 'team_schedules';

    protected $fillable = [
        'team_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'start_time'  => 'datetime:H:i',
        'end_time'    => 'datetime:H:i',
        'is_available'=> 'boolean',
    ];

    /**
     * ========================
     * RELATIONSHIP
     * ========================
     */

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * ========================
     * CONSTANT DAY (BEST PRACTICE)
     * ========================
     */

    const SUNDAY    = 0;
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;

    /**
     * ========================
     * HELPER METHODS
     * ========================
     */

    // Cek apakah jadwal aktif
    public function isAvailable()
    {
        return $this->is_available;
    }

    // Cek apakah waktu cocok (overlap)
    public function isTimeOverlap($start, $end)
    {
        return $this->start_time < $end && $this->end_time > $start;
    }

    // Format hari (biar readable)
    public function getDayNameAttribute()
    {
        return match ($this->day_of_week) {
            self::SUNDAY    => 'Minggu',
            self::MONDAY    => 'Senin',
            self::TUESDAY   => 'Selasa',
            self::WEDNESDAY => 'Rabu',
            self::THURSDAY  => 'Kamis',
            self::FRIDAY    => 'Jumat',
            self::SATURDAY  => 'Sabtu',
            default         => 'Tidak diketahui',
        };
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

    public function scopeByDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }
}