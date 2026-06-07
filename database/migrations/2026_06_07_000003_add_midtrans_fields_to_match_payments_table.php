<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_payments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('team_id')->constrained('users')->nullOnDelete();
            $table->string('order_id')->nullable()->unique()->after('user_id');
            $table->string('snap_token')->nullable()->after('invoice_id');
            $table->string('payment_type')->nullable()->after('gateway_status');
            $table->string('transaction_id')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('match_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'order_id',
                'snap_token',
                'payment_type',
                'transaction_id',
            ]);
        });
    }
};
