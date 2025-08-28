<?php

namespace Tests\Feature;

use App\Contracts\Services\StatisticsServiceInterface;
use Tests\TestCase;
use Mockery;

class DashboardControllerTest extends TestCase
{
    public function test_dashboard_displays_statistics()
    {
        $mockService = Mockery::mock(StatisticsServiceInterface::class);
        $mockService->shouldReceive('getComprehensiveStats')
                   ->once()
                   ->andReturn(['tasks_released' => 10]);

        $this->app->instance(StatisticsServiceInterface::class, $mockService);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('dashboardData');
    }
}
