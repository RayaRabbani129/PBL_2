<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: teams
     * Menyimpan profil tim futsal beserta statistik dan lokasi.
     * Koordinat latitude & longitude digunakan untuk fitur Auto Venue
     * (menghitung titik tengah antara dua tim yang di-match).
     * Statistik diperbarui otomatis setiap pertandingan selesai.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();

            // Kapten / pemilik tim
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('name');
            $table->string('city', 100);
            $table->string('province', 100);

            // Koordinat lokasi markas tim untuk fitur Auto Venue
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            // Level permainan untuk sistem matchmaking
            $table->enum('level', ['casual', 'semi_pro', 'competitive'])
                  ->default('casual');

            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Index untuk mempercepat proses matchmaking
            $table->index(['level', 'status']);
            $table->index(['city', 'level', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};