<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: venues
     * Menyimpan data lapangan futsal yang dikelola oleh admin.
     * Koordinat latitude & longitude digunakan untuk fitur Auto Venue
     * (perhitungan titik tengah antara dua tim).
     */
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();

            // Admin yang menambahkan lapangan
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->string('name');
            $table->text('address');
            $table->string('city', 100);
            $table->string('province', 100);

            // Koordinat untuk perhitungan Auto Venue
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            // Harga per jam dalam Rupiah
            $table->decimal('price_per_hour', 10, 2);

            // Kapasitas pemain maksimal
            $table->unsignedSmallInteger('capacity')->default(14);

            $table->string('phone', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};