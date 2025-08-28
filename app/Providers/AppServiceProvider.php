<?php

namespace App\Providers;

use App\Contracts\Services\StatisticsServiceInterface;
use App\Services\DashboardStatisticsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            StatisticsServiceInterface::class,
            DashboardStatisticsService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
