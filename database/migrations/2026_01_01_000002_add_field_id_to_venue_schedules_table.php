<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom field_id ke venue_schedules,
     * agar jadwal bisa diassign ke lapangan tertentu (bukan hanya venue).
     */
    public function up(): void
    {
        Schema::table('venue_schedules', function (Blueprint $table) {
            $table->foreignId('field_id')
                  ->nullable()
                  ->after('venue_id')
                  ->constrained('fields')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('venue_schedules', function (Blueprint $table) {
            $table->dropForeign(['field_id']);
            $table->dropColumn('field_id');
        });
    }
};
