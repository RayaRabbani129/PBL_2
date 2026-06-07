<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: matches
     * Menyimpan data pertandingan hasil matchmaking.
     * Satu pertandingan melibatkan dua tim (home & away) dan satu venue.
     *
     * Status alur: pending -> confirmed -> ongoing -> completed / cancelled
     *
     * home_score & away_score diisi setelah pertandingan selesai
     * untuk memperbarui statistik kedua tim.
     */
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();

            // Kode unik pertandingan (contoh: MTG-20250101-XXXX)
            $table->string('match_code', 20)->unique();

            // Tim tuan rumah (hasil matchmaking, bukan berarti lokasi)
            $table->foreignId('home_team_id')
                  ->constrained('teams')
                  ->restrictOnDelete();

            // Tim tamu
            $table->foreignId('away_team_id')
                  ->constrained('teams')
                  ->restrictOnDelete();

            // Lapangan hasil Auto Venue
            $table->foreignId('venue_id')->nullable()
                  ->constrained('venues')
                  ->restrictOnDelete();

            $table->dateTime('match_datetime');
            $table->unsignedSmallInteger('duration_minutes')->default(60);

            // Skor diisi setelah pertandingan selesai (nullable)
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();

            /**
             * Status alur pertandingan:
             * - pending    : menunggu konfirmasi kedua tim
             * - confirmed  : kedua tim sudah konfirmasi
             * - ongoing    : pertandingan sedang berlangsung
             * - completed  : pertandingan selesai, skor sudah diinput
             * - cancelled  : pertandingan dibatalkan
             */
            $table->enum('status', [
                'pending',
                'confirmed',
                'ongoing',
                'completed',
                'cancelled',
            ])->default('pending');

            // Total biaya sewa lapangan (cache dari match_costs)
            $table->decimal('total_cost', 10, 2)->default(0);

            // Catatan atau alasan pembatalan
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['home_team_id', 'status']);
            $table->index(['away_team_id', 'status']);
            $table->index(['venue_id', 'match_datetime']);
            $table->index(['status', 'match_datetime']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};