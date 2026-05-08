<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Relasi ke user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah notifikasi sudah dibaca
     */
    public function getIsReadAttribute(): bool
    {
        return $this->status === 'read';
    }

    /**
     * Cek apakah belum dibaca
     */
    public function getIsUnreadAttribute(): bool
    {
        return $this->status === 'unread';
    }
}