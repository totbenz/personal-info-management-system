<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">
                    {{ $schoolInfo['name'] }}
                </h2>
                <p class="text-sm text-gray-600">School Management Dashboard</p>
            </div>
        </div>
    </x-slot>

    <!-- Dashboard Content -->
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/30">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- School Information Card -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">School Information</h3>
                            <p class="text-gray-600">Manage your school's basic information and settings</p>
                        </div>
                        <a href="{{ route('schools.edit', ['school' => Auth::user()->personnel->school]) }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit School
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700 mb-1">School ID</p>
                                <p class="text-lg font-bold text-gray-900">{{ $schoolInfo['id'] }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-xl border border-emerald-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-emerald-700 mb-1">Division</p>
                                <p class="text-lg font-bold text-gray-900">{{ $schoolInfo['division'] }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl border border-purple-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-700 mb-1">Email</p>
                                <p class="text-lg font-bold text-gray-900">{{ $schoolInfo['email'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Personnel -->
                <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-blue-600/10 rounded-full -mr-12 -mt-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Personnel</span>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $totalPersonnel }}</div>
                        <div class="text-sm text-gray-600">All staff members</div>
                    </div>
                </div>

                <!-- Active Personnel -->
                <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 rounded-full -mr-12 -mt-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Active</span>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $activePersonnel }}</div>
                        <div class="text-sm text-gray-600">Currently active</div>
                    </div>
                </div>

                <!-- Teaching Personnel -->
                <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-500/10 to-purple-600/10 rounded-full -mr-12 -mt-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-purple-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Teaching</span>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $teachingPersonnel }}</div>
                        <div class="text-sm text-gray-600">Teaching staff</div>
                    </div>
                </div>

                <!-- Non-Teaching Personnel -->
                <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-500/10 to-orange-600/10 rounded-full -mr-12 -mt-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Non-Teaching</span>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $nonTeachingPersonnel }}</div>
                        <div class="text-sm text-gray-600">Non-teaching staff</div>
                    </div>
                </div>
            </div>

            <!-- Available Leaves Section (moved up for prominence) -->
            @include('school_head.partials.leaves', [
                'leaveData' => $leaveData ?? [],
                'ctoBalance' => $ctoBalance ?? [],
                'accrualSummary' => $accrualSummary ?? null,
                'year' => $year ?? date('Y')
            ])

            <!-- Leave Request History Section -->
            @if(isset($leaveRequests) && $leaveRequests->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div id="historyHeaderToggle" class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 rounded-lg p-2 -m-2 transition-colors duration-200 group" title="Click to toggle section">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors duration-200">Your Leave Request History</h3>
                        <svg id="historyToggleIcon" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            {{ $leaveRequests->where('status', 'pending')->count() }} Pending
                        </span>
                    </div>
                </div>
                <div id="leaveHistoryContent" class="space-y-4 transition-all duration-300">
                    @foreach($leaveRequests as $request)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r
                        @if($request->status === 'pending') from-orange-50 to-orange-100/50
                        @elseif($request->status === 'approved') from-green-50 to-green-100/50
                        @else from-red-50 to-red-100/50 @endif
                        rounded-xl border
                        @if($request->status === 'pending') border-orange-200/50
                        @elseif($request->status === 'approved') border-green-200/50
                        @else border-red-200/50 @endif
                        hover:shadow-md transition-all duration-200">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-br
                                @if($request->status === 'pending') from-orange-500 to-orange-600
                                @elseif($request->status === 'approved') from-green-500 to-green-600
                                @else from-red-500 to-red-600 @endif
                                rounded-xl flex items-center justify-center shadow-lg">
                                @if($request->status === 'pending')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($request->status === 'approved')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">
                                    @if($request->leave_type === 'custom')
                                        <span class="text-purple-600">{{ $request->custom_leave_name }}</span>
                                        <div class="text-xs text-gray-500 font-normal">(Custom Leave)</div>
                                    @else
                                        {{ $request->leave_type }}
                                    @endif
                                </h4>
                                <p class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('M d') }} -
                                    {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                    ({{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} day(s))
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($request->reason, 80) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($request->status === 'pending') bg-orange-100 text-orange-800
                                @elseif($request->status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->format('M d, Y') }}</p>
                            @if($request->status === 'approved')
                            <button onclick="openDownloadModal({{ $request->id }})" type="button" class="inline-flex items-center px-3 py-1 border border-orange-600 text-orange-700 text-xs font-semibold rounded-full hover:bg-orange-50 transition mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M8 12l4 4m0 0l4-4m-4 4V4" />
                                </svg>
                                <span class="ml-1">Download</span>
                            </button>
                            @else
                            <span class="text-xs text-gray-400 mt-2">N/A</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- CTO Request History Table -->
            <div x-data="{ open: true }" class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-teal-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-400/10 to-cyan-400/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-teal-900 mb-2">CTO Request History</h3>
                            <p class="text-gray-600">Your CTO work submissions and their status</p>
                        </div>
                        <div class="flex space-x-2">
                            <button @click="open = false" x-show="open" type="button" class="px-3 py-1 bg-teal-100 text-teal-700 rounded-lg text-xs font-semibold shadow hover:bg-teal-200 transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <button @click="open = true" x-show="!open" type="button" class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-lg text-xs font-semibold shadow hover:bg-cyan-200 transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto overflow-y-visible" x-show="open" x-transition>
                        <table class="min-w-full divide-y divide-teal-200 rounded-xl overflow-hidden">
                            <thead class="bg-gradient-to-r from-teal-100 to-cyan-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Date Filed</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-teal-700 uppercase tracking-wider">Total Hours Worked</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-teal-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-teal-100">
                                @forelse(($ctoRequests ?? []) as $request)
                                <tr class="hover:bg-teal-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-center">
                                        @php
                                        $hours = $request->total_hours ?? $request->requested_hours;
                                        @endphp
                                        {{ $hours !== null ? number_format($hours, 2) . ' hrs' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                                @if($request->status === 'approved') bg-green-100 text-green-700
                                                @elseif($request->status === 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($request->status === 'denied') bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-xs text-gray-400">N/A</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No CTO requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Charts and Analytics Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Personnel by Category Chart -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Personnel by Category</h3>
                    </div>
                    <div class="space-y-4">
                        @foreach($personnelByCategory as $category => $count)
                        <div class="group p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">{{ $category }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500 ease-out" style="width: {{ $totalPersonnel > 0 ? ($count / $totalPersonnel) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Personnel by Appointment Status -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Appointment Status</h3>
                    </div>
                    <div class="space-y-4">
                        @foreach($personnelByAppointment as $appointment => $count)
                        <div class="group p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">{{ ucfirst($appointment) }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-3 rounded-full transition-all duration-500 ease-out" style="width: {{ $totalPersonnel > 0 ? ($count / $totalPersonnel) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Activities and Alerts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recent Personnel Additions -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Recent Personnel</h3>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentPersonnel as $personnel)
                        <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-indigo-100/50 rounded-xl border border-indigo-200/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($personnel->first_name, 0, 1) }}{{ substr($personnel->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $personnel->first_name }} {{ $personnel->middle_name }} {{ $personnel->last_name }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $personnel->position->title ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full">{{ $personnel->created_at->diffForHumans() }}</span>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-500">No recent personnel additions</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Expiring Contracts Alert -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Expiring Contracts</h3>
                        </div>
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-bold rounded-full">{{ $expiringContracts->count() }}</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($expiringContracts as $personnel)
                        <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100/50 rounded-xl border-l-4 border-red-400 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($personnel->first_name, 0, 1) }}{{ substr($personnel->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $personnel->first_name }} {{ $personnel->middle_name }} {{ $personnel->last_name }}
                                    </p>
                                    <p class="text-sm text-red-600 font-medium">Expires: {{ \Carbon\Carbon::parse($personnel->employment_end)->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('school_personnels.show', ['personnel' => $personnel->id]) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                View
                            </a>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500">No expiring contracts</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Events and Salary Changes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recent Events -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Upcoming Events</h3>
                        </div>
                        <!-- <a href="#" class="text-blue-600 text-sm font-medium hover:text-blue-700 transition-colors duration-200">View All</a> -->
                    </div>
                    <div class="space-y-4">
                        @forelse($recentEvents as $event)
                        <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-teal-50 to-teal-100/50 rounded-xl border border-teal-200/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-teal-100 text-teal-800 text-sm font-medium rounded-full">{{ $event->type }}</span>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500">No upcoming events</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Salary Changes -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Recent Salary Changes</h3>
                        </div>
                        <!-- <a href="#" class="text-blue-600 text-sm font-medium hover:text-blue-700 transition-colors duration-200">View All</a> -->
                    </div>
                    <div class="space-y-4">
                        @forelse($recentSalaryChanges as $change)
                        <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-amber-50 to-amber-100/50 rounded-xl border border-amber-200/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($change->personnel->first_name, 0, 1) }}{{ substr($change->personnel->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $change->personnel->first_name }} {{ $change->personnel->last_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $change->type }} - SG{{ $change->current_salary_grade }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-amber-600 bg-amber-100 px-3 py-1 rounded-full">{{ $change->created_at->diffForHumans() }}</span>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500">No recent salary changes</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Loyalty Award Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- School Head Loyalty Award Status -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Your Loyalty Award Status</h3>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl border border-yellow-200/50">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">Years of Service</p>
                                    <p class="text-sm text-gray-600">{{ $schoolHeadYearsOfService }} years</p>
                                </div>
                                <div class="text-right">
                                    @if($schoolHeadCanClaim)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full">ELIGIBLE</span>
                                    @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-bold rounded-full">NOT ELIGIBLE</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Maximum Claims</p>
                                    <p class="text-sm text-gray-600">Based on years of service</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-blue-600">{{ $schoolHeadMaxClaims }}</span>
                                </div>
                            </div>
                        </div>

                        @if(!$schoolHeadCanClaim)
                        <div class="p-4 bg-gradient-to-r from-orange-50 to-orange-100/50 rounded-xl border border-orange-200/50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Next Award Year</p>
                                    <p class="text-sm text-gray-600">When you'll be eligible</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-orange-600">{{ $schoolHeadNextAwardYear }} years</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- School Personnel Loyalty Awards -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">School Personnel Loyalty Awards</h3>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full">{{ $eligiblePersonnelCount }} Eligible</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($schoolPersonnelLoyalty->where('is_eligible', true)->take(5) as $loyaltyInfo)
                        <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100/50 rounded-xl border border-green-200/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($loyaltyInfo['personnel']->first_name, 0, 1) }}{{ substr($loyaltyInfo['personnel']->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $loyaltyInfo['personnel']->first_name }} {{ $loyaltyInfo['personnel']->middle_name }} {{ $loyaltyInfo['personnel']->last_name }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $loyaltyInfo['years_of_service'] }} years of service</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-green-600">{{ $loyaltyInfo['max_claims'] }} claims</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-500">No eligible personnel for loyalty awards</p>
                        </div>
                        @endforelse
                    </div>
                    @if($eligiblePersonnelCount > 5)
                    <div class="mt-6 text-center">
                        <a href="#" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-semibold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            View all {{ $eligiblePersonnelCount }} eligible personnel
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
        <div id="downloadModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
                <button onclick="closeDownloadModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Download Leave Application</h3>
                <p class="text-gray-600 mb-6">Choose the signature type for your leave application:</p>
                <div class="space-y-3">
                    <a id="downloadAssistant" href="#" class="block w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center font-medium">
                        Assistant SDS
                        <p class="text-sm opacity-90">For Assistant School Division Superintendent</p>
                    </a>
                    <a id="downloadSchools" href="#" class="block w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center font-medium">
                        Schools SDS
                        <p class="text-sm opacity-90">For Schools Division Superintendent</p>
                    </a>
                </div>
            </div>
        </div>

    <!-- JavaScript for Leave History Minimize Feature -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Minimize functionality for leave history section
            var historyHeaderToggle = document.getElementById('historyHeaderToggle');
            var historyToggleIcon = document.getElementById('historyToggleIcon');
            var leaveHistoryContent = document.getElementById('leaveHistoryContent');
            var isHistoryMinimized = localStorage.getItem('leaveHistoryMinimized') === 'true';

            // Set initial state based on localStorage
            if (isHistoryMinimized && historyHeaderToggle && leaveHistoryContent) {
                leaveHistoryContent.style.height = '0';
                leaveHistoryContent.style.overflow = 'hidden';
                leaveHistoryContent.style.opacity = '0';
                historyToggleIcon.style.transform = 'rotate(-90deg)';
            }

            if (historyHeaderToggle && leaveHistoryContent) {
                historyHeaderToggle.addEventListener('click', function() {
                    if (isHistoryMinimized) {
                        // Expand
                        leaveHistoryContent.style.height = 'auto';
                        leaveHistoryContent.style.overflow = 'visible';
                        leaveHistoryContent.style.opacity = '1';
                        historyToggleIcon.style.transform = 'rotate(0deg)';
                        localStorage.setItem('leaveHistoryMinimized', 'false');
                    } else {
                        // Minimize
                        leaveHistoryContent.style.height = '0';
                        leaveHistoryContent.style.overflow = 'hidden';
                        leaveHistoryContent.style.opacity = '0';
                        historyToggleIcon.style.transform = 'rotate(-90deg)';
                        localStorage.setItem('leaveHistoryMinimized', 'true');
                    }
                    isHistoryMinimized = !isHistoryMinimized;
                });
            }

            // CTO History toggle functionality
            var ctoHistoryHeaderToggle = document.getElementById('ctoHistoryHeaderToggle');
            var ctoHistoryToggleIcon = document.getElementById('ctoHistoryToggleIcon');
            var ctoHistoryContent = document.getElementById('ctoHistoryContent');
            var isCtoHistoryMinimized = localStorage.getItem('ctoHistoryMinimized') === 'true';

            // Set initial state based on localStorage for CTO history
            if (isCtoHistoryMinimized && ctoHistoryHeaderToggle && ctoHistoryContent) {
                ctoHistoryContent.style.height = '0';
                ctoHistoryContent.style.overflow = 'hidden';
                ctoHistoryContent.style.opacity = '0';
                ctoHistoryToggleIcon.style.transform = 'rotate(-90deg)';
            }

            if (ctoHistoryHeaderToggle && ctoHistoryContent) {
                ctoHistoryHeaderToggle.addEventListener('click', function() {
                    if (isCtoHistoryMinimized) {
                        // Expand
                        ctoHistoryContent.style.height = 'auto';
                        ctoHistoryContent.style.overflow = 'visible';
                        ctoHistoryContent.style.opacity = '1';
                        ctoHistoryToggleIcon.style.transform = 'rotate(0deg)';
                        localStorage.setItem('ctoHistoryMinimized', 'false');
                    } else {
                        // Minimize
                        ctoHistoryContent.style.height = '0';
                        ctoHistoryContent.style.overflow = 'hidden';
                        ctoHistoryContent.style.opacity = '0';
                        ctoHistoryToggleIcon.style.transform = 'rotate(-90deg)';
                        localStorage.setItem('ctoHistoryMinimized', 'true');
                    }
                    isCtoHistoryMinimized = !isCtoHistoryMinimized;
                });
            }

            // Download modal function for leave requests
            window.openDownloadModal = function(leaveRequestId) {
                const modal = document.getElementById('downloadModal');
                const assistantLink = document.getElementById('downloadAssistant');
                const schoolsLink = document.getElementById('downloadSchools');

                assistantLink.href = `/leave-application/download/${leaveRequestId}/assistant`;
                schoolsLink.href = `/leave-application/download/${leaveRequestId}/schools`;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            window.closeDownloadModal = function() {
                const modal = document.getElementById('downloadModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            // Close modal when clicking outside
            document.getElementById('downloadModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDownloadModal();
                }
            });
        });
    </script>
</x-app-layout>
