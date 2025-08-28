<?php

namespace App\Services\Base;

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
        if ($previous === 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
