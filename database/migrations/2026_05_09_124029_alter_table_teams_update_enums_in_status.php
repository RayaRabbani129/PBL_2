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
        /**
         * =========================================================
         * UPDATE ENUM STATUS TEAM
         * =========================================================
         */

        DB::statement("
            ALTER TABLE teams
            MODIFY status ENUM(
                'active',
                'warning',
                'under_review',
                'suspended',
                'banned',
                'cheating',
                'toxic_behavior',
                'match_fixing',
                'violence',
                'fake_player'
            ) NOT NULL DEFAULT 'active'
        ");

        /**
         * =========================================================
         * ADD WARNING POINTS
         * =========================================================
         */

        Schema::table('teams', function (Blueprint $table) {

            if (! Schema::hasColumn('teams', 'warning_points')) {
                $table->integer('warning_points')
                    ->default(0)
                    ->after('status');
            }

            if (! Schema::hasColumn('teams', 'banned_at')) {
                $table->timestamp('banned_at')
                    ->nullable()
                    ->after('warning_points');
            }

        });

        /**
         * =========================================================
         * UPDATE ENUM TEAM STATUS LOGS
         * =========================================================
         */

        DB::statement("
            ALTER TABLE team_status_logs
            MODIFY status ENUM(
                'fair_play',
                'warning',
                'under_review',
                'suspended',
                'banned',
                'cheating',
                'toxic_behavior',
                'match_fixing',
                'violence',
                'fake_player'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /**
         * =========================================================
         * REVERT TEAMS STATUS
         * =========================================================
         */

        DB::statement("
            ALTER TABLE teams
            MODIFY status ENUM(
                'active',
                'inactive'
            ) NOT NULL DEFAULT 'active'
        ");

        /**
         * =========================================================
         * DROP AUDIT COLUMNS
         * =========================================================
         */

        Schema::table('teams', function (Blueprint $table) {

            if (Schema::hasColumn('teams', 'warning_points')) {
                $table->dropColumn('warning_points');
            }

            if (Schema::hasColumn('teams', 'banned_at')) {
                $table->dropColumn('banned_at');
            }

        });

        /**
         * =========================================================
         * REVERT TEAM STATUS LOGS
         * =========================================================
         */

        DB::statement("
            ALTER TABLE team_status_logs
            MODIFY status ENUM(
                'active',
                'suspended'
            ) NOT NULL
        ");
    }
};