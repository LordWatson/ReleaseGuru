<?php

namespace App\Services;

use App\Contracts\Services\StatisticsServiceInterface;
use App\Models\Task;
use App\Services\Base\BaseStatisticsService;
use Carbon\Carbon;

class AdminStatisticsService extends BaseStatisticsService implements StatisticsServiceInterface
{
    protected function getCachePrefix(): string
    {
        return 'admin_stats';
    }

    /**
     * Get tasks released in a specific period (admin sees all tasks)
     */
    protected function getTasksReleasedInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        $cacheKey = 'tasks_released_all_' . $startDate->format('Y_m');

        return $this->remember($cacheKey, self::CACHE_TTL_LONG, function () use ($startDate, $endDate){
            return Task::where('type', 'feature')
                ->whereHas('release', function ($query) use ($startDate, $endDate){
                    $query->whereBetween('release_date', [$startDate, $endDate]);
                })
                ->count();
        });
    }
}
