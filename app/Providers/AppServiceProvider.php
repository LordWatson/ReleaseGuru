<?php

namespace App\Providers;

use App\Contracts\Services\StatisticsServiceInterface;
use App\Services\AdminStatisticsService;
use App\Services\DashboardStatisticsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StatisticsServiceInterface::class, function ($app){
            // dashboard stats for admin uses
            if(Auth::check() && Auth::user()->role->name == 'Admin'){
                return $app->make(AdminStatisticsService::class);
            }

            // default standard stats for any other user type
            return $app->make(DashboardStatisticsService::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
