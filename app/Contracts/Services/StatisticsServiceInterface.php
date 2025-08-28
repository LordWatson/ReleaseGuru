<?php

namespace App\Contracts\Services;

interface StatisticsServiceInterface
{
    public function getComprehensiveStats(): array;
}
