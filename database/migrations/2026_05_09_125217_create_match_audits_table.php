<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_audits', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('match_id')
                ->constrained('matches')
                ->cascadeOnDelete();

            $table->foreignId('auditor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | TEAM REVIEW
            |--------------------------------------------------------------------------
            */

            $table->enum('home_team_review', [
                'fair_play',
                'warning',
                'under_review',
                'toxic_behavior',
                'fake_player',
                'violence',
                'cheating',
                'match_fixing',
            ])->default('fair_play');

            $table->enum('away_team_review', [
                'fair_play',
                'warning',
                'under_review',
                'toxic_behavior',
                'fake_player',
                'violence',
                'cheating',
                'match_fixing',
            ])->default('fair_play');

            /*
            |--------------------------------------------------------------------------
            | AUDIT DATA
            |--------------------------------------------------------------------------
            */

            $table->tinyInteger('sportsmanship_rating')
                ->nullable();

            $table->text('audit_notes')
                ->nullable();

            $table->longText('game_review')
                ->nullable();

            $table->timestamp('audited_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_audits');
    }
};