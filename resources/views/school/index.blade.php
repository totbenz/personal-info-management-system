<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <!-- Example: Academic Cap Icon (Heroicons) -->
            <svg class="w-6 h-6 mr-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0c-4.418 0-8-1.79-8-4V10m8 10c4.418 0 8-1.79 8-4V10"></path>
            </svg>
            {{ __('Schools') }}
        </h2>
    </x-slot>
    <div class="max-w-8xl mx-auto sm:px-6 sm:py-3 lg:px-8 lg:py-5">
        <ul class="inline-flex space-x-2">
            <li class="text-gray-600">
                <a href="https://craft.demo.quebixtechnology.com">Dashboard</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('schools.index') }}">Students</a>
            </li>
        </ul>
    </div>

    <div class="py-0 max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            @livewire('datatable.schools-datatable')
        </div>
    </div>
</x-app-layout>
