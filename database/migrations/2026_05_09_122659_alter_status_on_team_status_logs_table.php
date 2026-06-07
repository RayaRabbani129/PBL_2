<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE team_status_logs
            MODIFY status ENUM(
                'fair_play',
                'warning',
                'under_review',
                'suspended',
                'banned',
                'cheating',
                'toxic_behavior',
                'match_fixing',
                'violence',
                'fake_player'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE team_status_logs
            MODIFY status ENUM(
                'active',
                'suspended'
            ) NOT NULL
        ");
    }
};