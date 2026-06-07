<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: venue_schedules
     * Menyimpan jadwal ketersediaan lapangan per tanggal dan slot waktu.
     * Digunakan sistem untuk memeriksa apakah lapangan tersedia
     * pada jadwal pertandingan yang dihasilkan matchmaking.
     */
    public function up(): void
    {
        Schema::create('venue_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venue_id')
                  ->constrained('venues')
                  ->cascadeOnDelete();

            // Tanggal spesifik ketersediaan lapangan
            $table->date('date');

            $table->time('start_time');
            $table->time('end_time');

            // False jika slot sudah dipesan atau tutup
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            // Satu venue tidak boleh punya slot waktu yang tumpang tindih pada hari yang sama
            $table->index(['venue_id', 'date', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_schedules');
    }
};