<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{

    public function up(): void
    {
        DB::statement("
            ALTER TABLE team_members 
            MODIFY role ENUM(
                'captain',
                'player',
                'goalkeeper',
                'defender',
                'midfielder',
                'striker'
            ) NOT NULL DEFAULT 'player'
        ");
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->enum('role', ['captain', 'player'])
                  ->default('player')
                  ->change();
        });
    }
};
