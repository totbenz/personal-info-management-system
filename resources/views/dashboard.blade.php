<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Dashboard Content -->
    <div x-data="{ showSchools: false, showPersonnels: false, showUsers: false, showJobStatus: false, showDistrict: false, showDivision: false, selectedStatus: '', selectedDistrict: '', selectedDivision: '' }" class="py-8 bg-gradient-to-br from-slate-50 to-gray-100 min-h-screen pr-80">
        <div class="mx-4 px-4 sm:px-6">

            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Schools Card -->
                <div @click="showSchools = true" class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer">
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
                <div @click="showPersonnels = true" class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer">
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
                <div @click="showUsers = true" class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer">
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

            <!-- Schools Modal -->
            <div x-show="showSchools" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                    <button @click="showSchools = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Registered Schools</h2>
                    <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                        @foreach($schools as $school)
                        <li class="py-2 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-800">{{ $school->school_name ?? $school->name ?? 'School' }}</span>
                                <span class="ml-2 text-xs text-gray-500">ID: {{ $school->school_id ?? $school->id }}</span>
                            </div>
                            <a href="{{ route('schools.show', ['school' => $school->id]) }}" class="ml-4 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition" target="_blank">Show</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Personnels Modal -->
            <div x-show="showPersonnels" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                    <button @click="showPersonnels = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Active Personnels</h2>
                    <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                        @foreach($allPersonnels as $personnel)
                        <li class="py-2 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-800">
                                    {{ $personnel->first_name }} {{ $personnel->middle_name }} {{ $personnel->last_name }} {{ $personnel->name_ext }}
                                </span>
                                <span class="ml-2 text-xs text-green-600">({{ $personnel->job_status }})</span>
                            </div>
                            <a href="{{ route('personnels.show', ['personnel' => $personnel->id]) }}" class="ml-4 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition" target="_blank">Show</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Users Modal -->
            <div x-show="showUsers" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                    <button @click="showUsers = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">System Users</h2>
                    <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                        @foreach($users as $user)
                        <li class="py-2 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-800">
                                    @if($user->personnel)
                                    {{ $user->personnel->first_name }} {{ $user->personnel->middle_name }} {{ $user->personnel->last_name }} {{ $user->personnel->name_ext }}
                                    @else
                                    (No Personnel Info)
                                    @endif
                                </span>
                                <span class="ml-2 text-xs text-gray-500">{{ $user->email }}</span>
                            </div>
                            @if($user->personnel)
                            <a href="{{ route('personnels.show', ['personnel' => $user->personnel->id]) }}" class="ml-4 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition" target="_blank">Show</a>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Job Status Modal -->
            <div x-show="showJobStatus" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
                    <button @click="showJobStatus = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Personnel by Job Status</h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach(array_keys($jobStatusCounts->toArray()) as $status)
                        <button @click="selectedStatus = '{{ $status }}'" class="px-3 py-1 rounded bg-indigo-100 text-indigo-700 text-xs font-semibold" :class="selectedStatus === '{{ $status }}' ? 'bg-indigo-600 text-white' : ''">{{ ucfirst($status) }}</button>
                        @endforeach
                    </div>
                    <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                        @foreach($allPersonnels as $personnel)
                        <template x-if="selectedStatus === '' || selectedStatus === '{{ $personnel->job_status }}'">
                            <li class="py-2 flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $personnel->first_name }} {{ $personnel->middle_name }} {{ $personnel->last_name }} {{ $personnel->name_ext }}</span>
                                    <span class="ml-2 text-xs text-gray-500">({{ ucfirst($personnel->job_status) }})</span>
                                </div>
                                <a href="{{ route('personnels.show', ['personnel' => $personnel->id]) }}" class="ml-4 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition" target="_blank">Show</a>
                            </li>
                        </template>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- District Modal -->
            <div x-show="showDistrict" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
                    <button @click="showDistrict = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Schools by District</h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($schoolsPerDistrict as $districtId => $count)
                        <button @click="selectedDistrict = '{{ $districtId }}'" class="px-3 py-1 rounded bg-blue-100 text-blue-700 text-xs font-semibold" :class="selectedDistrict === '{{ $districtId }}' ? 'bg-blue-600 text-white' : ''">District {{ $districtId }}</button>
                        @endforeach
                    </div>
                    <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                        @foreach($schools as $school)
                        <template x-if="selectedDistrict === '' || selectedDistrict === '{{ $school->district_id }}'">
                            <li class="py-2 flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $school->school_name }}</span>
                                    <span class="ml-2 text-xs text-gray-500">District: {{ $school->district_id }}</span>
                                </div>
                                <a href="{{ route('schools.show', ['school' => $school->id]) }}" class="ml-4 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition" target="_blank">Show</a>
                            </li>
                        </template>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Division Modal -->
            <div x-show="showDivision" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
                    <button @click="showDivision = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                    <h2 class="text-xl font-semibold mb-4">Schools by Division</h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($schoolsPerDivision as $division => $count)
                        <button @click="selectedDivision = '{{ $division }}'" class="px-3 py-1 rounded bg-pink-100 text-white-700 text-xs font-semibold" :class="selectedDivision === '{{ $division }}' ? 'bg-pink-600 text-white' : ''">{{ $division }}</button>
                        @endforeach
                    </div>
                    <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                        @foreach($schools as $school)
                        <template x-if="selectedDivision === '' || selectedDivision === '{{ $school->division }}'">
                            <li class="py-2 flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $school->school_name }}</span>
                                    <span class="ml-2 text-xs text-gray-500">Division: {{ $school->division }}</span>
                                </div>
                                <a href="{{ route('schools.show', ['school' => $school->id]) }}" class="ml-4 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition" target="_blank">Show</a>
                            </li>
                        </template>
                        @endforeach
                    </ul>
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
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach($jobStatusCounts as $status => $count)
                        <button @click="showJobStatus = true; selectedStatus = '{{ $status }}'" class="px-3 py-1 rounded bg-indigo-100 text-indigo-700 text-xs font-semibold hover:bg-indigo-600 hover:text-white transition">{{ ucfirst($status) }} ({{ $count }})</button>
                        @endforeach
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
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach($schoolsPerDistrict as $districtId => $count)
                        <button @click="showDistrict = true; selectedDistrict = '{{ $districtId }}'" class="px-3 py-1 rounded bg-blue-100 text-blue-700 text-xs font-semibold hover:bg-blue-600 hover:text-white transition">District {{ $districtId }} ({{ $count }})</button>
                        @endforeach
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
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach($schoolsPerDivision as $division => $count)
                        <button @click="showDivision = true; selectedDivision = '{{ $division }}'" class="px-3 py-1 rounded bg-pink-100 text-pink-700 text-xs font-semibold hover:bg-pink-600 hover:text-white transition">{{ $division }} ({{ $count }})</button>
                        @endforeach
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
    <div class="fixed right-0 top-12 h-screen z-10 bg-slate-300" style="z-index:5;">
        @livewire('right-sidebar')
    </div>
</x-app-layout>
