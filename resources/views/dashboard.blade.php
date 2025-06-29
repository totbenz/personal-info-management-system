<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Dashboard Content -->
    <div class="py-8 bg-gradient-to-br from-slate-50 to-gray-100 min-h-screen pr-80">
        <div class="mx-4 px-4 sm:px-6">
            
            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Schools Card -->
                <div class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Schools</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1" wire:poll.15s>{{ $schoolCount }}</div>
                            <div class="text-xs text-gray-500">Total registered schools</div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Personnel Card -->
                <div class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Personnel</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1" wire:poll>{{ $personnelCount }}</div>
                            <div class="text-xs text-gray-500">Total personnel members</div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-2 h-2 bg-rose-500 rounded-full"></div>
                                <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Users</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">{{ $userCount }}</div>
                            <div class="text-xs text-gray-500">System users</div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                <!-- Job Status Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Job Status</h3>
                            <p class="text-xs text-gray-500">Personnel distribution</p>
                        </div>
                    </div>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @forelse($jobStatusCounts as $status => $count)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                            <span class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $count }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500 text-sm mt-1">No job status data available</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Schools per District Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Districts</h3>
                            <p class="text-xs text-gray-500">Schools per district</p>
                        </div>
                    </div>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @forelse($schoolsPerDistrict as $districtId => $count)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                            <span class="text-sm font-medium text-gray-700">District {{ $districtId }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $count }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <p class="text-gray-500 text-sm mt-1">No district data available</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Schools per Division Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Divisions</h3>
                            <p class="text-xs text-gray-500">Schools per division</p>
                        </div>
                    </div>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @forelse($schoolsPerDivision as $division => $count)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg hover:bg-pink-50 transition-colors duration-200">
                            <span class="text-sm font-medium text-gray-700 capitalize">{{ $division }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                                    {{ $count }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-gray-500 text-sm mt-1">No division data available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            </div>
            
            <!-- Loyalty Award Recipients Table -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mr-10 ml-10">
                <div class="px-4 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Loyalty Award Recipients</h3>
                            <p class="text-sm text-gray-600 mt-1">Recognizing excellence in {{ date("Y") }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    @livewire('datatable.loyalty-datatable')
                </div>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="fixed right-0 top-12 h-screen z-10 bg-slate-300">
            @livewire('right-sidebar')
        </div>
</x-app-layout>
