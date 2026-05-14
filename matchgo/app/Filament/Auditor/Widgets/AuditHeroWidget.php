<?php

namespace App\Filament\Auditor\Widgets;

use App\Models\Matches;
use App\Models\MatchAudit;
use App\Models\MatchVerification;
use App\Models\Team;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class AuditHeroWidget extends Widget
{
    protected string $view = 'filament.auditor.widgets.audit-hero-widget';

    protected static ?int $sort = 0;

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // ── Match stats ──
        $totalMatchesToday  = Matches::whereDate('created_at', $today)->count();
        $pendingAudit       = MatchAudit::whereNull('audited_at')->count();
        $pendingVerification = MatchVerification::where('status', 'pending')->count();

        // ── Team stats ──
        $totalTeams      = Team::count();
        $activeTeams     = Team::where('status', 'active')->count();

        // ── Audit completion rate ──
        $auditedToday      = MatchAudit::whereDate('audited_at', $today)->count();
        $completedMatches  = Matches::whereDate('created_at', $today)
            ->where('status', 'completed')->count();
        $auditRate         = $completedMatches > 0
            ? round(($auditedToday / $completedMatches) * 100)
            : 0;

        // ── Verification completion rate ──
        $verifiedToday     = MatchVerification::whereDate('created_at', $today)
            ->where('status', 'verified')->count();
        $verificationRate  = $completedMatches > 0
            ? round(($verifiedToday / $completedMatches) * 100)
            : 0;

        // ── Monthly comparison ──
        $matchesThisMonth  = Matches::where('created_at', '>=', $thisMonth)->count();
        $matchesLastMonth  = Matches::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $matchGrowth       = $matchesLastMonth > 0
            ? round((($matchesThisMonth - $matchesLastMonth) / $matchesLastMonth) * 100, 1)
            : 0;

        $auditsThisMonth   = MatchAudit::where('audited_at', '>=', $thisMonth)->count();
        $auditsLastMonth   = MatchAudit::whereBetween('audited_at', [$lastMonth, $lastMonthEnd])->count();
        $auditGrowth       = $auditsLastMonth > 0
            ? round((($auditsThisMonth - $auditsLastMonth) / $auditsLastMonth) * 100, 1)
            : 0;

        $userName = auth()->user()->name;

        return compact(
            'userName',
            'totalMatchesToday',
            'pendingAudit',
            'pendingVerification',
            'totalTeams',
            'activeTeams',
            'auditedToday',
            'auditRate',
            'verifiedToday',
            'verificationRate',
            'matchesThisMonth',
            'matchGrowth',
            'auditsThisMonth',
            'auditGrowth',
        );
    }
}