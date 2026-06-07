<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRingtone extends Model
{
    protected $fillable = [
        'category',
        'name',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}