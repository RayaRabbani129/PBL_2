<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldSchedule extends Model
{
    protected $fillable = [
        'field_id',
        'date',
        'start_time',
        'end_time',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'date'         => 'date',
        'is_available' => 'boolean',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}