<?php

namespace App\Filament\Auditor\Widgets;

use App\Models\Matches;
use App\Models\MatchAudit;
use App\Models\MatchVerification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AuditSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today     = Carbon::today();
        $thisWeek  = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Matches stats
        $matchesToday   = Matches::whereDate('created_at', $today)->count();
        $matchesWeek    = Matches::where('created_at', '>=', $thisWeek)->count();
        $matchesMonth   = Matches::where('created_at', '>=', $thisMonth)->count();

        // Audit stats
        $auditedToday   = MatchAudit::whereDate('audited_at', $today)->count();
        $auditedWeek    = MatchAudit::where('audited_at', '>=', $thisWeek)->count();
        $auditedMonth   = MatchAudit::where('audited_at', '>=', $thisMonth)->count();

        // Verification stats
        $pendingVerification = MatchVerification::where('status', 'pending')->count();
        $verifiedToday       = MatchVerification::whereDate('created_at', $today)
            ->where('status', 'verified')->count();

        // Completed matches
        $completedToday = Matches::whereDate('created_at', $today)
            ->where('status', 'completed')->count();
        $completedMonth = Matches::where('created_at', '>=', $thisMonth)
            ->where('status', 'completed')->count();

        // Audit completion rate
        $matchesCompletedToday = Matches::whereDate('created_at', $today)
            ->where('status', 'completed')->count();
        $auditCompletionRate = $matchesCompletedToday > 0
            ? round(($auditedToday / $matchesCompletedToday) * 100)
            : 0;

        // Verification rate
        $verificationRate = $matchesCompletedToday > 0
            ? round(($verifiedToday / $matchesCompletedToday) * 100)
            : 0;

        return [
            Stat::make('Match Hari Ini', $matchesToday)
                ->description("Minggu ini: {$matchesWeek} | Bulan ini: {$matchesMonth}")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Audit Hari Ini', $auditedToday)
                ->description("Bulan ini: {$auditedMonth} ({$auditCompletionRate}% completion)")
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('success'),

            Stat::make('Menunggu Verifikasi', $pendingVerification)
                ->description("Hari ini terverifikasi: {$verifiedToday}")
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($pendingVerification > 10 ? 'danger' : 'warning'),

            Stat::make('Match Selesai', $completedToday)
                ->description("Bulan ini: {$completedMonth} | Rate: {$verificationRate}%")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
        ];
    }
}