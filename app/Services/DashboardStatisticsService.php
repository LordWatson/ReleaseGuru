<?php

namespace App\Services;

use App\Contracts\Services\StatisticsServiceInterface;
use App\Models\BugReport;
use App\Models\Task;
use App\Models\Release;
use App\Services\Base\BaseStatisticsService;
use Carbon\Carbon;

class DashboardStatisticsService extends BaseStatisticsService implements StatisticsServiceInterface
{
    protected function getCachePrefix(): string
    {
        return 'dashboard_stats';
    }

    /**
     * Get comprehensive dashboard statistics with enhanced data
     */
    public function getComprehensiveStats(): array
    {
        return $this->remember(
            'comprehensive_dashboard_stats',
            self::CACHE_TTL_MEDIUM,
            fn() => $this->buildComprehensiveStats()
        );
    }

    /**
     * Build comprehensive statistics data
     */
    private function buildComprehensiveStats(): array
    {
        $current = $this->getCurrentMonthStats();
        $previous = $this->getPreviousMonthStats();

        return [
            'current' => $current,
            'previous' => $previous,
            'comparisons' => $this->buildComparisons($current, $previous),
            'trends' => $this->buildTrends($current, $previous),
            'metadata' => [
                'generated_at' => now()->toISOString(),
                'period' => [
                    'current_month' => Carbon::now()->format('F Y'),
                    'previous_month' => Carbon::now()->subMonth()->format('F Y'),
                ],
            ]
        ];
    }

    /**
     * Get current month statistics with optimized caching
     */
    private function getCurrentMonthStats(): array
    {
        $dateRanges = $this->getDateRanges();

        return [
            'tasks_released' => $this->getTasksReleasedInPeriod(
                $dateRanges['current_month']['start'],
                $dateRanges['current_month']['end']
            ),
            'open_bug_reports' => $this->getOpenBugReports(),
            'releases_count' => $this->getReleasesInPeriod(
                $dateRanges['current_month']['start'],
                $dateRanges['current_month']['end']
            ),
        ];
    }

    /**
     * Get previous month statistics
     */
    private function getPreviousMonthStats(): array
    {
        $dateRanges = $this->getDateRanges();

        return [
            'tasks_released' => $this->getTasksReleasedInPeriod(
                $dateRanges['last_month']['start'],
                $dateRanges['last_month']['end']
            ),
            'releases_count' => $this->getReleasesInPeriod(
                $dateRanges['last_month']['start'],
                $dateRanges['last_month']['end']
            ),
        ];
    }

    /**
     * Build comparison data between periods
     */
    private function buildComparisons(array $current, array $previous): array
    {
        return [
            'tasks_change' => $current['tasks_released'] - $previous['tasks_released'],
            'releases_change' => $current['releases_count'] - $previous['releases_count'],
            'tasks_change_percentage' => $this->calculatePercentageChange(
                $previous['tasks_released'],
                $current['tasks_released']
            ),
            'releases_change_percentage' => $this->calculatePercentageChange(
                $previous['releases_count'],
                $current['releases_count']
            ),
        ];
    }

    /**
     * Build trend indicators
     */
    private function buildTrends(array $current, array $previous): array
    {
        return [
            'tasks_trend' => $this->getTrendDirection($previous['tasks_released'], $current['tasks_released']),
            'releases_trend' => $this->getTrendDirection($previous['releases_count'], $current['releases_count']),
        ];
    }

    /**
     * Get trend direction (up, down, stable)
     */
    private function getTrendDirection(int $previous, int $current): string
    {
        if ($current > $previous) {
            return 'up';
        } elseif ($current < $previous) {
            return 'down';
        }

        return 'stable';
    }

    /**
     * Get tasks released in a specific period
     */
    private function getTasksReleasedInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        $cacheKey = 'tasks_released_' . $startDate->format('Y_m');

        return $this->remember($cacheKey, self::CACHE_TTL_LONG, function () use ($startDate, $endDate) {
            return Task::where('type', 'feature')
                ->whereHas('release', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('release_date', [$startDate, $endDate]);
                })
                ->count();
        });
    }

    /**
     * Get releases in a specific period
     */
    private function getReleasesInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        $cacheKey = 'releases_count_' . $startDate->format('Y_m');

        return $this->remember($cacheKey, self::CACHE_TTL_LONG, function () use ($startDate, $endDate) {
            return Release::whereBetween('release_date', [$startDate, $endDate])->count();
        });
    }

    /**
     * Get count of open bug reports with a short cache
     */
    private function getOpenBugReports(): int
    {
        return $this->remember('open_bug_reports', self::CACHE_TTL_SHORT, function () {
            return BugReport::whereIn('status', ['approved', 'open', 'in progress'])->count();
        });
    }

    // Legacy method for backward compatibility
    public function getComprehensiveDashboardStats(): array
    {
        return $this->getComprehensiveStats();
    }

    // Additional utility methods
    public function getQuickStats(): array
    {
        return $this->remember('quick_stats', self::CACHE_TTL_SHORT, function () {
            return [
                'open_bug_reports' => $this->getOpenBugReports(),
            ];
        });
    }
}
