<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE match_payments
            MODIFY status ENUM(
                'pending',
                'paid',
                'expired',
                'failed',
                'cancelled',
                'rejected',
                'refund_pending',
                'refunded'
            ) DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::table('match_payments')
            ->whereIn('status', ['refund_pending', 'refunded'])
            ->update(['status' => 'cancelled']);

        DB::statement("
            ALTER TABLE match_payments
            MODIFY status ENUM(
                'pending',
                'paid',
                'expired',
                'failed',
                'cancelled',
                'rejected'
            ) DEFAULT 'pending'
        ");
    }
};
