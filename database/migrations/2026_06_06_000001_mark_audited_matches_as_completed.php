<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('matches')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('match_audits')
                    ->whereColumn('match_audits.match_id', 'matches.id')
                    ->whereNotNull('match_audits.audited_at');
            })
            ->update(['status' => 'completed']);
    }

    public function down(): void
    {
        //
    }
};
