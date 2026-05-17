<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send(
        int    $userId,
        string $type,
        string $title,
        string $message
    ): Notification {
        
        return Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'status'  => 'unread',
        ]);
    }

    public static function matchChallenge(int $userId, string $teamName): void
    {
        self::send(
            $userId,
            'match_challenge',
            'Permintaan Tanding Baru',
            "{$teamName} menantang tim kamu untuk bertanding."
        );
    }

    public static function matchAccepted(int $userId, string $teamName): void
    {
        self::send(
            $userId,
            'challenge_accepted',
            'Challenge Diterima',
            "{$teamName} menerima challenge pertandingan."
        );
    }

    public static function reminder(int $userId, string $message): void
    {
        self::send(
            $userId,
            'match',
            'Reminder Jadwal',
            $message
        );
    }
}