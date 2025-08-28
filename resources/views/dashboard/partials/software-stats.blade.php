<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-6 mb-6">
    <!-- Tasks Released This Month -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-semibold text-gray-500 flex items-center">
            Tasks Released
            <span class="ml-2 text-xs text-blue-600 font-medium bg-blue-50 px-2 py-1 rounded">
                {{ now()->format('F') }}
            </span>
        </h3>
        <p class="mt-1 text-3xl font-bold text-gray-900 flex items-center">
            {{ $dashboardData['current']['tasks_released'] }}
            @if($dashboardData['comparisons']['tasks_change'] > 0)
                <span class="ml-2 text-sm text-green-500 font-medium flex">
                    ↑ {{ $dashboardData['comparisons']['tasks_change'] }}
                </span>
            @elseif($dashboardData['comparisons']['tasks_change'] < 0)
                <span class="ml-2 text-sm text-red-500 font-medium flex">
                    ↓ {{ abs($dashboardData['comparisons']['tasks_change']) }}
                </span>
            @else
                <span class="ml-2 text-sm text-gray-500 font-medium flex">
                    → 0
                </span>
            @endif
        </p>
        <small class="text-gray-400">vs last month ({{ $dashboardData['previous']['tasks_released'] }})</small>
    </div>

    <!-- Open Bug Reports -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-semibold text-gray-500 flex items-center">
            Open Bug Reports
        </h3>
        <p class="mt-1 text-3xl font-bold text-gray-900 flex items-center">
            {{ $dashboardData['current']['open_bug_reports'] }}
            @if($dashboardData['current']['open_bug_reports'] > 10)
                <span class="ml-2 text-xs text-red-600 font-semibold bg-red-50 px-2 py-1 rounded">
                    High
                </span>
            @elseif($dashboardData['current']['open_bug_reports'] > 5)
                <span class="ml-2 text-xs text-yellow-600 font-semibold bg-yellow-50 px-2 py-1 rounded">
                    Medium
                </span>
            @else
                <span class="ml-2 text-xs text-green-600 font-semibold bg-green-50 px-2 py-1 rounded">
                    Good
                </span>
            @endif
        </p>
        <small class="text-gray-400">Open / In Progress</small>
    </div>

    @if(Auth::user()->role->name == 'Admin')
        <!-- Releases This Month -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-sm font-semibold text-gray-500 flex items-center">
                Releases This Month
                <span class="ml-2 text-xs text-indigo-600 font-medium bg-indigo-50 px-2 py-1 rounded">
                    {{ now()->format('F') }}
                </span>
            </h3>
            <p class="mt-1 text-3xl font-bold text-gray-900 flex items-center">
                {{ $dashboardData['current']['releases_count'] }}
                @if($dashboardData['comparisons']['releases_change'] > 0)
                    <span class="ml-2 text-sm text-green-500 font-medium flex">
                            ↑ {{ $dashboardData['comparisons']['releases_change'] }}
                        </span>
                @elseif($dashboardData['comparisons']['releases_change'] < 0)
                    <span class="ml-2 text-sm text-red-500 font-medium flex">
                        ↓ {{ abs($dashboardData['comparisons']['releases_change']) }}
                    </span>
                @else
                    <span class="ml-2 text-sm text-gray-500 font-medium flex">
                        → 0
                    </span>
                @endif
            </p>
            <small class="text-gray-400">vs last month ({{ $dashboardData['previous']['releases_count'] }})</small>
        </div>
    @endif

    @if(Auth::user()->role->name == 'Developer')
        <!-- Outstanding Tasks -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-sm font-semibold text-gray-500 flex items-center">
                Outstanding Tasks
            </h3>
            <p class="mt-1 text-3xl font-bold text-gray-900 flex items-center">
                {{ $dashboardData['current']['outstanding_tasks'] }}
                @if($dashboardData['current']['outstanding_tasks'] > 10)
                    <span class="ml-2 text-xs text-red-600 font-semibold bg-red-50 px-2 py-1 rounded">
                    High
                </span>
                @elseif($dashboardData['current']['outstanding_tasks'] > 5)
                    <span class="ml-2 text-xs text-yellow-600 font-semibold bg-yellow-50 px-2 py-1 rounded">
                    Medium
                </span>
                @else
                    <span class="ml-2 text-xs text-green-600 font-semibold bg-green-50 px-2 py-1 rounded">
                    Good
                </span>
                @endif
            </p>
            <small class="text-gray-400">Assigned to me</small>
        </div>
    @endif
</div>
