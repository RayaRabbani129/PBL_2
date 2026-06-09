<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: fields
     * Menyimpan data lapangan (field) yang ada di dalam sebuah venue.
     * Satu venue bisa memiliki lebih dari satu lapangan.
     */
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venue_id')
                  ->constrained('venues')
                  ->cascadeOnDelete();

            $table->string('name');                          // Contoh: "Lapangan A", "Lapangan 1"
            $table->string('type', 50)->default('futsal');   // futsal, badminton, dll
            $table->unsignedSmallInteger('capacity')->default(14);
            $table->decimal('price_per_hour', 10, 2);
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
