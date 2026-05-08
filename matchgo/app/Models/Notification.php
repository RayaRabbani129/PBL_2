<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'data' => 'array',
    ];

    // Helper untuk cek sudah dibaca
    public function getIsReadAttribute(): bool
    {
        return $this->status === 'read';
    }

}