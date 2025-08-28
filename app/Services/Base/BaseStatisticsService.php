<?php

namespace App\Services\Base;

use App\Models\BugReport;
use App\Models\Task;
use App\Models\Release;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

abstract class BaseStatisticsService
{
    protected array $cacheKeys = [];

    // Cache TTL constants
    protected const CACHE_TTL_SHORT = 600;    // 10 minutes
    protected const CACHE_TTL_MEDIUM = 3600;  // 1 hour
    protected const CACHE_TTL_LONG = 14400;   // 4 hours

    abstract protected function getCachePrefix(): string;

    protected function getCacheKey(string $key): string
    {
        return $this->getCachePrefix() . '_' . $key;
    }

    protected function remember(string $key, int $ttl, callable $callback)
    {
        $cacheKey = $this->getCacheKey($key);
        return Cache::remember($cacheKey, $ttl, $callback);
    }

    protected function getDateRanges(): array
    {
        return [
            'current_month' => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ],
            'last_month' => [
                'start' => Carbon::now()->subMonth()->startOfMonth(),
                'end' => Carbon::now()->subMonth()->endOfMonth(),
            ],
        ];
    }

    protected function calculatePercentageChange(int $previous, int $current): float
    {
        if($previous === 0){
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
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
    protected function buildComprehensiveStats(): array
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
    protected function getCurrentMonthStats(): array
    {
        $dateRanges = $this->getDateRanges();

        $stats = [
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

        // Allow child classes to add additional stats
        return array_merge($stats, $this->getAdditionalCurrentStats());
    }

    /**
     * Get previous month statistics
     */
    protected function getPreviousMonthStats(): array
    {
        $dateRanges = $this->getDateRanges();

        $stats = [
            'tasks_released' => $this->getTasksReleasedInPeriod(
                $dateRanges['last_month']['start'],
                $dateRanges['last_month']['end']
            ),
            'releases_count' => $this->getReleasesInPeriod(
                $dateRanges['last_month']['start'],
                $dateRanges['last_month']['end']
            ),
        ];

        // Allow child classes to add additional stats
        return array_merge($stats, $this->getAdditionalPreviousStats());
    }

    /**
     * Build comparison data between periods
     */
    protected function buildComparisons(array $current, array $previous): array
    {
        $comparisons = [
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

        // Allow child classes to add additional comparisons
        return array_merge($comparisons, $this->getAdditionalComparisons($current, $previous));
    }

    /**
     * Build trend indicators
     */
    protected function buildTrends(array $current, array $previous): array
    {
        $trends = [
            'tasks_trend' => $this->getTrendDirection($previous['tasks_released'], $current['tasks_released']),
            'releases_trend' => $this->getTrendDirection($previous['releases_count'], $current['releases_count']),
        ];

        // Allow child classes to add additional trends
        return array_merge($trends, $this->getAdditionalTrends($current, $previous));
    }

    /**
     * Get trend direction (up, down, stable)
     */
    protected function getTrendDirection(int $previous, int $current): string
    {
        if($current > $previous){
            return 'up';
        }elseif ($current < $previous){
            return 'down';
        }

        return 'stable';
    }

    /**
     * Get tasks released in a specific period - to be implemented by child classes
     */
    abstract protected function getTasksReleasedInPeriod(Carbon $startDate, Carbon $endDate): int;

    /**
     * Get releases in a specific period
     */
    protected function getReleasesInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        $cacheKey = 'releases_count_' . $startDate->format('Y_m');

        return $this->remember($cacheKey, self::CACHE_TTL_LONG, function () use ($startDate, $endDate){
            return Release::whereBetween('release_date', [$startDate, $endDate])->count();
        });
    }

    /**
     * Get count of open bug reports with a short cache
     */
    protected function getOpenBugReports(): int
    {
        return $this->remember('open_bug_reports', self::CACHE_TTL_SHORT, function (){
            return BugReport::whereIn('status', ['approved', 'open', 'in progress'])->count();
        });
    }

    /**
     * Legacy method for backward compatibility
     */
    public function getComprehensiveDashboardStats(): array
    {
        return $this->getComprehensiveStats();
    }

    /**
     * Additional utility methods
     */
    public function getQuickStats(): array
    {
        return $this->remember('quick_stats', self::CACHE_TTL_SHORT, function (){
            return [
                'open_bug_reports' => $this->getOpenBugReports(),
            ];
        });
    }

    // Hook methods for child classes to override
    protected function getAdditionalCurrentStats(): array
    {
        return [];
    }

    protected function getAdditionalPreviousStats(): array
    {
        return [];
    }

    protected function getAdditionalComparisons(array $current, array $previous): array
    {
        return [];
    }

    protected function getAdditionalTrends(array $current, array $previous): array
    {
        return [];
    }
}
