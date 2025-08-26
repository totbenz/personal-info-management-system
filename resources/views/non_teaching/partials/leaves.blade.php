@php
    // Color mapping extended to include Personal Leave
    $colors = [
        'Vacation Leave' => 'blue',
        'Personal Leave' => 'blue',
        'Sick Leave' => 'emerald',
        'Special Privilege Leave' => 'purple',
        'Force Leave' => 'orange',
        'Compensatory Time Off' => 'teal',
        'Maternity Leave' => 'pink',
        'Rehabilitation Leave' => 'red',
        'Solo Parent Leave' => 'amber',
        'Study Leave' => 'indigo',
    ];

    $baseLeaveData = $leaveData ?? [];

    $userSex = Auth::user()->personnel->sex ?? null;
    $filteredLeaveData = array_filter($baseLeaveData, function($leave) use ($userSex) {
        return !($leave['type'] === 'Maternity Leave' && $userSex === 'male');
    });

    $leaveBalances = [];
    foreach($filteredLeaveData as $leave) {
        $leaveBalances[$leave['type']] = $leave['available'];
    }
@endphp

<div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div id="leavesHeaderToggle" class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 rounded-lg p-2 -m-2 transition-colors duration-200 group" title="Click to toggle section">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">Available Leaves ({{ $year ?? date('Y') }})</h3>
            <svg id="leavesToggleIcon" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="flex items-center space-x-2">
            <button id="ctoRequestBtn" class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="Request Compensatory Time Off">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <button id="leaveRequestBtn" class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="File a Leave Request">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3h-6m8 5a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2h10z" />
                </svg>
            </button>
        </div>
    </div>
    <div id="leavesContent" class="transition-all duration-300">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($filteredLeaveData as $leave)
            <div class="group flex flex-col justify-between p-4 bg-gradient-to-br from-{{ $colors[$leave['type']] ?? 'gray' }}-50 to-{{ $colors[$leave['type']] ?? 'gray' }}-100/50 rounded-xl border border-{{ $colors[$leave['type']] ?? 'gray' }}-200/50 hover:shadow-md transition-all duration-200">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-{{ $colors[$leave['type']] ?? 'gray' }}-700">{{ $leave['type'] }}</p>
                        <div class="flex items-center space-x-2">
                            @if(in_array($leave['type'], ['Vacation Leave', 'Sick Leave']))
                                <button class="addLeaveBtn w-6 h-6 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" 
                                        data-leave-type="{{ $leave['type'] }}" 
                                        data-current-available="{{ $leave['available'] }}"
                                        title="Add {{ $leave['type'] }} days">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            @endif
                            @if($leave['available'] <= 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">No Days</span>
                            @elseif($leave['available'] <= 3)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Low</span>
                            @endif
                        </div>
                    </div>
                    <p class="text-lg font-bold text-gray-900">Available: {{ (int) $leave['available'] }} / {{ $leave['type'] === 'Compensatory Time Off' ? (int) ($leave['ctos_earned'] ?? $leave['available']) : (int) $leave['max'] }}</p>
                    <p class="text-sm text-gray-600">Used: {{ (int) $leave['used'] }}</p>
                    @if(isset($leave['ctos_earned']) && $leave['ctos_earned'])
                        <p class="text-sm text-teal-600">CTO Earned: {{ $leave['ctos_earned'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if(isset($accrualSummary) && $accrualSummary)
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg border border-blue-200/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Automatic Leave Accrual System</h4>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Years of Service</p>
                    <p class="text-lg font-bold text-blue-600">{{ $accrualSummary['years_of_service'] }} years</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg p-4 border border-blue-200/30">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">Employment</h5>
                            <p class="text-sm text-gray-600">Started {{ $accrualSummary['employment_start'] }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-700">
                        <p><strong>{{ $accrualSummary['years_of_service'] }}</strong> years of service</p>
                        <p><strong>{{ $accrualSummary['months_in_current_year'] }}</strong> eligible months in {{ $year }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-200/30">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">Monthly Accrual</h5>
                            <p class="text-sm text-gray-600">{{ $accrualSummary['monthly_rate'] }} days per month</p>
                        </div>
                    </div>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-700">This year:</span>
                            <span class="font-semibold text-purple-600">+{{ number_format($accrualSummary['months_in_current_year'] * $accrualSummary['monthly_rate'], 2) }} days</span>
                        </div>
                        <div class="text-xs text-gray-500">{{ $accrualSummary['months_in_current_year'] }} months × {{ $accrualSummary['monthly_rate'] }}</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-200/30">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">Yearly Bonus</h5>
                            <p class="text-sm text-gray-600">{{ $accrualSummary['yearly_bonus'] }} days per year</p>
                        </div>
                    </div>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Total earned:</span>
                            <span class="font-semibold text-yellow-600">+{{ number_format($accrualSummary['completed_years_by_year_end'] * $accrualSummary['yearly_bonus'], 0) }} days</span>
                        </div>
                        <div class="text-xs text-gray-500">{{ $accrualSummary['completed_years_by_year_end'] }} years × {{ $accrualSummary['yearly_bonus'] }}</div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-100 border border-blue-300 rounded-lg p-4">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Current Year Calculation:</p>
                            <p>Your vacation and sick leave are automatically calculated based on:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1 text-xs">
                                <li>Base amount: 15 days each</li>
                                <li>Monthly accrual: 1.25 days per month worked</li>
                                <li>Yearly bonus: 15 days per completed year of service</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-green-100 border border-green-300 rounded-lg p-4">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-green-800">
                            <p class="font-medium mb-1">Real-time Updates:</p>
                            <p>Your leave balances update automatically each time you view the dashboard, reflecting:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1 text-xs">
                                <li>Current month progression</li>
                                <li>Years of service milestones</li>
                                <li>Leave usage deductions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @if(isset($ctoBalance) && !empty($ctoBalance['entries']))
            <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200/50 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            CTO Entries Details
                        </h4>
                        <p class="text-sm text-gray-600">Your earned compensatory time off with expiration dates</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Available</p>
                        <p class="text-2xl font-bold text-teal-600">{{ number_format($ctoBalance['total_available'], 1) }} days</p>
                    </div>
                </div>
                @if(($ctoBalance['expired_days'] ?? 0) > 0)
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <span class="text-sm text-red-800"><strong>{{ number_format($ctoBalance['expired_days'], 1) }} days</strong> of CTO have expired and are no longer available.</span>
                    </div>
                </div>
                @endif
                <div class="space-y-3">
                    @foreach($ctoBalance['entries'] as $entry)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($entry['days_remaining'], 1) }} days remaining
                                            <span class="text-gray-500">(of {{ number_format($entry['days_earned'], 1) }} earned)</span>
                                        </p>
                                        <p class="text-xs text-gray-600">Earned: {{ \Carbon\Carbon::parse($entry['earned_date'])->format('M d, Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">Expires: {{ \Carbon\Carbon::parse($entry['expiry_date'])->format('M d, Y') }}</p>
                                        @php $daysUntilExpiry = $entry['days_until_expiry']; @endphp
                                        @if($daysUntilExpiry < 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Expired</span>
                                        @elseif($daysUntilExpiry <= 30)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $daysUntilExpiry }} days left</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $daysUntilExpiry }} days left</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">CTO Usage Policy:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>CTO days expire 1 year after they are earned</li>
                                <li>The oldest earned days are used first (FIFO)</li>
                                <li>Expired CTO days are automatically removed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Leave Request Modal -->
    <div id="leaveRequestModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeLeaveRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">File a Leave Request</h3>
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">{{ session('success') }}</div>
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
                        @foreach($filteredLeaveData as $leave)
                            @if($leave['available'] > 0)
                                <option value="{{ $leave['type'] }}" data-available="{{ $leave['available'] }}">{{ $leave['type'] }} ({{ $leave['available'] }} days available)</option>
                            @else
                                <option value="{{ $leave['type'] }}" disabled class="text-gray-400" data-available="0">{{ $leave['type'] }} (No days available)</option>
                            @endif
                        @endforeach
                    </select>
                    <div id="leave_type_warning" class="hidden text-red-500 text-xs mt-1">This leave type has no available days.</div>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <div id="date_warning" class="hidden text-red-500 text-xs mt-1">The selected dates exceed your available leave days.</div>
                    <div id="days_info" class="hidden text-blue-600 text-xs mt-1">Total days: <span id="total_days">0</span></div>
                </div>
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                    <input type="text" name="reason" id="reason" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">File Leave</button>
            </form>
        </div>
    </div>

    <!-- CTO Request Modal -->
    <div id="ctoRequestModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeCtoRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Request Compensatory Time Off</h3>
            @if(session('cto_success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">{{ session('cto_success') }}</div>
            @endif
            @if($errors->has('requested_hours') || $errors->has('work_date') || $errors->has('start_time') || $errors->has('end_time') || $errors->has('reason') || $errors->has('description'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        @if($errors->has('requested_hours'))<li class="text-sm">{{ $errors->first('requested_hours') }}</li>@endif
                        @if($errors->has('work_date'))<li class="text-sm">{{ $errors->first('work_date') }}</li>@endif
                        @if($errors->has('start_time'))<li class="text-sm">{{ $errors->first('start_time') }}</li>@endif
                        @if($errors->has('end_time'))<li class="text-sm">{{ $errors->first('end_time') }}</li>@endif
                        @if($errors->has('reason'))<li class="text-sm">{{ $errors->first('reason') }}</li>@endif
                        @if($errors->has('description'))<li class="text-sm">{{ $errors->first('description') }}</li>@endif
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('cto-request.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="work_date" class="block text-sm font-medium text-gray-700">Date of Work</label>
                    <input type="date" name="work_date" id="work_date" required max="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="cto_start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" name="start_time" id="cto_start_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="cto_end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" name="end_time" id="cto_end_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
                <div>
                    <label for="requested_hours" class="block text-sm font-medium text-gray-700">Hours Worked</label>
                    <input type="number" name="requested_hours" id="requested_hours" min="1" max="24" required readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <div id="cto_hours_info" class="mt-2 p-2 bg-teal-50 border border-teal-200 rounded text-sm text-teal-800 hidden">You will earn <span id="cto_days_earned">0</span> CTO day(s) from <span id="cto_hours_display">0</span> hour(s) of work.</div>
                </div>
                <div>
                    <label for="cto_reason" class="block text-sm font-medium text-gray-700">Reason for Extra Work</label>
                    <textarea name="reason" id="cto_reason" rows="3" required maxlength="500" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div>
                    <label for="cto_description" class="block text-sm font-medium text-gray-700">Additional Details (Optional)</label>
                    <textarea name="description" id="cto_description" rows="2" maxlength="1000" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <button type="submit" id="cto_submit_btn" disabled class="w-full px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-md hover:bg-teal-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition">Submit CTO Request</button>
            </form>
        </div>
    </div>

    <!-- Add Leave Days Modal -->
    <div id="addLeaveModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeAddLeaveModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Add <span id="addLeaveModalTitle">Leave</span> Days</h3>
            @if(session('success') && !session('cto_success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">{{ session('success') }}</div>
            @endif
            @if($errors->has('days_to_add') || $errors->has('reason') || $errors->has('year') || $errors->has('leave_type'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        @if($errors->has('days_to_add'))<li class="text-sm">{{ $errors->first('days_to_add') }}</li>@endif
                        @if($errors->has('reason'))<li class="text-sm">{{ $errors->first('reason') }}</li>@endif
                        @if($errors->has('year'))<li class="text-sm">{{ $errors->first('year') }}</li>@endif
                        @if($errors->has('leave_type'))<li class="text-sm">{{ $errors->first('leave_type') }}</li>@endif
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
            <form method="POST" action="{{ route('non_teaching.leaves.add') }}" class="space-y-4">
                @csrf
                <input type="hidden" id="addLeaveType" name="leave_type" value="">
                <input type="hidden" name="year" value="{{ $year ?? date('Y') }}">
                <div>
                    <label for="days_to_add" class="block text-sm font-medium text-gray-700">Days to Add</label>
                    <input type="number" name="days_to_add" id="days_to_add" min="1" max="365" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Enter number of days">
                </div>
                <div>
                    <label for="add_leave_reason" class="block text-sm font-medium text-gray-700">Reason for Adding Leave</label>
                    <textarea name="reason" id="add_leave_reason" rows="3" required maxlength="255" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g., Earned from overtime, Special allocation, Year-end bonus..."></textarea>
                </div>
                <div id="addLeavePreview" class="hidden mt-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-800">
                    <p class="font-medium">Preview:</p>
                    <p>Current balance: <span id="previewCurrent">0</span> days</p>
                    <p>Adding: <span id="previewAdding">0</span> days</p>
                    <p class="font-bold">New balance: <span id="previewNew">0</span> days</p>
                </div>
                <button type="submit" id="addLeaveSubmitBtn" class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">Add Leave Days</button>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const leaveBalances = @json($leaveBalances);
    const btn = document.getElementById('leaveRequestBtn');
    const modal = document.getElementById('leaveRequestModal');
    const closeBtn = document.getElementById('closeLeaveRequestModal');
    const leaveTypeSelect = document.getElementById('leave_type');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const submitBtn = document.getElementById('submitBtn');
    const dateWarning = document.getElementById('date_warning');
    const daysInfo = document.getElementById('days_info');
    const totalDaysSpan = document.getElementById('total_days');
    const leaveTypeWarning = document.getElementById('leave_type_warning');
    const leavesHeaderToggle = document.getElementById('leavesHeaderToggle');
    const leavesToggleIcon = document.getElementById('leavesToggleIcon');
    const leavesContent = document.getElementById('leavesContent');
    let isLeavesMinimized = localStorage.getItem('nt_leavesMinimized') === 'true';
    if (isLeavesMinimized) {
        leavesContent.style.height = '0';
        leavesContent.style.overflow = 'hidden';
        leavesContent.style.opacity = '0';
        leavesToggleIcon.style.transform = 'rotate(-90deg)';
    }
    if (leavesHeaderToggle && leavesContent) {
        leavesHeaderToggle.addEventListener('click', function() {
            if (isLeavesMinimized) {
                leavesContent.style.height = 'auto';
                leavesContent.style.overflow = 'visible';
                leavesContent.style.opacity = '1';
                leavesToggleIcon.style.transform = 'rotate(0deg)';
                localStorage.setItem('nt_leavesMinimized', 'false');
            } else {
                leavesContent.style.height = '0';
                leavesContent.style.overflow = 'hidden';
                leavesContent.style.opacity = '0';
                leavesToggleIcon.style.transform = 'rotate(-90deg)';
                localStorage.setItem('nt_leavesMinimized', 'true');
            }
            isLeavesMinimized = !isLeavesMinimized;
        });
    }
    if(btn && modal && closeBtn) {
        btn.addEventListener('click', () => { modal.classList.remove('hidden'); modal.classList.add('flex'); });
        closeBtn.addEventListener('click', () => { modal.classList.add('hidden'); modal.classList.remove('flex'); });
        modal.addEventListener('click', e => { if (e.target === modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } });
    }
    // CTO Modal
    const ctoBtn = document.getElementById('ctoRequestBtn');
    const ctoModal = document.getElementById('ctoRequestModal');
    const ctoCloseBtn = document.getElementById('closeCtoRequestModal');
    const ctoStartTime = document.getElementById('cto_start_time');
    const ctoEndTime = document.getElementById('cto_end_time');
    const ctoHoursInput = document.getElementById('requested_hours');
    const ctoHoursInfo = document.getElementById('cto_hours_info');
    const ctoHoursDisplay = document.getElementById('cto_hours_display');
    const ctoDaysEarned = document.getElementById('cto_days_earned');
    const ctoSubmitBtn = document.getElementById('cto_submit_btn');
    if(ctoBtn && ctoModal && ctoCloseBtn) {
        ctoBtn.addEventListener('click', () => { ctoModal.classList.remove('hidden'); ctoModal.classList.add('flex'); });
        ctoCloseBtn.addEventListener('click', () => { ctoModal.classList.add('hidden'); ctoModal.classList.remove('flex'); });
        ctoModal.addEventListener('click', e => { if (e.target === ctoModal) { ctoModal.classList.add('hidden'); ctoModal.classList.remove('flex'); } });
    }
    function calculateCTOHours() {
        if (!ctoStartTime.value || !ctoEndTime.value) { if (ctoHoursInfo) ctoHoursInfo.classList.add('hidden'); if (ctoHoursInput) ctoHoursInput.value=''; if (ctoSubmitBtn) ctoSubmitBtn.disabled=true; return 0; }
        const startTime = new Date('2000-01-01 ' + ctoStartTime.value);
        const endTime = new Date('2000-01-01 ' + ctoEndTime.value);
        if (endTime <= startTime) { if (ctoHoursInfo) ctoHoursInfo.classList.add('hidden'); if (ctoHoursInput) ctoHoursInput.value=''; if (ctoSubmitBtn) ctoSubmitBtn.disabled=true; return 0; }
        const hours = Math.round((endTime - startTime) / (1000*60*60));
        const days = (hours/8).toFixed(2);
        ctoHoursInput.value = hours; ctoHoursDisplay.textContent = hours; ctoDaysEarned.textContent = days; ctoHoursInfo.classList.remove('hidden');
        validateCTOForm();
        return hours;
    }
    function validateCTOForm() {
        const workDate = document.getElementById('work_date');
        const reason = document.getElementById('cto_reason');
        const hours = ctoHoursInput.value;
        const isValid = workDate.value && reason.value.trim().length >= 10 && hours > 0;
        if (ctoSubmitBtn) ctoSubmitBtn.disabled = !isValid;
        return isValid;
    }
    if (ctoStartTime) ctoStartTime.addEventListener('change', calculateCTOHours);
    if (ctoEndTime) ctoEndTime.addEventListener('change', calculateCTOHours);
    const ctoWorkDate = document.getElementById('work_date');
    if (ctoWorkDate) ctoWorkDate.addEventListener('change', validateCTOForm);
    const ctoReason = document.getElementById('cto_reason');
    if (ctoReason) ctoReason.addEventListener('input', validateCTOForm);

    // Add Leave Days Modal
    const addLeaveModal = document.getElementById('addLeaveModal');
    const closeAddLeaveModal = document.getElementById('closeAddLeaveModal');
    const addLeaveBtns = document.querySelectorAll('.addLeaveBtn');
    const addLeaveModalTitle = document.getElementById('addLeaveModalTitle');
    const addLeaveType = document.getElementById('addLeaveType');
    const currentBalance = document.getElementById('currentBalance');
    const daysToAdd = document.getElementById('days_to_add');
    const addLeavePreview = document.getElementById('addLeavePreview');
    const previewCurrent = document.getElementById('previewCurrent');
    const previewAdding = document.getElementById('previewAdding');
    const previewNew = document.getElementById('previewNew');
    addLeaveBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const leaveType = this.getAttribute('data-leave-type');
            const currentAvailable = this.getAttribute('data-current-available');
            addLeaveModalTitle.textContent = leaveType; addLeaveType.value = leaveType; currentBalance.textContent = currentAvailable; previewCurrent.textContent = currentAvailable;
            addLeaveModal.classList.remove('hidden'); addLeaveModal.classList.add('flex');
        });
    });
    if (closeAddLeaveModal) closeAddLeaveModal.addEventListener('click', () => { addLeaveModal.classList.add('hidden'); addLeaveModal.classList.remove('flex'); });
    if (addLeaveModal) addLeaveModal.addEventListener('click', e => { if (e.target === addLeaveModal) { addLeaveModal.classList.add('hidden'); addLeaveModal.classList.remove('flex'); } });
    if (daysToAdd) daysToAdd.addEventListener('input', function() { const adding = parseInt(this.value)||0; const current = parseInt(previewCurrent.textContent)||0; const newTotal = current + adding; previewAdding.textContent=adding; previewNew.textContent=newTotal; if(adding>0){ addLeavePreview.classList.remove('hidden'); } else { addLeavePreview.classList.add('hidden'); } });

    function calculateDays() {
        if (!startDateInput.value || !endDateInput.value) { daysInfo.classList.add('hidden'); return 0; }
        const startDate = new Date(startDateInput.value); const endDate = new Date(endDateInput.value);
        if (endDate < startDate) { daysInfo.classList.add('hidden'); return 0; }
        const daysDiff = Math.ceil((endDate - startDate)/(1000*3600*24)) + 1; totalDaysSpan.textContent = daysDiff; daysInfo.classList.remove('hidden'); return daysDiff;
    }
    function validateLeaveRequest() {
        const selectedLeaveType = leaveTypeSelect.value; const totalDays = calculateDays(); const availableDays = leaveBalances[selectedLeaveType] || 0;
        dateWarning.classList.add('hidden'); leaveTypeWarning.classList.add('hidden'); let isValid = true;
        if (selectedLeaveType && availableDays === 0) { leaveTypeWarning.classList.remove('hidden'); isValid = false; }
        if (selectedLeaveType && totalDays > 0 && totalDays > availableDays) { dateWarning.classList.remove('hidden'); dateWarning.innerHTML = `The selected dates (${totalDays} days) exceed your available ${selectedLeaveType} days (${availableDays} available).`; isValid = false; }
        submitBtn.disabled = !isValid || !selectedLeaveType || totalDays === 0; return isValid;
    }
    if (leaveTypeSelect) leaveTypeSelect.addEventListener('change', validateLeaveRequest);
    if (startDateInput) startDateInput.addEventListener('change', validateLeaveRequest);
    if (endDateInput) endDateInput.addEventListener('change', validateLeaveRequest);
    const form = document.querySelector('#leaveRequestModal form');
    if (form) form.addEventListener('submit', e => { if (!validateLeaveRequest()) { e.preventDefault(); alert('Please fix the validation errors before submitting.'); } });
    // Auto-open modals on validation errors (mirroring school head logic)
    @if($errors->has('requested_hours') || $errors->has('work_date') || $errors->has('start_time') || $errors->has('end_time') || $errors->has('reason') || $errors->has('description'))
        if (ctoModal) { ctoModal.classList.remove('hidden'); ctoModal.classList.add('flex'); }
    @endif
    @if($errors->has('days_to_add') || $errors->has('leave_type'))
        if (addLeaveModal) { addLeaveModal.classList.remove('hidden'); addLeaveModal.classList.add('flex'); }
    @endif
    @if($errors->any() && !($errors->has('requested_hours') || $errors->has('work_date') || $errors->has('start_time') || $errors->has('end_time') || $errors->has('reason') || $errors->has('description') || $errors->has('days_to_add')))
        if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
    @endif
    validateLeaveRequest();
});
</script>