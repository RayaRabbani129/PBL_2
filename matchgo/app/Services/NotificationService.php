<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Kirim notifikasi umum
     */
    public static function send(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'data'    => $data,
            'status'  => 'unread',
        ]);
    }

    /**
     * Notifikasi permintaan tanding baru
     */
    public static function matchChallenge(
        int $userId,
        string $teamName,
        ?int $matchRequestId = null
    ): Notification {
        return self::send(
            $userId,
            'match_challenge',
            'Permintaan Tanding Baru',
            "{$teamName} menantang tim kamu untuk bertanding.",
            [
                'team_name'        => $teamName,
                'match_request_id' => $matchRequestId,
            ]
        );
    }

    /**
     * Notifikasi tantangan diterima
     */
    public static function matchAccepted(
        int $userId,
        string $teamName
    ): Notification {
        return self::send(
            $userId,
            'match_confirmed',
            'Tantangan Diterima',
            "{$teamName} menerima tantangan pertandingan.",
            [
                'team_name' => $teamName,
            ]
        );
    }

    /**
     * Notifikasi tantangan ditolak
     */
    public static function matchRejected(
        int $userId,
        string $teamName
    ): Notification {
        return self::send(
            $userId,
            'match_rejected',
            'Tantangan Ditolak',
            "{$teamName} menolak tantangan pertandingan.",
            [
                'team_name' => $teamName,
            ]
        );
    }

    /**
     * Notifikasi reminder jadwal
     */
    public static function reminder(
        int $userId,
        string $message
    ): Notification {
        return self::send(
            $userId,
            'match_reminder',
            'Reminder Jadwal',
            $message
        );
    }

    /**
     * Notifikasi hasil pertandingan
     */
    public static function matchResult(
        int $userId,
        string $message
    ): Notification {
        return self::send(
            $userId,
            'match_result',
            'Hasil Pertandingan',
            $message
        );
    }

    /**
     * Notifikasi sistem umum
     */
    public static function system(
        int $userId,
        string $title,
        string $message
    ): Notification {
        return self::send(
            $userId,
            'system',
            $title,
            $message
        );
    }
}