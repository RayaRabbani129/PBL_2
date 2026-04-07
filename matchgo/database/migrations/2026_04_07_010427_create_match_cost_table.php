<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: match_costs
     * Menyimpan rincian perhitungan biaya Smart Cost Split.
     * Setiap pertandingan memiliki tepat satu record di tabel ini.
     *
     * Formula perhitungan:
     *   total_venue_cost     = venue.price_per_hour * (duration_minutes / 60)
     *   home_team_cost       = total_venue_cost / 2
     *   away_team_cost       = total_venue_cost / 2
     *   home_cost_per_player = home_team_cost / home_team_players
     *   away_cost_per_player = away_team_cost / away_team_players
     */
    public function up(): void
    {
        Schema::create('match_costs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('match_id')
                  ->unique()  // One-to-one dengan matches
                  ->constrained('matches')
                  ->cascadeOnDelete();

            // Total biaya sewa lapangan (dari durasi * harga/jam)
            $table->decimal('total_venue_cost', 10, 2);

            // Biaya yang ditanggung masing-masing tim (50:50)
            $table->decimal('home_team_cost', 10, 2);
            $table->decimal('away_team_cost', 10, 2);

            // Jumlah pemain aktif saat pertandingan (untuk bagi biaya per orang)
            $table->unsignedTinyInteger('home_team_players')->default(0);
            $table->unsignedTinyInteger('away_team_players')->default(0);

            // Biaya per pemain masing-masing tim
            $table->decimal('home_cost_per_player', 10, 2)->default(0);
            $table->decimal('away_cost_per_player', 10, 2)->default(0);

            // True jika perhitungan sudah final dan tidak akan berubah
            $table->boolean('is_finalized')->default(false);

            // Catatan tambahan (misalnya diskon, biaya tambahan, dll.)
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_costs');
    }
};