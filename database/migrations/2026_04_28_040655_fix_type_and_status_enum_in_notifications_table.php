<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notifications 
            MODIFY COLUMN type ENUM(
                'booking',
                'match',
                'verification',
                'match_confirmed',
                'match_challenge',
                'challenge_accepted',
                'challenge_rejected',
                'challenge_cancelled'
            ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notifications 
            MODIFY COLUMN type ENUM('booking','match','verification') NOT NULL");
    }
};