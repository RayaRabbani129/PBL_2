<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: team_schedules
     * Menyimpan jadwal ketersediaan bermain rutin per tim.
     * day_of_week mengikuti standar PHP: 0 = Minggu, 1 = Senin, ..., 6 = Sabtu.
     * Sistem matchmaking menggunakan data ini untuk mencocokkan
     * tim yang punya jadwal ketersediaan yang sama.
     */
    public function up(): void
    {
        Schema::create('team_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('team_id')
                  ->constrained('teams')
                  ->cascadeOnDelete();

            // 0=Minggu, 1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu
            $table->tinyInteger('day_of_week')->unsigned();

            $table->time('start_time');
            $table->time('end_time');

            $table->boolean('is_available')->default(true);

            $table->timestamps();

            // Satu tim tidak boleh punya dua jadwal berbeda di hari dan waktu yang sama
            $table->unique(['team_id', 'day_of_week', 'start_time']);

            $table->index(['team_id', 'day_of_week', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_schedules');
    }
};