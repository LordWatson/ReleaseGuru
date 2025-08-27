<?php

namespace App\Services;

use App\Models\BugReport;
use App\Models\Task;
use App\Models\Release;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsService
{
    /**
     * Get count of tasks released this month
     * Cached results every 240 seconds
     * @return int
     */
    public function getTasksReleasedThisMonth(): int
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return Cache::remember('dashboard_tasks_released_this_month', 14400, function () use($startOfMonth, $endOfMonth){
            return Task::where('type', 'feature')->whereHas('release', function ($query) use ($startOfMonth, $endOfMonth){
                $query->whereBetween('release_date', [$startOfMonth, $endOfMonth]);
            })->count();
        });
    }

    /**
     * Get count of bug reports that are 'open' or 'in_progress'
     * Cached results every 10 minutes
     * @return int
     */
    public function getOpenBugReports(): int
    {
        return Cache::remember('dashboard_open_bug_reports', 600, function (){
            return BugReport::whereIn('status', ['approved', 'open', 'in progress'])->count();
        });
    }

    /**
     * Get count of releases this month
     */
    public function getReleasesThisMonth(): int
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return Release::whereBetween('release_date', [$startOfMonth, $endOfMonth])->count();
    }

    /**
     * Get all dashboard statistics at once
     */
    public function getDashboardStats(): array
    {
        return [
            'tasks_released_this_month' => $this->getTasksReleasedThisMonth(),
            'open_bug_reports' => $this->getOpenBugReports(),
            'releases_this_month' => $this->getReleasesThisMonth(),
        ];
    }

    /**
     * Get comparison data for previous month
     */
    public function getTasksReleasedLastMonth(): int
    {
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        return Task::where('type', 'feature')->whereHas('release', function ($query) use ($startOfLastMonth, $endOfLastMonth){
            $query->whereBetween('release_date', [$startOfLastMonth, $endOfLastMonth]);
        })->count();
    }

    /**
     * Get releases count for last month
     */
    public function getReleasesLastMonth(): int
    {
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        return Release::whereBetween('release_date', [$startOfLastMonth, $endOfLastMonth])->count();
    }

    /**
     * Get comprehensive dashboard data with comparisons
     */
    public function getComprehensiveDashboardStats(): array
    {
        $currentStats = $this->getDashboardStats();
        $tasksLastMonth = $this->getTasksReleasedLastMonth();
        $releasesLastMonth = $this->getReleasesLastMonth();

        return [
            'current' => $currentStats,
            'comparisons' => [
                'tasks_change' => $currentStats['tasks_released_this_month'] - $tasksLastMonth,
                'releases_change' => $currentStats['releases_this_month'] - $releasesLastMonth,
                'tasks_last_month' => $tasksLastMonth,
                'releases_last_month' => $releasesLastMonth,
            ]
        ];
    }
}
