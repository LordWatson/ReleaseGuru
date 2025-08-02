<div class="fixed left-0 top-0 h-screen w-48 primary-background shadow-lg flex items-center z-10">
    <nav class="w-full text-center">
        <ul class="space-y-6">
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Users') }}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('releases')">
                    {{ __('Releases') }}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('tasks')">
                    {{ __('Tasks') }}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('reports')">
                    {{ __('Reports') }}
                </x-nav-link>
            </li>
        </ul>
    </nav>
</div>
