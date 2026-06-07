<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: team_members
     * Tabel pivot many-to-many antara users dan teams.
     * Menyimpan anggota tiap tim beserta role-nya di dalam tim.
     * Jumlah anggota aktif digunakan oleh fitur Smart Cost Split
     * untuk menghitung biaya per pemain.
     */
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();

            $table->foreignId('team_id')
                  ->constrained('teams')
                  ->cascadeOnDelete();

            // captain = kapten/pemilik tim, player = anggota biasa
            $table->enum('role', ['captain', 'player'])->default('player');

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            // Satu user hanya bisa satu kali terdaftar di satu tim
            $table->unique(['team_id']);

            $table->index(['team_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};