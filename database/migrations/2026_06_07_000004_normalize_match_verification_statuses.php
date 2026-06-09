<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE match_verifications
            MODIFY status ENUM('valid', 'cheating', 'pending', 'verified', 'rejected') DEFAULT 'pending'
        ");

        DB::table('match_verifications')
            ->where('status', 'valid')
            ->update(['status' => 'verified']);

        DB::table('match_verifications')
            ->where('status', 'cheating')
            ->update(['status' => 'rejected']);

        DB::statement("
            ALTER TABLE match_verifications
            MODIFY status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE match_verifications
            MODIFY status ENUM('valid', 'cheating', 'pending', 'verified', 'rejected') DEFAULT 'pending'
        ");

        DB::table('match_verifications')
            ->where('status', 'verified')
            ->update(['status' => 'valid']);

        DB::table('match_verifications')
            ->whereIn('status', ['pending', 'rejected'])
            ->update(['status' => 'cheating']);

        DB::statement("
            ALTER TABLE match_verifications
            MODIFY status ENUM('valid', 'cheating') DEFAULT 'valid'
        ");
    }
};
