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

        <!-- Service Credit Requests Section -->
    @if(isset($pendingServiceCreditRequests))
        <div id="serviceCreditRequestsSection" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mr-10 ml-10 mb-6">
            <div class="px-4 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pending Service Credit Requests</h3>
                            <p class="text-sm text-gray-600 mt-1">Review and approve Service Credit requests from teachers</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $pendingServiceCreditRequests->count() }} Pending
                        </span>
                        <a href="{{ route('admin.service-credit-requests') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            View All
                        </a>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <div class="hidden md:block">
                    @if($pendingServiceCreditRequests->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Segments</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Earned</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingServiceCreditRequests as $request)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                                <span class="text-xs font-medium text-white">
                                                    {{ strtoupper(substr($request->teacher->first_name, 0, 1)) }}{{ strtoupper(substr($request->teacher->last_name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $request->teacher->first_name }} {{ $request->teacher->middle_name }} {{ $request->teacher->last_name }} {{ $request->teacher->name_ext }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($request->work_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900">
                                    <div>
                                        @if($request->morning_in && $request->morning_out)
                                            AM: {{ \Carbon\Carbon::parse($request->morning_in)->format('g:i A') }} - {{ \Carbon\Carbon::parse($request->morning_out)->format('g:i A') }}
                                        @endif
                                    </div>
                                    <div>
                                        @if($request->afternoon_in && $request->afternoon_out)
                                            PM: {{ \Carbon\Carbon::parse($request->afternoon_in)->format('g:i A') }} - {{ \Carbon\Carbon::parse($request->afternoon_out)->format('g:i A') }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ number_format($request->total_hours,2) }} hrs</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-600">{{ number_format($request->requested_days,2) }} days</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 max-w-xs truncate" title="{{ $request->reason }}">{{ $request->reason }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $request->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <form method="POST" action="{{ route('admin.service-credit-requests.approve', $request->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" onclick="return confirm('Approve this Service Credit request?')">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.service-credit-requests.deny', $request->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" onclick="return confirm('Deny this Service Credit request?')">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                Deny
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="p-8 text-center text-sm text-gray-500">No pending Service Credit requests.</div>
                    @endif
                </div>
                <!-- Mobile cards -->
                <div class="md:hidden space-y-4 p-4">
                    @if($pendingServiceCreditRequests->count() > 0)
                    @foreach($pendingServiceCreditRequests as $request)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ strtoupper(substr($request->teacher->first_name, 0, 1)) }}{{ strtoupper(substr($request->teacher->last_name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $request->teacher->first_name }} {{ $request->teacher->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <div class="text-xs text-gray-500">Work Date</div>
                                <div class="text-sm font-medium text-gray-900">{{ optional($request->work_date)->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Hours</div>
                                <div class="text-sm font-medium text-gray-900">{{ number_format($request->total_hours,2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Days Earned</div>
                                <div class="text-sm font-medium text-purple-600">{{ number_format($request->requested_days,2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Reason</div>
                                <div class="text-sm font-medium text-gray-900 truncate" title="{{ $request->reason }}">{{ $request->reason }}</div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('admin.service-credit-requests.approve', $request->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" onclick="return confirm('Approve this Service Credit request?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.service-credit-requests.deny', $request->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" onclick="return confirm('Deny this Service Credit request?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Deny
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 text-center text-sm text-gray-500">No pending Service Credit requests.</div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Approved Leave Requests History Section -->
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scSection = document.getElementById('serviceCreditRequestsSection');
            if(!scSection) return;
            const tableBodySelector = '#serviceCreditRequestsSection table tbody';
            const badgeSelector = '#serviceCreditRequestsSection span.inline-flex';
            async function refreshServiceCredits(){
                try {
                    const resp = await fetch('{{ route('admin.service-credit-requests.pending-json') }}', {headers:{'Accept':'application/json'}});
                    if(!resp.ok) return;
                    const data = await resp.json();
                    const rows = data.data || [];
                    const badge = scSection.querySelector(badgeSelector);
                    if(badge){ badge.textContent = rows.length + ' Pending'; }
                    const tbody = scSection.querySelector(tableBodySelector);
                    if(!tbody) return;
                    if(rows.length === 0){
                        tbody.innerHTML = '<tr><td colspan="8" class="p-8 text-center text-sm text-gray-500">No pending Service Credit requests.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = rows.map(r => {
                        const am = (r.morning_in && r.morning_out) ? `AM: ${r.morning_in} - ${r.morning_out}` : '';
                        const pm = (r.afternoon_in && r.afternoon_out) ? `PM: ${r.afternoon_in} - ${r.afternoon_out}` : '';
                        return `<tr class=\"hover:bg-gray-50 transition-colors duration-200\">`
                            + `<td class=\"px-6 py-4 whitespace-nowrap\">${r.teacher || 'N/A'}</td>`
                            + `<td class=\"px-6 py-4 whitespace-nowrap text-sm\">${r.work_date || ''}</td>`
                            + `<td class=\"px-6 py-4 whitespace-nowrap text-xs\">${am}<br>${pm}</td>`
                            + `<td class=\"px-6 py-4 whitespace-nowrap text-sm font-medium\">${Number(r.total_hours).toFixed(2)} hrs</td>`
                            + `<td class=\"px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-600\">${Number(r.requested_days).toFixed(2)} days</td>`
                            + `<td class=\"px-6 py-4 whitespace-nowrap text-sm\" title=\"${r.reason}\">${r.reason}</td>`
                            + `<td class=\"px-6 py-4 whitespace-nowrap text-sm\">${r.created_at}</td>`
                            + `<td class=\"px-6 py-4 whitespace-nowrap text-sm font-medium\">`
                            + `<div class=\"flex space-x-2\">`
                            + `<form method=\"POST\" action=\"/admin/service-credit-requests/${r.id}/approve\">@csrf<button class=\"px-3 py-1.5 text-xs rounded-md text-white bg-green-600 hover:bg-green-700\" onclick=\"return confirm('Approve this Service Credit request?')\">Approve</button></form>`
                            + `<form method=\"POST\" action=\"/admin/service-credit-requests/${r.id}/deny\">@csrf<button class=\"px-3 py-1.5 text-xs rounded-md text-white bg-red-600 hover:bg-red-700\" onclick=\"return confirm('Deny this Service Credit request?')\">Deny</button></form>`
                            + `</div></td>`
                            + `</tr>`;
                    }).join('');
                } catch(e){ /* silent */ }
            }
            // Initial + periodic refresh
            refreshServiceCredits();
            setInterval(refreshServiceCredits, 15000); // 15s
        });
        </script>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 mr-10 ml-10 mb-8 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                <div class="flex items-center justify-between">
                    <div id="approvedLeaveHeaderToggle" class="flex items-center space-x-3 cursor-pointer hover:bg-green-100/50 rounded-lg p-2 -m-2 transition-colors duration-200 group" title="Click to toggle section">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors duration-200">Approved Leave Requests</h3>
                            <p class="text-sm text-gray-600">All approved leave requests across all roles</p>
                        </div>
                        <svg id="approvedLeaveToggleIcon" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Month/Year Filter -->
                        <select id="leaveFilterMonth" class="text-xs border border-gray-300 rounded px-2 py-1">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <select id="leaveFilterYear" class="text-xs border border-gray-300 rounded px-2 py-1">
                            @for($year = 2020; $year <= 2030; $year++)
                            <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                        <button id="filterLeaveRequests" class="inline-flex items-center px-2 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <!-- PDF Download Button -->
                        <button id="downloadLeavePDF" class="inline-flex items-center px-2 py-1 border border-green-300 shadow-sm text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </button>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ isset($approvedLeaveRequests) ? $approvedLeaveRequests->count() : 0 }} Approved
                        </span>
                    </div>
                </div>
            </div>
            <div id="approvedLeaveContent" class="transition-all duration-300">
                @if(isset($approvedLeaveRequests) && $approvedLeaveRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personnel</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($approvedLeaveRequests as $index => $request)
                                <tr class="hover:bg-green-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-white">
                                                        @if($request->user->personnel)
                                                            {{ substr($request->user->personnel->first_name, 0, 1) }}{{ substr($request->user->personnel->last_name, 0, 1) }}
                                                        @else
                                                            {{ substr($request->user->name, 0, 2) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($request->user->personnel)
                                                        {{ $request->user->personnel->first_name }} {{ $request->user->personnel->last_name }}
                                                    @else
                                                        {{ $request->user->name }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @if($request->user->personnel && $request->user->personnel->position)
                                                        {{ $request->user->personnel->position->title ?? 'N/A' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($request->user->role === 'school_head') bg-purple-100 text-purple-800
                                            @elseif($request->user->role === 'teacher') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $request->user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($request->user->personnel && $request->user->personnel->school)
                                            <div class="text-sm text-gray-900">{{ $request->user->personnel->school->school_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $request->user->personnel->school->school_id }}</div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $request->leave_type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">to {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                            {{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} day(s)
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->updated_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-500">{{ $request->updated_at->format('g:i A') }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <p class="text-gray-500 text-lg">No approved leave requests found</p>
                    <p class="text-gray-400 text-sm mt-1">Approved leave requests will appear here</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Approved CTO Requests History Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 mr-10 ml-10 mb-8 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-teal-50 to-white">
                <div class="flex items-center justify-between">
                    <div id="approvedCtoHeaderToggle" class="flex items-center space-x-3 cursor-pointer hover:bg-teal-100/50 rounded-lg p-2 -m-2 transition-colors duration-200 group" title="Click to toggle section">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-teal-600 transition-colors duration-200">Approved CTO Requests</h3>
                            <p class="text-sm text-gray-600">All approved CTO requests across all roles</p>
                        </div>
                        <svg id="approvedCtoToggleIcon" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Month/Year Filter -->
                        <select id="ctoFilterMonth" class="text-xs border border-gray-300 rounded px-2 py-1">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <select id="ctoFilterYear" class="text-xs border border-gray-300 rounded px-2 py-1">
                            @for($year = 2020; $year <= 2030; $year++)
                            <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                        <button id="filterCTORequests" class="inline-flex items-center px-2 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <!-- PDF Download Button -->
                        <button id="downloadCTOPDF" class="inline-flex items-center px-2 py-1 border border-teal-300 shadow-sm text-xs font-medium rounded-md text-teal-700 bg-teal-50 hover:bg-teal-100">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </button>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">
                            {{ isset($approvedCTORequests) ? $approvedCTORequests->count() : 0 }} Approved
                        </span>
                    </div>
                </div>
            </div>
            <div id="approvedCtoContent" class="transition-all duration-300">
                @if(isset($approvedCTORequests) && $approvedCTORequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personnel</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Hours</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CTO Hours</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CTO Days Earned</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Date</th>
                                    
                                </tr> 
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($approvedCTORequests as $index => $request)
                                <tr class="hover:bg-teal-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-white">
                                                        @if($request->personnel)
                                                            {{ substr($request->personnel->first_name, 0, 1) }}{{ substr($request->personnel->last_name, 0, 1) }}
                                                        @elseif($request->user)
                                                            {{ substr($request->user->name, 0, 2) }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($request->personnel)
                                                        {{ $request->personnel->first_name }} {{ $request->personnel->last_name }}
                                                    @elseif($request->user)
                                                        {{ $request->user->name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @if($request->personnel && $request->personnel->position)
                                                        {{ $request->personnel->position->title ?? 'N/A' }}
                                                    @endif
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                                    School Head
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($request->personnel && $request->personnel->school)
                                            <div class="text-sm text-gray-900">{{ $request->personnel->school->school_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $request->personnel->school->school_id }}</div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-medium">{{ \Carbon\Carbon::parse($request->work_date)->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($request->work_date)->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ \Carbon\Carbon::parse($request->start_time)->format('g:i A') }}</div>
                                        <div class="text-xs text-gray-500">to {{ \Carbon\Carbon::parse($request->end_time)->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 font-medium">
                                            {{ $request->requested_hours }} hours
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-teal-100 text-teal-800 font-medium">
                                            {{ number_format($request->cto_days_earned, 2) }} days
                                        </span>
                                    </td>
                                 
                                 
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->updated_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-500">{{ $request->updated_at->format('g:i A') }}</div>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No approved CTO requests found</p>
                    <p class="text-gray-400 text-sm mt-1">Approved CTO requests will appear here</p>
                </div>
                @endif
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

    <!-- JavaScript for History Section Toggle Feature -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Approved Leave Requests toggle functionality
            var approvedLeaveHeaderToggle = document.getElementById('approvedLeaveHeaderToggle');
            var approvedLeaveToggleIcon = document.getElementById('approvedLeaveToggleIcon');
            var approvedLeaveContent = document.getElementById('approvedLeaveContent');
            var isApprovedLeaveMinimized = localStorage.getItem('approvedLeaveMinimized') === 'true';

            // Set initial state based on localStorage
            if (isApprovedLeaveMinimized && approvedLeaveHeaderToggle && approvedLeaveContent) {
                approvedLeaveContent.style.height = '0';
                approvedLeaveContent.style.overflow = 'hidden';
                approvedLeaveContent.style.opacity = '0';
                approvedLeaveToggleIcon.style.transform = 'rotate(-90deg)';
            }

            if (approvedLeaveHeaderToggle && approvedLeaveContent) {
                approvedLeaveHeaderToggle.addEventListener('click', function() {
                    if (isApprovedLeaveMinimized) {
                        // Expand
                        approvedLeaveContent.style.height = 'auto';
                        approvedLeaveContent.style.overflow = 'visible';
                        approvedLeaveContent.style.opacity = '1';
                        approvedLeaveToggleIcon.style.transform = 'rotate(0deg)';
                        localStorage.setItem('approvedLeaveMinimized', 'false');
                    } else {
                        // Minimize
                        approvedLeaveContent.style.height = '0';
                        approvedLeaveContent.style.overflow = 'hidden';
                        approvedLeaveContent.style.opacity = '0';
                        approvedLeaveToggleIcon.style.transform = 'rotate(-90deg)';
                        localStorage.setItem('approvedLeaveMinimized', 'true');
                    }
                    isApprovedLeaveMinimized = !isApprovedLeaveMinimized;
                });
            }

            // Approved CTO Requests toggle functionality
            var approvedCtoHeaderToggle = document.getElementById('approvedCtoHeaderToggle');
            var approvedCtoToggleIcon = document.getElementById('approvedCtoToggleIcon');
            var approvedCtoContent = document.getElementById('approvedCtoContent');
            var isApprovedCtoMinimized = localStorage.getItem('approvedCtoMinimized') === 'true';

            // Set initial state based on localStorage for approved CTO
            if (isApprovedCtoMinimized && approvedCtoHeaderToggle && approvedCtoContent) {
                approvedCtoContent.style.height = '0';
                approvedCtoContent.style.overflow = 'hidden';
                approvedCtoContent.style.opacity = '0';
                approvedCtoToggleIcon.style.transform = 'rotate(-90deg)';
            }

            if (approvedCtoHeaderToggle && approvedCtoContent) {
                approvedCtoHeaderToggle.addEventListener('click', function() {
                    if (isApprovedCtoMinimized) {
                        // Expand
                        approvedCtoContent.style.height = 'auto';
                        approvedCtoContent.style.overflow = 'visible';
                        approvedCtoContent.style.opacity = '1';
                        approvedCtoToggleIcon.style.transform = 'rotate(0deg)';
                        localStorage.setItem('approvedCtoMinimized', 'false');
                    } else {
                        // Minimize
                        approvedCtoContent.style.height = '0';
                        approvedCtoContent.style.overflow = 'hidden';
                        approvedCtoContent.style.opacity = '0';
                        approvedCtoToggleIcon.style.transform = 'rotate(-90deg)';
                        localStorage.setItem('approvedCtoMinimized', 'true');
                    }
                    isApprovedCtoMinimized = !isApprovedCtoMinimized;
                });
            }

            // Filter functionality for approved leave requests
            const filterLeaveRequests = document.getElementById('filterLeaveRequests');
            const leaveFilterMonth = document.getElementById('leaveFilterMonth');
            const leaveFilterYear = document.getElementById('leaveFilterYear');
            const downloadLeavePDF = document.getElementById('downloadLeavePDF');

            if (filterLeaveRequests) {
                filterLeaveRequests.addEventListener('click', function() {
                    const month = leaveFilterMonth.value;
                    const year = leaveFilterYear.value;
                    
                    // Show loading state
                    filterLeaveRequests.disabled = true;
                    filterLeaveRequests.innerHTML = '<svg class="animate-spin w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';

                    fetch(`/admin/approved-leave-requests/filter?month=${month}&year=${year}`)
                        .then(response => response.json())
                        .then(data => {
                            updateLeaveRequestsTable(data);
                            updateLeaveRequestsCount(data.count);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to filter requests. Please try again.');
                        })
                        .finally(() => {
                            filterLeaveRequests.disabled = false;
                            filterLeaveRequests.innerHTML = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.586V4z" /></svg>Filter';
                        });
                });
            }

            // PDF download for leave requests
            if (downloadLeavePDF) {
                downloadLeavePDF.addEventListener('click', function() {
                    const month = leaveFilterMonth.value;
                    const year = leaveFilterYear.value;
                    const url = `/admin/approved-leave-requests/download-pdf?month=${month}&year=${year}`;
                    window.open(url, '_blank');
                });
            }

            // Filter functionality for approved CTO requests
            const filterCTORequests = document.getElementById('filterCTORequests');
            const ctoFilterMonth = document.getElementById('ctoFilterMonth');
            const ctoFilterYear = document.getElementById('ctoFilterYear');
            const downloadCTOPDF = document.getElementById('downloadCTOPDF');

            if (filterCTORequests) {
                filterCTORequests.addEventListener('click', function() {
                    const month = ctoFilterMonth.value;
                    const year = ctoFilterYear.value;
                    
                    // Show loading state
                    filterCTORequests.disabled = true;
                    filterCTORequests.innerHTML = '<svg class="animate-spin w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';

                    fetch(`/admin/approved-cto-requests/filter?month=${month}&year=${year}`)
                        .then(response => response.json())
                        .then(data => {
                            updateCTORequestsTable(data);
                            updateCTORequestsCount(data.count);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to filter requests. Please try again.');
                        })
                        .finally(() => {
                            filterCTORequests.disabled = false;
                            filterCTORequests.innerHTML = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.586V4z" /></svg>Filter';
                        });
                });
            }

            // PDF download for CTO requests
            if (downloadCTOPDF) {
                downloadCTOPDF.addEventListener('click', function() {
                    const month = ctoFilterMonth.value;
                    const year = ctoFilterYear.value;
                    const url = `/admin/approved-cto-requests/download-pdf?month=${month}&year=${year}`;
                    window.open(url, '_blank');
                });
            }

            // Helper functions to update tables
            function updateLeaveRequestsTable(data) {
                const tableContainer = document.querySelector('#approvedLeaveContent .overflow-x-auto');
                if (!tableContainer) return;

                if (data.requests.length === 0) {
                    tableContainer.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <p class="text-gray-500 text-lg">No approved leave requests found</p>
                            <p class="text-gray-400 text-sm mt-1">No requests match the selected filters</p>
                        </div>
                    `;
                    return;
                }

                let tableHTML = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personnel</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;

                data.requests.forEach((request, index) => {
                    const roleClass = request.role === 'school_head' ? 'bg-purple-100 text-purple-800' : 
                                     request.role === 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
                    
                    tableHTML += `
                        <tr class="hover:bg-green-50 transition-colors duration-200 ${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">${request.personnel_initials}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${request.personnel_name}</div>
                                        <div class="text-sm text-gray-500">${request.position_title}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${roleClass}">
                                    ${request.role.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="text-sm text-gray-900">${request.school_name}</div>
                                <div class="text-xs text-gray-500">${request.school_id}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${request.leave_type}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>${new Date(request.start_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</div>
                                <div class="text-xs text-gray-500">to ${new Date(request.end_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                    ${request.days_count} day(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${request.updated_at}
                                <div class="text-xs text-gray-500">${request.updated_time}</div>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += '</tbody></table>';
                tableContainer.innerHTML = tableHTML;
            }

            function updateCTORequestsTable(data) {
                const tableContainer = document.querySelector('#approvedCtoContent .overflow-x-auto');
                if (!tableContainer) return;

                if (data.requests.length === 0) {
                    tableContainer.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 text-lg">No approved CTO requests found</p>
                            <p class="text-gray-400 text-sm mt-1">No requests match the selected filters</p>
                        </div>
                    `;
                    return;
                }

                let tableHTML = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personnel</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Hours</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CTO Hours</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CTO Days Earned</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Notes</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;

                data.requests.forEach((request, index) => {
                    tableHTML += `
                        <tr class="hover:bg-teal-50 transition-colors duration-200 ${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">${request.personnel_initials}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${request.personnel_name}</div>
                                        <div class="text-sm text-gray-500">${request.position_title}</div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">School Head</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="text-sm text-gray-900">${request.school_name}</div>
                                <div class="text-xs text-gray-500">${request.school_id}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${new Date(request.work_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</div>
                                <div class="text-xs text-gray-500">${new Date(request.work_date).toLocaleDateString('en-US', {weekday: 'long'})}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>${request.start_time}</div>
                                <div class="text-xs text-gray-500">to ${request.end_time}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 font-medium">
                                    ${request.requested_hours} hours
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-teal-100 text-teal-800 font-medium">
                                    ${request.cto_days_earned} days
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    <p class="truncate" title="${request.reason}">${request.reason.length > 40 ? request.reason.substring(0, 40) + '...' : request.reason}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                ${request.admin_notes ? `<div class="max-w-xs"><p class="truncate text-gray-600 italic" title="${request.admin_notes}">${request.admin_notes.length > 40 ? request.admin_notes.substring(0, 40) + '...' : request.admin_notes}</p></div>` : '<span class="text-gray-400">No notes</span>'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${request.updated_at}
                                <div class="text-xs text-gray-500">${request.updated_time}</div>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += '</tbody></table>';
                tableContainer.innerHTML = tableHTML;
            }

            function updateLeaveRequestsCount(count) {
                const countElement = document.querySelector('#approvedLeaveHeaderToggle').parentElement.parentElement.querySelector('.bg-green-100');
                if (countElement) {
                    countElement.textContent = `${count} Approved`;
                }
            }

            function updateCTORequestsCount(count) {
                const countElement = document.querySelector('#approvedCtoHeaderToggle').parentElement.parentElement.querySelector('.bg-teal-100');
                if (countElement) {
                    countElement.textContent = `${count} Approved`;
                }
            }
        });
    </script>
</x-app-layout>
    