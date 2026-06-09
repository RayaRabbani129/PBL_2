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
     * Cek apakah notifikasi belum dibaca
     */
    public function getIsUnreadAttribute(): bool
    {
        return $this->status === 'unread';
    }

    /**
     * Ambil title aman supaya tidak null
     */
    public function getDisplayTitleAttribute(): string
    {
        return $this->title
            ?: ($this->data['title'] ?? 'Notifikasi');
    }

    /**
     * Ambil message aman supaya tidak null
     */
    public function getDisplayMessageAttribute(): string
    {
        return $this->message
            ?: ($this->data['message'] ?? $this->data['body'] ?? 'Tidak ada pesan.');
    }

    /**
     * Ambil type aman
     */
    public function getDisplayTypeAttribute(): string
    {
        return $this->type
            ?: ($this->data['type'] ?? 'system');
    }
}