<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <!-- Dashboard Content -->
    <div x-data="{ showSchools: false, showPersonnels: false, showUsers: false, showJobStatus: false, showDistrict: false, showDivision: false, selectedStatus: '', selectedDistrict: '', selectedDivision: '' }" class="py-8 bg-gradient-to-br from-slate-50 to-gray-100 min-h-screen pr-80">
        
        <!-- Success Message -->
        @if(session('success'))
        <div class="mx-4 px-4 sm:px-6 mb-4">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="mx-4 px-4 sm:px-6">

            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
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

                <!-- Leave Requests Card -->
                <div class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer relative" onclick="document.getElementById('leaveRequestsSection').scrollIntoView({behavior: 'smooth'})">
                    @if($pendingLeaveRequests->count() > 0)
                    <div class="absolute -top-2 -right-2 flex">
                        <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Leave Requests</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">{{ $pendingLeaveRequests->count() }}</div>
                            <div class="text-xs text-gray-500">
                                @if($pendingLeaveRequests->count() > 0)
                                    Pending approvals
                                @else
                                    All up to date
                                @endif
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- CTO Requests Card -->
                <div class="group bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer relative" onclick="document.getElementById('ctoRequestsSection').scrollIntoView({behavior: 'smooth'})">
                    @if($pendingCTORequests->count() > 0)
                    <div class="absolute -top-2 -right-2 flex">
                        <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">CTO Requests</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">{{ $pendingCTORequests->count() }}</div>
                            <div class="text-xs text-gray-500">
                                @if($pendingCTORequests->count() > 0)
                                    Pending approvals
                                @else
                                    All up to date
                                @endif
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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

    <!-- Leave Approval Requests Table -->
    @if($pendingLeaveRequests->count() > 0)
    <div id="leaveRequestsSection" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mr-10 ml-10 mb-6">
            <div class="px-4 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pending Leave Approval Requests</h3>
                            <p class="text-sm text-gray-600 mt-1">Review and approve leave requests from personnel</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            {{ $pendingLeaveRequests->count() }} Pending
                        </span>
                        <a href="{{ route('admin.leave-requests') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            View All
                        </a>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                @if($pendingLeaveRequests->count() > 0)
                <div class="hidden md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Personnel
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Leave Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reason
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Requested Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingLeaveRequests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">
                                                {{ strtoupper(substr($request->user->personnel->first_name ?? $request->user->name, 0, 1)) }}{{ strtoupper(substr($request->user->personnel->last_name ?? '', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            @if($request->user->personnel)
                                                {{ $request->user->personnel->first_name }} {{ $request->user->personnel->middle_name }} {{ $request->user->personnel->last_name }} {{ $request->user->personnel->name_ext }}
                                            @else
                                                {{ $request->user->name }}
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($request->user->role === 'school_head') bg-purple-100 text-purple-800
                                    @elseif($request->user->role === 'teacher') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $request->user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $request->leave_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - 
                                    {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} day(s)
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $request->reason }}">
                                    {{ $request->reason }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('admin.leave-requests.update', $request->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                                onclick="return confirm('Are you sure you want to approve this leave request?')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.leave-requests.update', $request->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="denied">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                onclick="return confirm('Are you sure you want to deny this leave request?')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Deny
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                
                <!-- Mobile view -->
                <div class="md:hidden space-y-4">
                    @foreach($pendingLeaveRequests as $request)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($request->user->personnel->first_name ?? $request->user->name, 0, 1)) }}{{ strtoupper(substr($request->user->personnel->last_name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($request->user->personnel)
                                            {{ $request->user->personnel->first_name }} {{ $request->user->personnel->middle_name }} {{ $request->user->personnel->last_name }} {{ $request->user->personnel->name_ext }}
                                        @else
                                            {{ $request->user->name }}
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                        @if($request->user->role === 'school_head') bg-purple-100 text-purple-800
                                        @elseif($request->user->role === 'teacher') bg-green-100 text-green-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $request->user->role)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Leave Type</span>
                                <div class="text-sm text-gray-900 font-medium">{{ $request->leave_type }}</div>
                            </div>
                            
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Duration</span>
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - 
                                    {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                    <span class="text-xs text-gray-500">
                                        ({{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} day(s))
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Reason</span>
                                <div class="text-sm text-gray-900">{{ $request->reason }}</div>
                            </div>
                            
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Requested</span>
                                <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('admin.leave-requests.update', $request->id) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                        onclick="return confirm('Are you sure you want to approve this leave request?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.leave-requests.update', $request->id) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="denied">
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                        onclick="return confirm('Are you sure you want to deny this leave request?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Deny
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No pending leave requests</h3>
                    <p class="mt-1 text-sm text-gray-500">All leave requests have been processed.</p>
                </div>
                @endif
            </div>
    </div>
    @endif

    <!-- CTO Requests Section -->
    @if($pendingCTORequests->count() > 0)
    <div id="ctoRequestsSection" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mr-10 ml-10 mb-6">
            <div class="px-4 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pending CTO Approval Requests</h3>
                            <p class="text-sm text-gray-600 mt-1">Review and approve CTO requests from school heads</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $pendingCTORequests->count() }} Pending
                        </span>
                        <a href="{{ route('admin.cto-requests') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View All
                        </a>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                @if($pendingCTORequests->count() > 0)
                <div class="hidden md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                School Head
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Work Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hours
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                CTO Days Earned
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reason
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Requested Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingCTORequests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">
                                                {{ strtoupper(substr($request->personnel->first_name, 0, 1)) }}{{ strtoupper(substr($request->personnel->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $request->personnel->first_name }} {{ $request->personnel->middle_name }} {{ $request->personnel->last_name }} {{ $request->personnel->name_ext }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($request->work_date)->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($request->start_time)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($request->end_time)->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $request->requested_hours }} hours</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-600">{{ $request->cto_days_earned }} days</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $request->reason }}">
                                    {{ $request->reason }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('admin.cto-requests.approve', $request->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                                onclick="return confirm('Are you sure you want to approve this CTO request?')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.cto-requests.deny', $request->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                onclick="return confirm('Are you sure you want to deny this CTO request?')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Deny
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                
                <!-- Mobile view for CTO requests -->
                <div class="md:hidden space-y-4">
                    @foreach($pendingCTORequests as $request)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($request->personnel->first_name, 0, 1)) }}{{ strtoupper(substr($request->personnel->last_name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $request->personnel->first_name }} {{ $request->personnel->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <div class="text-xs text-gray-500">Work Date</div>
                                <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($request->work_date)->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Hours Worked</div>
                                <div class="text-sm font-medium text-gray-900">{{ $request->requested_hours }} hours</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">CTO Earned</div>
                                <div class="text-sm font-medium text-blue-600">{{ $request->cto_days_earned }} days</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Time</div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($request->start_time)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($request->end_time)->format('g:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-xs text-gray-500 mb-1">Reason</div>
                            <div class="text-sm text-gray-900">{{ $request->reason }}</div>
                        </div>
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('admin.cto-requests.approve', $request->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                        onclick="return confirm('Are you sure you want to approve this CTO request?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.cto-requests.deny', $request->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                        onclick="return confirm('Are you sure you want to deny this CTO request?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Deny
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No pending CTO requests</h3>
                    <p class="mt-1 text-sm text-gray-500">All CTO requests have been processed.</p>
                </div>
                @endif
            </div>
    </div>
    @endif

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
    