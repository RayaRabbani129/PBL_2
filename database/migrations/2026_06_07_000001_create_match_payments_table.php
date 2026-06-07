<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE matches
            MODIFY status ENUM(
                'pending',
                'matched',
                'accepted',
                'confirmed',
                'scheduled',
                'awaiting_payment',
                'ongoing',
                'completed',
                'cancelled'
            ) DEFAULT 'pending'
        ");

        Schema::create('match_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('method', ['qris', 'bank_transfer', 'shopeepay'])->nullable();
            $table->string('payer_name')->nullable();
            $table->string('proof_path')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'rejected'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['match_id', 'team_id']);
            $table->index(['match_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_payments');

        DB::statement("
            ALTER TABLE matches
            MODIFY status ENUM(
                'pending',
                'confirmed',
                'ongoing',
                'completed',
                'cancelled'
            ) DEFAULT 'pending'
        ");
    }
};
