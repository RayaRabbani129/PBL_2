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
        Schema::create('referees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->integer('experience_years')->default(0);
            $table->enum('certification_level', ['basic', 'intermediate', 'advanced', 'professional'])->default('basic');
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->float('rating')->default(0);
            $table->integer('total_matches_refereed')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('is_available');
            $table->index('city');
            $table->index('certification_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referees');
    }
};
