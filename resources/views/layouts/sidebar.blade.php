<div class="fixed left-0 top-0 h-screen w-48 primary-background shadow-lg flex items-center z-10">
    <nav class="w-full text-center">
        <ul class="space-y-6">
            <li>
                <x-nav-link :href="route('users.index')" :active="request()->is('users')">
                    {{ __('Users') }}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('projects.index')" :active="request()->is('projects')">
                    {{ __('Projects') }}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('releases.index')" :active="request()->is('releases')">
                    {{ __('Releases') }}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('features.index')" :active="request()->is('features')">
                    {{ __('Tasks') }}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('reports.index')" :active="request()->is('reports')">
                    {{ __('Reports') }}
                </x-nav-link>
            </li>
        </ul>
    </nav>
</div>
