<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Metric Data -->
    <div class="py-12 mt-[-3rem]">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="px-5 py-8 ">
                <section class="flex space-x-5 justify-between">
                    <div class="w-1/3 flex items-center p-8 bg-white shadow rounded-lg">
                        <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-purple-600 bg-purple-100 rounded-full mr-6">
                            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold" wire:poll.15s>{{ $schoolCount }}</span>
                            <span class="block text-gray-500">Schools</span>
                        </div>
                    </div>
                    <div class="w-1/3 flex items-center p-8 bg-white shadow rounded-lg">
                        <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-green-600 bg-green-100 rounded-full mr-6">
                            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <span wire:poll class="block text-2xl font-bold">{{ $personnelCount }}</span>
                            <span class="block text-gray-500">Personnels</span>
                        </div>
                    </div>
                    <div class="w-1/3 flex items-center p-8 bg-white shadow rounded-lg">
                        <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-red-600 bg-red-100 rounded-full mr-6">
                            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                        <div>
                            <span class="inline-block text-2xl font-bold">{{ $userCount }}</span>
                            <span class="block text-gray-500">Users</span>
                        </div>
                    </div>
                </section>

                <!-- <section class="flex space-x-5 justify-between">
                </section> -->
            </div>
        </div>

        <!-- Loyalty Table -->
        <div class="mx-auto sm:px-6 lg:px-8 ml-5 mr-5">
            <div class="my-5 px-5 py-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="font-semibod text-lg text-gray-800 leading-tight">Loyalty Award Recipients - {{ date("Y") }}</h3>
                @livewire('datatable.loyalty-datatable')
            </div>
        </div>


    </div>
</x-app-layout>
