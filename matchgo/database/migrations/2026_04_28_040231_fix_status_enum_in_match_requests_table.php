<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE match_requests 
            MODIFY COLUMN status ENUM('searching','matched','rejected','cancelled') 
            NOT NULL DEFAULT 'searching'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE match_requests 
            MODIFY COLUMN status ENUM('searching','matched','cancelled') 
            NOT NULL DEFAULT 'searching'");
    }
};
