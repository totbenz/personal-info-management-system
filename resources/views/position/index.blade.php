<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Position') }}
        </h2>
    </x-slot>
    <div class="py-0 max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-2">
            @include('position.forms.create')
            @livewire('datatable.position-datatable')

        </div>
    </div>
</x-app-layout>
