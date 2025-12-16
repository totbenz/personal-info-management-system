
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Personnel
        </h2>
    </x-slot>

    <div class="max-w-8xl mx-auto sm:px-6 sm:py-3 lg:px-8">
        <div class="mx-auto px-8 py-5 w-full bg-white shadow-md overflow-hidden sm:rounded-lg">
            @livewire('personnel-create')
        </div>
    </div>
</x-app-layout>
