<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send(
        int $userId,
        string $type,
        string $message
    ): void {

        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'message' => $message,
            'status'  => 'unread',
        ]);
    }

    public static function matchChallenge(
        int $userId,
        string $teamName
    ): void {

        self::send(
            $userId,
            'match_challenge',
            "{$teamName} menantang tim kamu untuk bertanding."
        );
    }

    public static function matchAccepted(
        int $userId,
        string $teamName
    ): void {

        self::send(
            $userId,
            'match_accepted',
            "{$teamName} menerima challenge pertandingan."
        );
    }

    public static function reminder(
        int $userId,
        string $message
    ): void {

        self::send(
            $userId,
            'reminder',
            $message
        );
    }
}