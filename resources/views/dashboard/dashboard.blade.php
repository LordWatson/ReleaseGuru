<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('dashboard.partials.software-stats')
            @include('dashboard.partials.projects-table')
        </div>
    </div>
</x-app-layout>
