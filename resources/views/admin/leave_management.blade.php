<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Leave Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6">
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

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mb-6">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            @foreach($errors->all() as $error)
                                <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Personnel</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_personnel'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Vacation Days</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_vacation_available'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Sick Days</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_sick_available'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Critical Leave Status</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['no_vacation_count'] + $stats['no_sick_count'] }} Personnel</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Header with filters -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Manage Leave Balances for All Personnel</h3>
                        </div>

                        <!-- Filters -->
                        <div class="mt-3 sm:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <form method="GET" action="{{ route('admin.leave-management') }}" class="flex items-center space-x-2">
                                <div class="flex items-center space-x-2">
                                    <label for="search" class="text-sm font-medium text-gray-700">Search:</label>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                           placeholder="Name..."
                                           class="border-gray-300 rounded-md text-sm w-32">
                                </div>

                                <div class="flex items-center space-x-2">
                                    <label for="role" class="text-sm font-medium text-gray-700">Role:</label>
                                    <select name="role" id="role" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                                        <option value="">All Roles</option>
                                        <option value="school_head" {{ $role == 'school_head' ? 'selected' : '' }}>School Head</option>
                                        <option value="teacher" {{ $role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="non_teaching" {{ $role == 'non_teaching' ? 'selected' : '' }}>Non-Teaching</option>
                                    </select>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <label for="year" class="text-sm font-medium text-gray-700">Year:</label>
                                    <select name="year" id="year-select" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                                        @for($y = 2020; $y <= 2030; $y++)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                    Filter
                                </button>

                                @if(request()->hasAny(['search', 'role', 'year']))
                                    <a href="{{ route('admin.leave-management') }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">
                                        Clear
                                    </a>
                                @endif
                            </form>

                            <!-- Process Accruals Button -->
                            <button id="process-accruals-btn" class="px-3 py-1 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Process Accruals</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if(count($leaveData) > 0)
                    <!-- Leave Data Table -->
                    <div class="overflow-x-auto">
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
                                        School
                                    </th>
                                    @php
                                        // Get all unique leave types from the first personnel
                                        $allLeaveTypes = [];
                                        if(count($leaveData) > 0) {
                                            foreach($leaveData[0]['leaves'] as $leave) {
                                                $allLeaveTypes[] = $leave['type'];
                                            }
                                        }
                                    @endphp
                                    @foreach($allLeaveTypes as $leaveType)
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $leaveType }}
                                        </th>
                                    @endforeach
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($leaveData as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $data['personnel']->first_name }} {{ $data['personnel']->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $data['personnel']->middle_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($data['user']->role === 'school_head') bg-purple-100 text-purple-800
                                                @elseif($data['user']->role === 'teacher') bg-green-100 text-green-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $data['user']->role)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $data['personnel']->school ? $data['personnel']->school->school_name : 'No School Assigned' }}
                                        </td>
                                        @foreach($allLeaveTypes as $leaveType)
                                            @php
                                                $leave = collect($data['leaves'])->firstWhere('type', $leaveType);
                                            @endphp
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <span class="font-semibold">Available:</span> {{ $leave['available'] ?? 0 }} days
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <span class="font-semibold">Used:</span> {{ $leave['used'] ?? 0 }} days
                                                </div>
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="openAddLeaveModal({{ $data['personnel']->id }}, '{{ $data['personnel']->first_name }} {{ $data['personnel']->last_name }}', '{{ ucfirst(str_replace('_', ' ', $data['user']->role)) }}', '{{ $data['personnel']->school ? $data['personnel']->school->school_name : 'No School Assigned' }}')"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 mr-2">
                                                Add Days
                                            </button>
                                            <button onclick="openDeductLeaveModal({{ $data['personnel']->id }}, '{{ $data['personnel']->first_name }} {{ $data['personnel']->last_name }}', '{{ ucfirst(str_replace('_', ' ', $data['user']->role)) }}', '{{ $data['personnel']->school ? $data['personnel']->school->school_name : 'No School Assigned' }}')"
                                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors duration-200">
                                                Deduct Days
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($users->hasPages())
                                {{ $users->links() }}
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $users->firstItem() }}</span>
                                    to
                                    <span class="font-medium">{{ $users->lastItem() }}</span>
                                    of
                                    <span class="font-medium">{{ $users->total() }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                @if($users->hasPages())
                                    {{ $users->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No personnel found</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no personnel in the system for the selected filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Leave Modal -->
    <div id="addLeaveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Leave Days</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="addLeaveForm" method="POST" action="{{ route('admin.leave-management.add') }}">
                    @csrf
                    <input type="hidden" id="modal_personnel_id" name="personnel_id" value="">
                    <input type="hidden" name="year" value="{{ $year }}">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Personnel</label>
                        <div id="modal_personnel_info" class="p-3 bg-gray-50 rounded-md text-sm text-gray-600"></div>
                    </div>

                    <div class="mb-4">
                        <label for="modal_leave_type" class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                        <select id="modal_leave_type" name="leave_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select leave type</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="modal_days_to_add" class="block text-sm font-medium text-gray-700 mb-2">Days to Add</label>
                        <input type="number" id="modal_days_to_add" name="days_to_add" min="1" max="365" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="modal_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason (Optional)</label>
                        <textarea id="modal_reason" name="reason" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Reason for adding leave days..."></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" id="cancelModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add Days
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deduct Leave Modal -->
    <div id="deductLeaveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Deduct Leave Days</h3>
                    <button id="closeDeductModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="deductLeaveForm" method="POST" action="{{ route('admin.leave-management.deduct') }}">
                    @csrf
                    <input type="hidden" id="deduct_modal_personnel_id" name="personnel_id" value="">
                    <input type="hidden" name="year" value="{{ $year }}">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Personnel</label>
                        <div id="deduct_modal_personnel_info" class="p-3 bg-gray-50 rounded-md text-sm text-gray-600"></div>
                    </div>

                    <div class="mb-4">
                        <label for="deduct_modal_leave_type" class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                        <select id="deduct_modal_leave_type" name="leave_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">Select leave type</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="deduct_modal_days_to_deduct" class="block text-sm font-medium text-gray-700 mb-2">Days to Deduct</label>
                        <input type="number" id="deduct_modal_days_to_deduct" name="days_to_deduct" min="0.5" max="365" step="0.5" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        <p class="mt-1 text-xs text-gray-500">Cannot deduct more than available balance</p>
                    </div>

                    <div class="mb-4">
                        <label for="deduct_modal_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                        <textarea id="deduct_modal_reason" name="reason" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                  placeholder="Reason for deducting leave days..."></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" id="cancelDeductModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Deduct Days
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const modal = document.getElementById('addLeaveModal');
            const deductModal = document.getElementById('deductLeaveModal');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelModalBtn = document.getElementById('cancelModal');
            const closeDeductModalBtn = document.getElementById('closeDeductModal');
            const cancelDeductModalBtn = document.getElementById('cancelDeductModal');

            function openAddLeaveModal(personnelId, personnelName, personnelRole, schoolName) {
                document.getElementById('modal_personnel_id').value = personnelId;
                document.getElementById('modal_personnel_info').innerHTML = `
                    <div><strong>${personnelName}</strong></div>
                    <div class="text-gray-500">${personnelRole} - ${schoolName}</div>
                `;

                console.log('Opening modal for personnel:', personnelId);

                // Fetch leave types using the new simple endpoint
                fetch(`/api/personnel/${personnelId}/leave-types?year={{ $year }}`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        const leaveTypeSelect = document.getElementById('modal_leave_type');
                        leaveTypeSelect.innerHTML = '<option value="">Select leave type</option>';

                        if (data.leaveTypes && data.leaveTypes.length > 0) {
                            data.leaveTypes.forEach(type => {
                                const option = document.createElement('option');
                                option.value = type;
                                option.textContent = `${type} (${data.leaves[type].available} days available)`;
                                leaveTypeSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "No leave records found";
                            option.disabled = true;
                            leaveTypeSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                modal.classList.remove('hidden');
            }

            function openDeductLeaveModal(personnelId, personnelName, personnelRole, schoolName) {
                document.getElementById('deduct_modal_personnel_id').value = personnelId;
                document.getElementById('deduct_modal_personnel_info').innerHTML = `
                    <div><strong>${personnelName}</strong></div>
                    <div class="text-gray-500">${personnelRole} - ${schoolName}</div>
                `;

                console.log('Opening deduct modal for personnel:', personnelId);

                // Fetch leave types using the new simple endpoint
                fetch(`/api/personnel/${personnelId}/leave-types?year={{ $year }}`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        const leaveTypeSelect = document.getElementById('deduct_modal_leave_type');
                        leaveTypeSelect.innerHTML = '<option value="">Select leave type</option>';

                        if (data.leaveTypes && data.leaveTypes.length > 0) {
                            data.leaveTypes.forEach(type => {
                                const option = document.createElement('option');
                                option.value = type;
                                option.textContent = `${type} (${data.leaves[type].available} days available)`;
                                leaveTypeSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "No leave records found";
                            option.disabled = true;
                            leaveTypeSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                deductModal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.getElementById('addLeaveForm').reset();
            }

            function closeDeductModal() {
                deductModal.classList.add('hidden');
                document.getElementById('deductLeaveForm').reset();
            }

            // Make functions global so they can be called from onclick
            window.openAddLeaveModal = openAddLeaveModal;
            window.openDeductLeaveModal = openDeductLeaveModal;

            // Event listeners for close buttons
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeModal();
                });
            }

            if (cancelModalBtn) {
                cancelModalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeModal();
                });
            }

            if (closeDeductModalBtn) {
                closeDeductModalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeDeductModal();
                });
            }

            if (cancelDeductModalBtn) {
                cancelDeductModalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeDeductModal();
                });
            }

            // Close modal when clicking outside
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            if (deductModal) {
                deductModal.addEventListener('click', function(e) {
                    if (e.target === deductModal) {
                        closeDeductModal();
                    }
                });
            }

            // Close modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (modal && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                    if (deductModal && !deductModal.classList.contains('hidden')) {
                        closeDeductModal();
                    }
                }
            });

            // Process Accruals Button
            document.getElementById('process-accruals-btn').addEventListener('click', function() {
                if (confirm('Process leave accruals for all personnel? This will update Vacation and Sick leave based on months worked (Sick Leave excluded for teachers) and set fixed allocations for other leave types.')) {
                    const year = document.getElementById('year-select').value;

                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = `
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Processing...</span>
                    `;

                    fetch('/admin/leave-management/process-accruals', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            year: parseInt(year)
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing accruals.');
                    })
                    .finally(() => {
                        // Reset button state
                        this.disabled = false;
                        this.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Process Accruals</span>
                        `;
                    });
                }
            });
        });
    </script>
</x-app-layout>
