<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Personnel') }}
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
