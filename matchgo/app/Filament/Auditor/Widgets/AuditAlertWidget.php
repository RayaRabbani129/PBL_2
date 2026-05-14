<?php

namespace App\Filament\Auditor\Widgets;

use App\Models\MatchAudit;
use App\Models\MatchVerification;
use App\Models\Matches;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class AuditAlertWidget extends Widget
{
    protected string $view = 'filament.auditor.widgets.audit-alert-widget';

    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        // Matches pending audit (completed but not yet audited)
        $pendingAudit = MatchAudit::whereNull('audited_at')
            ->with(['match.homeTeam', 'match.awayTeam', 'auditor'])
            ->latest()
            ->limit(5)
            ->get();

        // Pending verifications older than 1 hour
        $stalePending = MatchVerification::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHour())
            ->count();

        // Completed matches without verification (today)
        $unverifiedToday = Matches::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->whereDoesntHave('verification', function ($query) {
                $query->where('status', 'verified');
            })
            ->count();

        // High-risk matches detection: score difference > 10
        $highRiskMatches = Matches::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->whereRaw('ABS(home_score - away_score) > 10')
            ->count();
        $highRiskAlert = $highRiskMatches > 0;

        // Matches with low sportsmanship rating
        $lowSportMatches = MatchAudit::whereDate('audited_at', Carbon::today())
            ->where('sportsmanship_rating', '<', 5)
            ->count();

        return compact(
            'pendingAudit',
            'stalePending',
            'unverifiedToday',
            'highRiskMatches',
            'highRiskAlert',
            'lowSportMatches',
        );
    }
}