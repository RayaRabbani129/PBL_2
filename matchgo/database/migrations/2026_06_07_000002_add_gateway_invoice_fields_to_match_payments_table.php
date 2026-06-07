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
            ALTER TABLE match_payments
            MODIFY status ENUM('unpaid', 'pending', 'paid', 'expired', 'failed', 'cancelled', 'rejected') DEFAULT 'pending'
        ");

        DB::table('match_payments')
            ->where('status', 'unpaid')
            ->update(['status' => 'pending']);

        DB::statement("
            ALTER TABLE match_payments
            MODIFY status ENUM('pending', 'paid', 'expired', 'failed', 'cancelled', 'rejected') DEFAULT 'pending'
        ");

        Schema::table('match_payments', function (Blueprint $table) {
            $table->string('gateway')->default('generic')->after('amount');
            $table->string('invoice_id')->nullable()->unique()->after('gateway');
            $table->string('payment_url')->nullable()->after('invoice_id');
            $table->timestamp('expired_at')->nullable()->after('payment_url');
            $table->string('gateway_status')->nullable()->after('status');
            $table->json('raw_payload')->nullable()->after('gateway_status');
        });
    }

    public function down(): void
    {
        Schema::table('match_payments', function (Blueprint $table) {
            $table->dropColumn([
                'gateway',
                'invoice_id',
                'payment_url',
                'expired_at',
                'gateway_status',
                'raw_payload',
            ]);
        });

        DB::statement("
            ALTER TABLE match_payments
            MODIFY status ENUM('unpaid', 'paid', 'rejected') DEFAULT 'unpaid'
        ");
    }
};
