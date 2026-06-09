<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referee_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('referee_id')->constrained('referees')->onDelete('restrict');
            $table->date('rental_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('hourly_rate', 10, 2);
            $table->float('total_hours')->default(0);
            $table->decimal('rental_cost', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['match_id', 'referee_id']);
            $table->index('status');
            $table->index('rental_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referee_rentals');
    }
};
