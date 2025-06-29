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

                <!-- Job Status Counts Card -->
                <div class="mt-8 flex flex-wrap gap-6">
                    <div class="w-full max-w-md bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8M12 8v8" />
                            </svg>
                            Personnel Job Status Counts
                        </h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse($jobStatusCounts as $status => $count)
                            <li class="py-2 flex justify-between items-center">
                                <span class="capitalize text-gray-700">{{ $status }}</span>
                                <span class="font-bold text-indigo-700">{{ $count }}</span>
                            </li>
                            @empty
                            <li class="py-2 text-gray-400">No personnel job status data.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Schools per District and Division Cards -->
                <div class="mt-8 flex flex-wrap gap-6">
                    <div class="w-full max-w-md bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8M12 8v8" />
                            </svg>
                            Schools per District
                        </h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse($schoolsPerDistrict as $districtId => $count)
                            <li class="py-2 flex justify-between items-center">
                                <span class="capitalize text-gray-700">District ID: {{ $districtId }}</span>
                                <span class="font-bold text-blue-700">{{ $count }}</span>
                            </li>
                            @empty
                            <li class="py-2 text-gray-400">No district data.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="w-full max-w-md bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                            </svg>
                            Schools per Division
                        </h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse($schoolsPerDivision as $division => $count)
                            <li class="py-2 flex justify-between items-center">
                                <span class="capitalize text-gray-700">{{ $division }}</span>
                                <span class="font-bold text-pink-700">{{ $count }}</span>
                            </li>
                            @empty
                            <li class="py-2 text-gray-400">No division data.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Active Personnels Card -->
                <div class="mt-8">
                    <div class="w-full max-w-2xl bg-white shadow rounded-lg p-6 mx-auto">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4" />
                            </svg>
                            Active Personnels
                        </h3>
                        <div class="max-h-56 overflow-y-auto">
                            <ul class="divide-y divide-gray-200">
                                @forelse($activePersonnels as $personnel)
                                <li class="py-2 flex items-center">
                                    <span class="font-medium text-gray-700">
                                        {{ $personnel['first_name'] }}
                                        @if($personnel['middle_name']){{ ' ' . $personnel['middle_name'][0] . '.' }}@endif
                                        {{ ' ' . $personnel['last_name'] }}
                                        @if($personnel['name_ext']){{ ' ' . $personnel['name_ext'] }}@endif
                                    </span>
                                </li>
                                @empty
                                <li class="py-2 text-gray-400">No active personnels found.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

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
