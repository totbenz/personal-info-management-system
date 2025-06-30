<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ __('Personnel') }}</span>
        </h2>
    </x-slot>
    <div class="max-w-8xl mx-auto sm:px-6 sm:py-3 lg:px-8 lg:py-5">
        <ul class="inline-flex space-x-2">
            <li class="text-gray-600">
                <a href="{{ route('admin.home') }}">Dashboard</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('personnels.index') }}">Personnel</a>
            </li>
        </ul>
    </div>

    <div class="py-0 max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            @livewire('datatable.personnels-datatable')
        </div>
    </div>
</x-app-layout>
