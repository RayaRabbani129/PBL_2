<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_ringtones', function (Blueprint $table) {
            $table->id();

            $table->enum('category', [
                'booking',
                'match',
                'verification',
                'match_confirmed',
                'match_challenge',
                'challenge_accepted',
                'challenge_rejected',
                'challenge_cancelled',
            ])->unique();

            $table->string('name');
            $table->string('file_path');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_ringtones');
    }
};