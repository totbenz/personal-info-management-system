<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">
                    {{ $personalInfo['full_name'] }}
                </h2>
                <p class="text-sm text-gray-600">Teacher Dashboard - Personal Information</p>
            </div>
        </div>
    </x-slot>

    <!-- Dashboard Content -->
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50/20 to-emerald-50/30">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Personal Information Card -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Personal Information</h3>
                            <p class="text-gray-600">Your basic personal details and contact information</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ substr($personalInfo['full_name'], 0, 2) }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-green-50 to-green-100/50 rounded-xl border border-green-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-700 mb-1">Personnel ID</p>
                                <p class="text-lg font-bold text-gray-900">{{ $personalInfo['personnel_id'] }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700 mb-1">Date of Birth</p>
                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($personalInfo['date_of_birth'])->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl border border-purple-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-700 mb-1">Place of Birth</p>
                                <p class="text-lg font-bold text-gray-900">{{ $personalInfo['place_of_birth'] }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-xl border border-emerald-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-emerald-700 mb-1">Email</p>
                                <p class="text-lg font-bold text-gray-900">{{ $personalInfo['email'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-xl border border-orange-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-orange-700 mb-1">Mobile</p>
                                <p class="text-lg font-bold text-gray-900">{{ $personalInfo['mobile_no'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-red-50 to-red-100/50 rounded-xl border border-red-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-red-700 mb-1">Civil Status</p>
                                <p class="text-lg font-bold text-gray-900">{{ ucfirst($personalInfo['civil_status']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Information Card -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Work Information</h3>
                            <p class="text-gray-600">Your current position, school, and employment details</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                            </svg>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700 mb-1">Position</p>
                                <p class="text-lg font-bold text-gray-900">{{ $workInfo['position'] }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-xl border border-emerald-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-emerald-700 mb-1">School</p>
                                <p class="text-lg font-bold text-gray-900">{{ $workInfo['school'] }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl border border-purple-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-700 mb-1">Job Status</p>
                                <p class="text-lg font-bold text-gray-900">{{ ucfirst($workInfo['job_status']) }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-xl border border-orange-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-orange-700 mb-1">Salary Grade</p>
                                <p class="text-lg font-bold text-gray-900">SG {{ $workInfo['salary_grade'] }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-indigo-50 to-indigo-100/50 rounded-xl border border-indigo-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-indigo-700 mb-1">Employment Start</p>
                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($workInfo['employment_start'])->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-yellow-50 to-yellow-100/50 rounded-xl border border-yellow-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-yellow-700 mb-1">Years of Service</p>
                                <p class="text-lg font-bold text-gray-900">{{ $yearsOfService }} years</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Government Information Card -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-pink-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Government Information</h3>
                            <p class="text-gray-600">Your government-issued identification numbers</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-red-50 to-red-100/50 rounded-xl border border-red-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-red-700 mb-1">TIN</p>
                                <p class="text-lg font-bold text-gray-900">{{ $governmentInfo['tin'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700 mb-1">SSS No.</p>
                                <p class="text-lg font-bold text-gray-900">{{ $governmentInfo['sss_num'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-green-50 to-green-100/50 rounded-xl border border-green-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-700 mb-1">GSIS No.</p>
                                <p class="text-lg font-bold text-gray-900">{{ $governmentInfo['gsis_num'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl border border-purple-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-700 mb-1">PhilHealth</p>
                                <p class="text-lg font-bold text-gray-900">{{ $governmentInfo['philhealth_num'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-xl border border-orange-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-orange-700 mb-1">PAG-IBIG</p>
                                <p class="text-lg font-bold text-gray-900">{{ $governmentInfo['pagibig_num'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Leaves Section -->
            @php
            $colors = [
            'Personal Leave' => 'blue',
            'Sick Leave' => 'emerald',
            'Maternity Leave' => 'pink',
            'Rehabilitation Leave' => 'red',
            'Solo Parent Leave' => 'amber',
            'Study Leave' => 'indigo',
            ];

            // Override: display only the mandated leave set with custom rules
            $userSex = Auth::user()->personnel->sex ?? null;
            $isSoloParent = Auth::user()->personnel->is_solo_parent ?? false;

            $displayLeaves = [];

            // Personal Leave (taken from Service Credit)
            $displayLeaves[] = [
            'type' => 'Personal Leave',
            'available' => null, // null indicates service credit based
            'max' => null,
            'used' => null,
            'source' => 'Service Credit',
            'description' => 'Taken from Service Credit balance.'
            ];

            // Sick Leave (taken from Service Credit)
            $displayLeaves[] = [
            'type' => 'Sick Leave',
            'available' => null,
            'max' => null,
            'used' => null,
            'source' => 'Service Credit',
            'description' => 'Taken from Service Credit balance.'
            ];

            // Maternity Leave (only for female; +15 days if solo parent)
            if ($userSex === 'female') {
            $maternityDays = $isSoloParent ? 120 : 105; // base 105 + 15 if solo parent
            $displayLeaves[] = [
            'type' => 'Maternity Leave',
            'available' => $maternityDays,
            'max' => $maternityDays,
            'used' => 0,
            'description' => $isSoloParent ? '120 days (includes additional 15 days for Solo Parent).' : '105 days standard allocation.'
            ];
            }

            // Rehabilitation Leave – 180 days (in case of accident in line of duty)
            $displayLeaves[] = [
            'type' => 'Rehabilitation Leave',
            'available' => 180,
            'max' => 180,
            'used' => 0,
            'description' => 'Up to 180 days for injury/accident in line of duty.'
            ];

            // Solo Parent Leave – 7 days (only if solo parent)
            if ($isSoloParent) {
            $displayLeaves[] = [
            'type' => 'Solo Parent Leave',
            'available' => 7,
            'max' => 7,
            'used' => 0,
            'description' => '7 days annual leave for solo parents.'
            ];
            }

            // Study Leave – 180 days
            $displayLeaves[] = [
            'type' => 'Study Leave',
            'available' => 180,
            'max' => 180,
            'used' => 0,
            'description' => 'Up to 180 days for study purposes (per policy).'
            ];

            // Fetch current Service Credit balance
            $serviceCreditRecord = \App\Models\TeacherLeave::where('teacher_id', Auth::user()->personnel->id)
            ->where('leave_type', 'Service Credit')
            ->where('year', now()->year)
            ->first();
            $serviceCreditBalance = $serviceCreditRecord?->available ?? 0;

            // Prepare balances for JS (only numeric leaves)
            $leaveBalances = [];
            foreach($displayLeaves as $leave) {
            if (is_numeric($leave['available'])) {
            $leaveBalances[$leave['type']] = $leave['available'];
            }
            }
            // For Sick Leave, use Service Credit balance
            $leaveBalances['Sick Leave'] = $serviceCreditBalance;
            @endphp

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div id="leavesHeaderToggle" class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 rounded-lg p-2 -m-2 transition-colors duration-200 group" title="Click to toggle section">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">Available Leaves ({{ $year }})</h3>
                        <svg id="leavesToggleIcon" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="px-3 py-1 bg-purple-50 border border-purple-200 rounded-md text-xs font-medium text-purple-700" title="Current Service Credit balance used for Personal & Sick Leave">Service Credit: {{ number_format($serviceCreditBalance,2) }} days</div>
                        <button id="serviceCreditRequestBtn" class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="Request Service Credit">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3h-6m8 5a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2h10z" />
                            </svg>
                        </button>
                        <!-- Leave Request Icon Button -->
                        <button id="leaveRequestBtn" class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="File a Leave Request">
                            <!-- Document with Plus Icon -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3h-6m8 5a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2h10z" />
                            </svg>
                        </button>
                        <!-- Leave Monetization Icon Button -->
                        <button id="monetizationBtn" class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="Convert Leave to Cash">
                            <!-- Money Icon -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Service Credit Request Modal -->
                <div id="serviceCreditRequestModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
                        <button id="closeServiceCreditRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Request Service Credit</h3>
                        @if(session('success') && session('success') === 'Service Credit request submitted and pending approval.')
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            {{ session('success') }}
                            @if(session('sc_hours'))
                            <div class="mt-1 text-xs">Logged: {{ number_format(session('sc_hours'),2) }} hour(s) = {{ number_format(session('sc_days'),2) }} day(s)</div>
                            @endif
                        </div>
                        @endif
                        @if($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('service-credit-request.store') }}" class="space-y-4" id="serviceCreditForm">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="work_date" class="block text-sm font-medium text-gray-700">Work Date</label>
                                    <input type="date" name="work_date" id="work_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="flex flex-col justify-end">
                                    <div class="text-xs text-gray-500">Provide your actual work times.</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Morning In</label>
                                    <input type="time" name="morning_in" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Morning Out</label>
                                    <input type="time" name="morning_out" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Afternoon In</label>
                                    <input type="time" name="afternoon_in" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Afternoon Out</label>
                                    <input type="time" name="afternoon_out" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            <div class="p-3 bg-purple-50 border border-purple-200 rounded-md text-xs text-purple-800" id="scSummary">
                                <span class="font-semibold">Summary:</span> <span id="scHours">0</span> hour(s) = <span id="scDays">0</span> day(s)
                            </div>
                            <div>
                                <label for="reason_sc" class="block text-sm font-medium text-gray-700">Reason</label>
                                <input type="text" name="reason" id="reason_sc" maxlength="255" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Reason">
                            </div>
                            <div>
                                <label for="description_sc" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                <textarea name="description" id="description_sc" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Additional details"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition">Submit Service Credit Request</button>
                        </form>
                    </div>
                </div>
                @if($errors->has('time') || $errors->has('error') || session('sc_modal'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var m = document.getElementById('serviceCreditRequestModal');
                        if (m) {
                            m.classList.remove('hidden');
                            m.classList.add('flex');
                        }
                    });
                </script>
                @endif

                <!-- Leave Monetization Modal (hidden by default) -->
                <div id="monetizationModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-lg relative">
                        <button id="closeMonetizationModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Convert Leave to Cash</h3>
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-sm text-yellow-800">Note: You must retain at least 5 days for each leave type. Vacation Leave will be used first.</p>
                        </div>
                        <div id="monetizationError" class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md"></div>
                        <div id="monetizationSuccess" class="hidden mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md"></div>

                        <!-- Current Leave Balances -->
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Current Leave Balances</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Vacation Leave</p>
                                    <p class="font-bold text-green-600" id="vlBalance">0 days</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Sick Leave</p>
                                    <p class="font-bold text-green-600" id="slBalance">0 days</p>
                                    <p class="text-xs text-purple-600 mt-1">(From Service Credit)</p>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <p class="text-sm text-gray-600">Maximum Monetizable</p>
                                <p class="font-bold text-orange-600" id="maxMonetizable">0 days</p>
                                <p class="text-xs text-gray-500 mt-1">Must retain at least 5 days per leave type</p>
                            </div>
                        </div>

                        <form id="monetizationForm" class="space-y-4">
                            @csrf
                            <div>
                                <label for="days_to_monetize" class="block text-sm font-medium text-gray-700">Number of Days to Monetize</label>
                                <input type="number" name="days_to_monetize" id="days_to_monetize" min="1" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Enter number of days">
                                <p class="text-xs text-gray-500 mt-1">Days will be deducted from Vacation Leave first, then Sick Leave if needed.</p>
                            </div>
                            <div>
                                <label for="monetization_reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                <textarea name="reason" id="monetization_reason" rows="3" required maxlength="500"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Please provide a reason for monetization"></textarea>
                            </div>
                            <div id="monetizationPreview" class="hidden mt-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
                                <p class="font-semibold mb-1">Preview:</p>
                                <p id="previewText"></p>
                            </div>
                            <button type="submit" id="monetizationSubmitBtn"
                                class="w-full px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-md hover:bg-orange-700 transition">
                                Submit Monetization Request
                            </button>
                        </form>
                    </div>
                </div>
                <div id="leavesContent" class="transition-all duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($displayLeaves as $leave)
                        <div class="group flex flex-col justify-between p-4 bg-gradient-to-br from-{{ $colors[$leave['type']] ?? 'gray' }}-50 to-{{ $colors[$leave['type']] ?? 'gray' }}-100/50 rounded-xl border border-{{ $colors[$leave['type']] ?? 'gray' }}-200/50 hover:shadow-md transition-all duration-200">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-{{ $colors[$leave['type']] ?? 'gray' }}-700">{{ $leave['type'] }}</p>
                                    <div class="flex items-center space-x-2">
                                        @if($leave['available'] === 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            No Days
                                        </span>
                                        @elseif(is_numeric($leave['available']) && $leave['available'] <= 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Low
                                            </span>
                                            @endif
                                    </div>
                                </div>
                                @if(is_null($leave['available']))
                                <p class="text-lg font-bold text-gray-900">Taken from Service Credit</p>
                                @else
                                <p class="text-lg font-bold text-gray-900">Available: {{ $leave['available'] }} @if($leave['max']) / {{ $leave['max'] }} @endif</p>
                                <p class="text-sm text-gray-600">Used: {{ $leave['used'] ?? 0 }}</p>
                                @endif
                                @if(!empty($leave['description']))
                                <p class="text-xs text-gray-500 italic mt-1">{{ $leave['description'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Leave Request Modal (hidden by default) -->
                <div id="leaveRequestModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
                        <button id="closeLeaveRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">File a Leave Request</h3>
                        @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('leave-request.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="leave_type" class="block text-sm font-medium text-gray-700">Type of Leave</label>
                                <select name="leave_type" id="leave_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select type</option>
                                    @foreach($displayLeaves as $leave)
                                    @if(is_null($leave['available']))
                                    <option value="{{ $leave['type'] }}" data-available="{{ $serviceCreditBalance }}">
                                        {{ $leave['type'] }} ({{ number_format($serviceCreditBalance, 2) }} days from Service Credit)
                                    </option>
                                    @else
                                    <option value="{{ $leave['type'] }}" data-available="{{ $leave['available'] }}">
                                        {{ $leave['type'] }} ({{ $leave['available'] }} days available)
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('leave_type')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                <div id="leave_type_warning" class="hidden text-red-500 text-xs mt-1">
                                    This leave type has no available days.
                                </div>
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('start_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('end_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                <div id="date_warning" class="hidden text-red-500 text-xs mt-1">
                                    The selected dates exceed your available leave days.
                                </div>
                                <div id="days_info" class="hidden text-blue-600 text-xs mt-1">
                                    Total days: <span id="total_days">0</span>
                                </div>
                            </div>
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                <input type="text" name="reason" id="reason" value="{{ old('reason') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('reason')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">File Leave</button>
                        </form>
                    </div>
                </div>

                <!-- Add Leave Days Modal (hidden by default) -->
                <div id="addLeaveModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
                        <button id="closeAddLeaveModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Add <span id="addLeaveModalTitle">Leave</span> Days</h3>
                        @if(session('success') && !session('cto_success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if($errors->has('days_to_add') || $errors->has('reason') || $errors->has('year') || $errors->has('leave_type'))
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <ul class="list-disc list-inside space-y-1">
                                @if($errors->has('days_to_add'))
                                <li class="text-sm">{{ $errors->first('days_to_add') }}</li>
                                @endif
                                @if($errors->has('reason'))
                                <li class="text-sm">{{ $errors->first('reason') }}</li>
                                @endif
                                @if($errors->has('year'))
                                <li class="text-sm">{{ $errors->first('year') }}</li>
                                @endif
                                @if($errors->has('leave_type'))
                                <li class="text-sm">{{ $errors->first('leave_type') }}</li>
                                @endif
                            </ul>
                        </div>
                        @endif

                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">Current Balance: <span id="currentBalance">0</span> days</p>
                                    <p class="text-xs mt-1">Adding leave days will increase your available balance for this leave type.</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('teacher.leaves.add') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" id="addLeaveType" name="leave_type" value="">
                            <input type="hidden" name="year" value="{{ $year ?? date('Y') }}">

                            <div>
                                <label for="days_to_add" class="block text-sm font-medium text-gray-700">Days to Add</label>
                                <input type="number" name="days_to_add" id="days_to_add" min="1" max="365" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    value="{{ old('days_to_add') }}"
                                    placeholder="Enter number of days">
                                <p class="text-xs text-gray-500 mt-1">Enter the number of days you want to add (1-365)</p>
                            </div>

                            <div>
                                <label for="add_leave_reason" class="block text-sm font-medium text-gray-700">Reason for Adding Leave</label>
                                <textarea name="reason" id="add_leave_reason" rows="3" required maxlength="255"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="e.g., Earned from overtime, Special allocation, Year-end bonus...">{{ old('reason') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Briefly explain why you're adding these leave days</p>
                            </div>

                            <div id="addLeavePreview" class="hidden mt-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-800">
                                <p class="font-medium">Preview:</p>
                                <p>Current balance: <span id="previewCurrent">0</span> days</p>
                                <p>Adding: <span id="previewAdding">0</span> days</p>
                                <p class="font-bold">New balance: <span id="previewNew">0</span> days</p>
                            </div>

                            <button type="submit" id="addLeaveSubmitBtn"
                                class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                                Add Leave Days
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Your requested leaves and their status Section -->
            <div x-data="{ open: true }" class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-green-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-400/10 to-emerald-400/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-green-900 mb-2">Leave Request History</h3>
                            <p class="text-gray-600">Your requested leaves and their status</p>
                        </div>
                        <div class="flex space-x-2">
                            <button @click="open = false" x-show="open" type="button" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold shadow hover:bg-green-200 transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <button @click="open = true" x-show="!open" type="button" class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold shadow hover:bg-emerald-200 transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto" x-show="open" x-transition>
                        <table class="min-w-full divide-y divide-green-200 rounded-xl overflow-hidden">
                            <thead class="bg-gradient-to-r from-green-100 to-emerald-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Leave Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Filed Date</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider">Number of Days</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-green-100">
                                @forelse($leaveRequests as $leave)
                                <tr class="hover:bg-green-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-800">{{ $leave->leave_type ?? $leave->type ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $leave->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 text-center">
                                        @if(!empty($leave->start_date) && !empty($leave->end_date))
                                        {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                                @if($leave->status === 'approved') bg-green-100 text-green-700
                                                @elseif($leave->status === 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($leave->status === 'rejected') bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($leave->status === 'approved')
                                        <button onclick="openDownloadModal({{ $leave->id }})" type="button" class="inline-flex items-center px-3 py-1 border border-green-600 text-green-700 text-xs font-semibold rounded-full hover:bg-green-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M8 12l4 4m0 0l4-4m-4 4V4" />
                                            </svg>
                                            <span class="ml-1">Download</span>
                                        </button>
                                        @else
                                        <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No leave requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Service Credit Requests History -->
            @if(isset($serviceCreditRequests) && $serviceCreditRequests->count() > 0)
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-purple-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-indigo-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div id="serviceCreditHistoryHeaderToggle" class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 rounded-lg p-2 -m-2 transition-colors duration-200 group" title="Click to toggle section">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-purple-600 transition-colors duration-200">Recent Service Credit Requests</h3>
                                <p class="text-gray-600">Latest submitted requests and their status</p>
                            </div>
                            <svg id="serviceCreditHistoryToggleIcon" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $serviceCreditRequests->where('status', 'pending')->count() }} Pending
                            </span>
                        </div>
                    </div>
                    <div id="serviceCreditHistoryContent" class="space-y-4 transition-all duration-300">
                        @foreach($serviceCreditRequests as $sc)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r
                        @if($sc->status === 'pending') from-purple-50 to-purple-100/50
                        @elseif($sc->status === 'approved') from-green-50 to-green-100/50
                        @else from-red-50 to-red-100/50 @endif
                        rounded-xl border
                        @if($sc->status === 'pending') border-purple-200/50
                        @elseif($sc->status === 'approved') border-green-200/50
                        @else border-red-200/50 @endif
                        hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br
                                @if($sc->status === 'pending') from-purple-500 to-purple-600
                                @elseif($sc->status === 'approved') from-green-500 to-green-600
                                @else from-red-500 to-red-600 @endif
                                rounded-full flex items-center justify-center text-white">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sc->status === 'pending')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @elseif($sc->status === 'approved')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $sc->work_date?->format('M d, Y') }} • {{ number_format($sc->total_hours,2) }} hrs ({{ number_format($sc->requested_days,2) }} days)</h4>
                                    <p class="text-xs text-gray-600">Reason: {{ Str::limit($sc->reason, 60) }}</p>
                                    <p class="text-[11px] text-gray-500 mt-1">
                                        @if($sc->morning_in && $sc->morning_out)
                                        AM: {{ \Carbon\Carbon::parse($sc->morning_in)->format('g:i A') }} - {{ \Carbon\Carbon::parse($sc->morning_out)->format('g:i A') }}
                                        @endif
                                        @if($sc->afternoon_in && $sc->afternoon_out)
                                        | PM: {{ \Carbon\Carbon::parse($sc->afternoon_in)->format('g:i A') }} - {{ \Carbon\Carbon::parse($sc->afternoon_out)->format('g:i A') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($sc->status === 'pending') bg-purple-100 text-purple-800
                                @elseif($sc->status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($sc->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $sc->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Loyalty Award Information -->
            @if($yearsOfService >= 10)
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/10 to-amber-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Loyalty Award Status</h3>
                            <p class="text-gray-600">Your loyalty award eligibility and claims information</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-yellow-50 to-yellow-100/50 rounded-xl border border-yellow-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-yellow-700 mb-1">Years of Service</p>
                                <p class="text-lg font-bold text-gray-900">{{ $yearsOfService }} years</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-green-50 to-green-100/50 rounded-xl border border-green-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-700 mb-1">Status</p>
                                <p class="text-lg font-bold text-gray-900">{{ $canClaimLoyaltyAward ? 'ELIGIBLE' : 'NOT ELIGIBLE' }}</p>
                            </div>
                        </div>
                        <div class="group flex items-center space-x-4 p-4 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50 hover:shadow-md transition-all duration-200">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700 mb-1">Max Claims</p>
                                <p class="text-lg font-bold text-gray-900">{{ $maxClaims }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Events -->
            @if($recentEvents->count() > 0)
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-500/10 to-cyan-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Upcoming Events</h3>
                            <p class="text-gray-600">Recent and upcoming events in your school</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentEvents as $event)
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
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Salary Changes -->
            @if($recentSalaryChanges->count() > 0)
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Recent Salary Changes</h3>
                            <p class="text-gray-600">Your recent salary adjustments and updates</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentSalaryChanges as $change)
                        <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-amber-50 to-amber-100/50 rounded-xl border border-amber-200/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($change->personnel->first_name, 0, 1) }}{{ substr($change->personnel->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $change->type }}</p>
                                    <p class="text-sm text-gray-600">SG{{ $change->current_salary_grade }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-amber-600 bg-amber-100 px-3 py-1 rounded-full">{{ $change->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- JavaScript for Leave Section Toggle Feature -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Show SweetAlert notifications for session messages
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                @endif

                // Pass leave balances to JavaScript
                const leaveBalances = @json($leaveBalances);

                // Leave request modal elements
                var leaveRequestBtn = document.getElementById('leaveRequestBtn');
                var leaveRequestModal = document.getElementById('leaveRequestModal');
                var closeLeaveRequestModal = document.getElementById('closeLeaveRequestModal');
                var leaveTypeSelect = document.getElementById('leave_type');
                var startDateInput = document.getElementById('start_date');
                var endDateInput = document.getElementById('end_date');
                var submitBtn = document.getElementById('submitBtn');
                var dateWarning = document.getElementById('date_warning');
                var daysInfo = document.getElementById('days_info');
                var totalDaysSpan = document.getElementById('total_days');
                var leaveTypeWarning = document.getElementById('leave_type_warning');

                // Minimize functionality for leaves section
                var leavesHeaderToggle = document.getElementById('leavesHeaderToggle');
                var leavesToggleIcon = document.getElementById('leavesToggleIcon');
                var leavesContent = document.getElementById('leavesContent');
                var isLeavesMinimized = localStorage.getItem('teacherLeavesMinimized') === 'true';

                // Set initial state for leaves section based on localStorage
                if (isLeavesMinimized) {
                    leavesContent.style.height = '0';
                    leavesContent.style.overflow = 'hidden';
                    leavesContent.style.opacity = '0';
                    leavesToggleIcon.style.transform = 'rotate(-90deg)';
                }

                // Leaves section toggle
                if (leavesHeaderToggle && leavesContent) {
                    leavesHeaderToggle.addEventListener('click', function() {
                        if (isLeavesMinimized) {
                            // Expand
                            leavesContent.style.height = 'auto';
                            leavesContent.style.overflow = 'visible';
                            leavesContent.style.opacity = '1';
                            leavesToggleIcon.style.transform = 'rotate(0deg)';
                            localStorage.setItem('teacherLeavesMinimized', 'false');
                        } else {
                            // Minimize
                            leavesContent.style.height = '0';
                            leavesContent.style.overflow = 'hidden';
                            leavesContent.style.opacity = '0';
                            leavesToggleIcon.style.transform = 'rotate(-90deg)';
                            localStorage.setItem('teacherLeavesMinimized', 'true');
                        }
                        isLeavesMinimized = !isLeavesMinimized;
                    });
                }

                // Modal controls
                if (leaveRequestBtn && leaveRequestModal && closeLeaveRequestModal) {
                    leaveRequestBtn.addEventListener('click', function() {
                        leaveRequestModal.classList.remove('hidden');
                        leaveRequestModal.classList.add('flex');
                    });

                    closeLeaveRequestModal.addEventListener('click', function() {
                        leaveRequestModal.classList.add('hidden');
                        leaveRequestModal.classList.remove('flex');
                    });

                    // Close modal when clicking outside
                    leaveRequestModal.addEventListener('click', function(e) {
                        if (e.target === leaveRequestModal) {
                            leaveRequestModal.classList.add('hidden');
                            leaveRequestModal.classList.remove('flex');
                        }
                    });
                }

                // Date calculation function
                function calculateDays() {
                    if (!startDateInput || !endDateInput || !startDateInput.value || !endDateInput.value) {
                        if (daysInfo) daysInfo.classList.add('hidden');
                        return 0;
                    }

                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (endDate < startDate) {
                        if (daysInfo) daysInfo.classList.add('hidden');
                        return 0;
                    }

                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end dates

                    if (totalDaysSpan) totalDaysSpan.textContent = daysDiff;
                    if (daysInfo) daysInfo.classList.remove('hidden');

                    return daysDiff;
                }

                // Validation function
                function validateLeaveRequest() {
                    const selectedLeaveType = leaveTypeSelect ? leaveTypeSelect.value : '';
                    const totalDays = calculateDays();
                    const availableDays = leaveBalances[selectedLeaveType] || 0;

                    // Reset warnings
                    if (dateWarning) dateWarning.classList.add('hidden');
                    if (leaveTypeWarning) leaveTypeWarning.classList.add('hidden');

                    let isValid = true;

                    // For teachers, be more lenient with validation
                    // Only block if Solo Parent Leave is selected but user is not solo parent
                    if (selectedLeaveType === 'Solo Parent Leave' && availableDays === 0) {
                        if (leaveTypeWarning) {
                            leaveTypeWarning.classList.remove('hidden');
                            leaveTypeWarning.innerHTML = 'You are not eligible for Solo Parent Leave.';
                        }
                        isValid = false;
                    }

                    // Check specific limits for limited leave types
                    if (selectedLeaveType && totalDays > 0) {
                        let maxAllowed = null;
                        if (selectedLeaveType === 'Solo Parent Leave' && availableDays > 0) {
                            maxAllowed = 7;
                        } else if (selectedLeaveType === 'Maternity Leave') {
                            maxAllowed = typeof availableDays === 'number' ? availableDays : null;
                        } else if (selectedLeaveType === 'Rehabilitation Leave' || selectedLeaveType === 'Study Leave') {
                            maxAllowed = 180;
                        }

                        if (maxAllowed && totalDays > maxAllowed) {
                            if (dateWarning) {
                                dateWarning.classList.remove('hidden');
                                dateWarning.innerHTML = `The selected dates (${totalDays} days) exceed the maximum allowed for ${selectedLeaveType} (${maxAllowed} days).`;
                            }
                            isValid = false;
                        }
                    }

                    // Enable/disable submit button
                    if (submitBtn) {
                        submitBtn.disabled = !selectedLeaveType || totalDays === 0;
                    }

                    return isValid;
                }

                // Event listeners for validation
                if (leaveTypeSelect) {
                    leaveTypeSelect.addEventListener('change', validateLeaveRequest);
                }

                if (startDateInput) {
                    startDateInput.addEventListener('change', validateLeaveRequest);
                }

                if (endDateInput) {
                    endDateInput.addEventListener('change', validateLeaveRequest);
                }

                // Form submissions are handled normally by Laravel
                // SweetAlert notifications will show based on session messages on page reload

                // Close modals with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        [leaveRequestModal, document.getElementById('serviceCreditRequestModal'), document.getElementById('addLeaveModal')].forEach(modal => {
                            if (modal && !modal.classList.contains('hidden')) {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            }
                        });
                    }
                });

                // Add Leave Modal functionality
                var addLeaveModal = document.getElementById('addLeaveModal');
                var closeAddLeaveModal = document.getElementById('closeAddLeaveModal');
                var addLeaveBtns = document.querySelectorAll('.addLeaveBtn');
                var addLeaveModalTitle = document.getElementById('addLeaveModalTitle');
                var addLeaveType = document.getElementById('addLeaveType');
                var currentBalance = document.getElementById('currentBalance');
                var daysToAdd = document.getElementById('days_to_add');
                var addLeavePreview = document.getElementById('addLeavePreview');
                var previewCurrent = document.getElementById('previewCurrent');
                var previewAdding = document.getElementById('previewAdding');
                var previewNew = document.getElementById('previewNew');

                // Add event listeners for add leave buttons
                addLeaveBtns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var leaveType = this.getAttribute('data-leave-type');
                        var currentAvailable = this.getAttribute('data-current-available');

                        // Set modal content
                        addLeaveModalTitle.textContent = leaveType;
                        addLeaveType.value = leaveType;
                        currentBalance.textContent = currentAvailable;
                        previewCurrent.textContent = currentAvailable;

                        // Show modal
                        addLeaveModal.classList.remove('hidden');
                        addLeaveModal.classList.add('flex');
                    });
                });

                // Close add leave modal
                if (closeAddLeaveModal) {
                    closeAddLeaveModal.addEventListener('click', function() {
                        addLeaveModal.classList.add('hidden');
                        addLeaveModal.classList.remove('flex');
                    });
                }

                // Close modal when clicking outside
                if (addLeaveModal) {
                    addLeaveModal.addEventListener('click', function(e) {
                        if (e.target === addLeaveModal) {
                            addLeaveModal.classList.add('hidden');
                            addLeaveModal.classList.remove('flex');
                        }
                    });
                }

                // Preview calculation for add leave
                if (daysToAdd) {
                    daysToAdd.addEventListener('input', function() {
                        var adding = parseInt(this.value) || 0;
                        var current = parseInt(previewCurrent.textContent) || 0;
                        var newTotal = current + adding;

                        previewAdding.textContent = adding;
                        previewNew.textContent = newTotal;

                        if (adding > 0) {
                            addLeavePreview.classList.remove('hidden');
                        } else {
                            addLeavePreview.classList.add('hidden');
                        }
                    });
                }

                // Service Credit Modal logic & auto-calculation
                (function() {
                    const scBtn = document.getElementById('serviceCreditRequestBtn');
                    const scModal = document.getElementById('serviceCreditRequestModal');
                    const scClose = document.getElementById('closeServiceCreditRequestModal');
                    const form = document.getElementById('serviceCreditForm');
                    const timeInputs = form ? form.querySelectorAll('input[type="time"]') : [];
                    const hoursSpan = document.getElementById('scHours');
                    const daysSpan = document.getElementById('scDays');

                    function parseTime(val) {
                        if (!val) return null;
                        const [h, m] = val.split(':').map(Number);
                        return h * 60 + m;
                    }

                    function diffHours(start, end) {
                        if (start === null || end === null) return 0;
                        const d = (end - start) / 60;
                        return d > 0 ? d : 0;
                    }

                    function recompute() {
                        if (!form) return;
                        const mi = parseTime(form.morning_in.value);
                        const mo = parseTime(form.morning_out.value);
                        const ai = parseTime(form.afternoon_in.value);
                        const ao = parseTime(form.afternoon_out.value);
                        let total = diffHours(mi, mo) + diffHours(ai, ao);
                        // Cap at 16 for safety
                        if (total > 16) total = 16;
                        hoursSpan.textContent = total.toFixed(2);
                        daysSpan.textContent = (total / 8).toFixed(2);
                    }
                    timeInputs.forEach(inp => inp.addEventListener('change', recompute));
                    if (scBtn) {
                        scBtn.addEventListener('click', () => {
                            scModal.classList.remove('hidden');
                            scModal.classList.add('flex');
                            recompute();
                        });
                    }
                    if (scClose) {
                        scClose.addEventListener('click', () => {
                            scModal.classList.add('hidden');
                            scModal.classList.remove('flex');
                        });
                    }

                    // Close modal when clicking outside
                    if (scModal) {
                        scModal.addEventListener('click', function(e) {
                            if (e.target === scModal) {
                                scModal.classList.add('hidden');
                                scModal.classList.remove('flex');
                            }
                        });
                    }
                })();

                // Monetization Modal JavaScript
                (function() {
                    const monetizationBtn = document.getElementById('monetizationBtn');
                    const monetizationModal = document.getElementById('monetizationModal');
                    const closeMonetizationModal = document.getElementById('closeMonetizationModal');
                    const monetizationForm = document.getElementById('monetizationForm');
                    const daysInput = document.getElementById('days_to_monetize');
                    const errorDiv = document.getElementById('monetizationError');
                    const successDiv = document.getElementById('monetizationSuccess');
                    const previewDiv = document.getElementById('monetizationPreview');
                    const previewText = document.getElementById('previewText');

                    // Get leave balances from the page data
                    const vlAvailable = leaveBalances['Vacation Leave'] || 0;
                    const slAvailable = leaveBalances['Sick Leave'] || 0;

                    // Calculate maximum monetizable days (available - 5 buffer)
                    const maxMonetizableDays = Math.max(0, vlAvailable - 5) + Math.max(0, slAvailable - 5);

                    // Open monetization modal
                    if (monetizationBtn) {
                        monetizationBtn.addEventListener('click', function() {
                            // Update UI with current balances
                            document.getElementById('vlBalance').textContent = vlAvailable + ' days';
                            document.getElementById('slBalance').textContent = slAvailable + ' days';
                            document.getElementById('maxMonetizable').textContent = maxMonetizableDays + ' days';

                            // Set max attribute for input
                            daysInput.max = maxMonetizableDays;

                            monetizationModal.classList.remove('hidden');
                            monetizationModal.classList.add('flex');
                        });
                    }

                    // Close monetization modal
                    if (closeMonetizationModal) {
                        closeMonetizationModal.addEventListener('click', function() {
                            monetizationModal.classList.add('hidden');
                            monetizationModal.classList.remove('flex');
                            resetMonetizationForm();
                        });
                    }

                    // Close modal when clicking outside
                    if (monetizationModal) {
                        monetizationModal.addEventListener('click', function(e) {
                            if (e.target === monetizationModal) {
                                monetizationModal.classList.add('hidden');
                                monetizationModal.classList.remove('flex');
                                resetMonetizationForm();
                            }
                        });
                    }

                    // Handle input changes
                    if (daysInput) {
                        daysInput.addEventListener('input', function() {
                            const days = parseInt(this.value) || 0;

                            // Clear previous messages
                            hideError();
                            hideSuccess();
                            previewDiv.classList.add('hidden');

                            if (days > 0 && days <= maxMonetizableDays) {
                                showPreview(days);
                            } else if (days > maxMonetizableDays) {
                                showError(`Invalid amount. You can only monetize up to ${maxMonetizableDays} days. You must retain at least 5 days for each leave type.`);
                            }
                        });
                    }

                    // Show preview of monetization distribution
                    function showPreview(days) {
                        let remainingDays = days;
                        let vlUsed = 0;
                        let slUsed = 0;

                        // Use VL first (minus 5 buffer)
                        const availableVL = Math.max(0, vlAvailable - 5);
                        vlUsed = Math.min(availableVL, remainingDays);
                        remainingDays -= vlUsed;

                        // Use SL if needed (minus 5 buffer)
                        if (remainingDays > 0) {
                            const availableSL = Math.max(0, slAvailable - 5);
                            slUsed = Math.min(availableSL, remainingDays);
                            remainingDays -= slUsed;
                        }

                        let preview = `VL: ${vlUsed} day(s), SL: ${slUsed} day(s)`;
                        if (remainingDays > 0) {
                            preview += ` (Warning: ${remainingDays} day(s) cannot be processed)`;
                        }

                        previewText.textContent = preview;
                        previewDiv.classList.remove('hidden');
                    }

                    // Handle form submission
                    if (monetizationForm) {
                        monetizationForm.addEventListener('submit', function(e) {
                            e.preventDefault();

                            const formData = new FormData(this);
                            const days = parseInt(formData.get('days_to_monetize'));

                            // Add leave balances to the form data
                            formData.append('vl_available', vlAvailable);
                            formData.append('sl_available', slAvailable);

                            if (days > maxMonetizableDays) {
                                showError(`Invalid amount. You can only monetize up to ${maxMonetizableDays} days.`);
                                return;
                            }

                            // Disable submit button
                            const submitBtn = document.getElementById('monetizationSubmitBtn');
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Submitting...';

                            fetch('/teacher/monetization', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                // Check if response is JSON
                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    return response.json();
                                } else {
                                    // If not JSON, it's likely a redirect (validation error)
                                    return response.text().then(html => {
                                        // Try to extract error message from HTML if possible
                                        throw new Error('Validation failed. Please check your input and try again.');
                                    });
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    showSuccess(data.message);
                                    setTimeout(() => {
                                        monetizationModal.classList.add('hidden');
                                        monetizationModal.classList.remove('flex');
                                        resetMonetizationForm();
                                        // Optionally refresh the page or update UI
                                        location.reload();
                                    }, 2000);
                                } else {
                                    showError(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error submitting monetization:', error);
                                showError(error.message || 'An error occurred while submitting your request. Please try again.');
                            })
                            .finally(() => {
                                submitBtn.disabled = false;
                                submitBtn.textContent = 'Submit Monetization Request';
                            });
                        });
                    }

                    function showError(message) {
                        errorDiv.textContent = message;
                        errorDiv.classList.remove('hidden');
                    }

                    function hideError() {
                        errorDiv.classList.add('hidden');
                    }

                    function showSuccess(message) {
                        successDiv.textContent = message;
                        successDiv.classList.remove('hidden');
                    }

                    function hideSuccess() {
                        successDiv.classList.add('hidden');
                    }

                    function resetMonetizationForm() {
                        monetizationForm.reset();
                        hideError();
                        hideSuccess();
                        previewDiv.classList.add('hidden');
                    }
                })();

                // Initial validation
                validateLeaveRequest();
            });
        </script>

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

        <script>
            function openDownloadModal(leaveId) {
                const modal = document.getElementById('downloadModal');
                const assistantLink = document.getElementById('downloadAssistant');
                const schoolsLink = document.getElementById('downloadSchools');

                assistantLink.href = `/leave-application/download/${leaveId}/assistant`;
                schoolsLink.href = `/leave-application/download/${leaveId}/schools`;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeDownloadModal() {
                const modal = document.getElementById('downloadModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Close modal when clicking outside
            document.getElementById('downloadModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDownloadModal();
                }
            });
        </script>

</x-app-layout>
