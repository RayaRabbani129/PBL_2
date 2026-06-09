<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus unique constraint pada team_id
     */
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            // Nama index biasanya: team_members_team_id_unique
            $table->dropUnique(['team_id']);
        });
    }

    /**
     * Kembalikan unique constraint (rollback)
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->unique(['team_id']);
        });
    }
};