<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan field_id ke tabel matches dan bookings.
     *
     * Kenapa perlu?
     * - 1 venue bisa punya banyak lapangan
     * - match harus tahu bermain di lapangan mana
     * - booking juga harus spesifik ke field
     */
    public function up(): void
    {
        // ─────────────────────────────────────────────
        // MATCHES
        // ─────────────────────────────────────────────
        Schema::table('matches', function (Blueprint $table) {

            // field yang dipakai untuk match
            $table->foreignId('field_id')
                ->nullable()
                ->after('venue_id')
                ->constrained('fields')
                ->nullOnDelete();

        });

        // ─────────────────────────────────────────────
        // BOOKINGS
        // ─────────────────────────────────────────────
        Schema::table('bookings', function (Blueprint $table) {

            // field yang dibooking
            $table->foreignId('field_id')
                ->nullable()
                ->after('venue_id')
                ->constrained('fields')
                ->nullOnDelete();

        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        // ─────────────────────────────────────────────
        // MATCHES
        // ─────────────────────────────────────────────
        Schema::table('matches', function (Blueprint $table) {

            $table->dropForeign(['field_id']);
            $table->dropColumn('field_id');

        });

        // ─────────────────────────────────────────────
        // BOOKINGS
        // ─────────────────────────────────────────────
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropForeign(['field_id']);
            $table->dropColumn('field_id');

        });
    }
};