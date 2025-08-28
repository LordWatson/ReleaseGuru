<?php

namespace App\Services;

use App\Contracts\Services\StatisticsServiceInterface;
use App\Models\Task;
use App\Services\Base\BaseStatisticsService;
use Carbon\Carbon;

class DashboardStatisticsService extends BaseStatisticsService implements StatisticsServiceInterface
{
    protected function getCachePrefix(): string
    {
        return 'dashboard_stats';
    }

    /**
     * Get tasks released in a specific period (user sees only their tasks)
     */
    protected function getTasksReleasedInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        $cacheKey = 'tasks_released_user_' . auth()->id() . '_' . $startDate->format('Y_m');

        return $this->remember($cacheKey, self::CACHE_TTL_LONG, function () use ($startDate, $endDate){
            return Task::where('type', 'feature')
                ->where('developer_id', auth()->id())
                ->whereHas('release', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('release_date', [$startDate, $endDate]);
                })
                ->count();
        });
    }

    /**
     * Add outstanding tasks to current month stats
     */
    protected function getAdditionalCurrentStats(): array
    {
        return [
            'outstanding_tasks' => $this->getOutstandingTasks(),
        ];
    }

    /**
     * Get tasks currently not completed, by the developer
     */
    private function getOutstandingTasks(): int
    {
        $cacheKey = 'outstanding_tasks_' . auth()->id();

        return $this->remember($cacheKey, self::CACHE_TTL_LONG, function (){
            return Task::where('type', 'feature')
                ->where('developer_id', auth()->id())
                ->whereNotIn('status', ['completed', 'rejected'])
                ->count();
        });
    }
}
