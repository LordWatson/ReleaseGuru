<?php

namespace App\Http\Controllers;

use App\Contracts\Services\StatisticsServiceInterface;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly StatisticsServiceInterface $statisticsService
    ) {}

    /**
     * Display the dashboard with statistics and projects
     */
    public function index(): \Illuminate\View\View
    {
        $dashboardData = $this->statisticsService->getComprehensiveStats();
        $projects = Project::activeTasksCount()->get();

        return view('dashboard.dashboard', compact('dashboardData', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
