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
        Schema::create('match_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->integer('score_team_a');
            $table->integer('score_team_b');
            $table->enum('status', ['valid','cheating']);
            $table->text('notes')->nullable();
            $table->foreignId('verified_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_verifications');
    }
};
