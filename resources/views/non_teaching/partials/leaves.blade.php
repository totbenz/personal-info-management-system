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
        'Paternity Leave' => 'fuchsia',
        'VAWC Leave' => 'rose',
        'Special Leave Benefits for Women' => 'pink',
        'Calamity Leave' => 'yellow',
        'Adoption Leave' => 'cyan',
    ];

    $baseLeaveData = $leaveData ?? [];

    $userSex = Auth::user()->personnel->sex ?? null;
    $civilStatus = Auth::user()->personnel->civil_status ?? null;
    $isSoloParent = Auth::user()->personnel->is_solo_parent ?? false;
    $filteredLeaveData = array_filter($baseLeaveData, function($leave) use ($userSex, $isSoloParent, $civilStatus) {
        // Remove Compensatory Time Off from leave requests
        if ($leave['type'] === 'Compensatory Time Off') return false;
        // Solo Parent Leave only for solo parents
        if (!$isSoloParent && $leave['type'] === 'Solo Parent Leave') return false;
        // Maternity Leave only for women
        if ($leave['type'] === 'Maternity Leave' && $userSex === 'male') return false;
        // Paternity Leave only for women
        if ($leave['type'] === 'Paternity Leave' && $userSex !== 'female') return false;
        // Special Leave Benefits for Women only for women
        if ($leave['type'] === 'Special Leave Benefits for Women' && $userSex !== 'female') return false;
        // Adoption Leave: 60 days for female and single male, 7 days for male spouse
        if ($leave['type'] === 'Adoption Leave') {
            if ($userSex === 'female') return true;
            if ($userSex === 'male' && $civilStatus === 'single') return true;
            if ($userSex === 'male' && $civilStatus !== 'single') return true;
            return false;
        }
        // VAWC and Calamity Leave visible to all
        return true;
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
            <!-- CTO Request Icon Button (Earn CTO) -->
            <button id="ctoRequestBtn" class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="Add Compensatory Time Off" onclick="document.getElementById('ctoRequestModal').classList.remove('hidden'); document.getElementById('ctoRequestModal').classList.add('flex');">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>

            <!-- Use CTO Icon Button -->
            <button id="useCtoBtn" class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="Request Compensatory Time Off" onclick="document.getElementById('useCtoModal').classList.remove('hidden'); document.getElementById('useCtoModal').classList.add('flex');">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </button>

            <!-- Leave Request Icon Button -->
            <button id="leaveRequestBtn" class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="File a Leave Request" onclick="document.getElementById('leaveRequestModal').classList.remove('hidden'); document.getElementById('leaveRequestModal').classList.add('flex');">
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
                            {{-- @if(in_array($leave['type'], ['Vacation Leave', 'Sick Leave']))
                                <button class="addLeaveBtn w-6 h-6 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200"
                                        data-leave-type="{{ $leave['type'] }}"
                                        data-current-available="{{ $leave['available'] }}"
                                        title="Add {{ $leave['type'] }} days">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                                <button class="deductLeaveBtn w-6 h-6 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200"
                                        data-leave-type="{{ $leave['type'] }}"
                                        data-current-available="{{ $leave['available'] }}"
                                        title="Deduct {{ $leave['type'] }} days">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M18 12H6" />
                                    </svg>
                                </button>
                            @endif --}}
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
                    {{-- Proof requirements for special leaves --}}
                    @if($leave['type'] === 'Paternity Leave')
                        <p class="text-xs text-fuchsia-700 mt-2">Proof required: Birth certificate, medical certificate, marriage contract</p>
                    @elseif($leave['type'] === 'VAWC Leave')
                        <p class="text-xs text-rose-700 mt-2">Proof required: VAWC case documentation</p>
                    @elseif($leave['type'] === 'Special Leave Benefits for Women')
                        <p class="text-xs text-pink-700 mt-2">Proof required: Medical certificate, supporting documents</p>
                    @elseif($leave['type'] === 'Calamity Leave')
                        <p class="text-xs text-yellow-700 mt-2">Proof required: Calamity declaration, supporting documents</p>
                    @elseif($leave['type'] === 'Adoption Leave')
                        <p class="text-xs text-cyan-700 mt-2">Proof required: Adoption papers, court order</p>
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
            <button id="closeLeaveRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700" onclick="document.getElementById('leaveRequestModal').classList.add('hidden'); document.getElementById('leaveRequestModal').classList.remove('flex');">
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
                    <div class="relative">
                        <select name="leave_type" id="leave_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" onchange="handleLeaveTypeChange(this.value)">
                            <option value="">Select type</option>
                            @foreach($filteredLeaveData as $leave)
                                @if($leave['available'] > 0)
                                    <option value="{{ $leave['type'] }}" data-available="{{ $leave['available'] }}">{{ $leave['type'] }} ({{ $leave['available'] }} days available)</option>
                                @else
                                    <option value="{{ $leave['type'] }}" disabled class="text-gray-400" data-available="0">{{ $leave['type'] }} (No days available)</option>
                                @endif
                            @endforeach
                            <option value="custom">Custom Leave</option>
                            <option value="others" data-available="0">Others ▼</option>
                        </select>

                        <!-- Monetization Submenu -->
                        <div id="monetizationSubmenu" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-10 hidden">
                            <a href="{{ route('non_teaching.monetization.history') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-md">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Monetization
                                </span>
                            </a>
                            <button type="button" onclick="closeMonetizationSubmenu()" class="w-full px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-b-md text-left">
                                Cancel
                            </button>
                        </div>
                    </div>
                    @error('leave_type')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    <div id="leave_type_warning" class="hidden text-red-500 text-xs mt-1">This leave type has no available days.</div>
                </div>

                <!-- Custom Leave Name Field (hidden by default) -->
                <div id="customLeaveNameDiv" class="hidden">
                    <label for="custom_leave_name" class="block text-sm font-medium text-gray-700">Custom Leave Type Name</label>
                    <input type="text" name="custom_leave_name" id="custom_leave_name" maxlength="50"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                           placeholder="Enter custom leave type name">
                    @error('custom_leave_name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
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
                    <input type="text" name="reason" id="reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">File Leave</button>
            </form>
        </div>
    </div>

    <!-- Use CTO Modal (hidden by default) -->
    <div id="useCtoModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeUseCtoModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700" onclick="document.getElementById('useCtoModal').classList.add('hidden'); document.getElementById('useCtoModal').classList.remove('flex');">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Request Compensatory Time Off</h3>

            @if(session('cto_usage_success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('cto_usage_success') }}
                </div>
            @endif
            @if($errors->has('cto_usage_error'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    {{ $errors->first('cto_usage_error') }}
                </div>
            @endif

            <div class="mb-4 p-3 bg-cyan-50 border border-cyan-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-cyan-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-cyan-800">
                        <p class="font-medium">Available CTO Balance: <span class="text-lg">{{ number_format($ctoBalance['total_available'] ?? 0, 1) }}</span> days</p>
                        <p class="text-xs mt-1">Use your earned CTO for Sick Leave or Vacation Leave</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('leave-request.store-cto') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="is_cto_based" value="1">

                <div>
                    <label for="cto_leave_type" class="block text-sm font-medium text-gray-700">Use CTO as</label>
                    <select name="cto_leave_type" id="cto_leave_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" onchange="toggleCtoOthersName()">
                        <option value="">Select leave type</option>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Vacation Leave">Vacation Leave</option>
                        <option value="Others">Others</option>
                    </select>
                    @error('cto_leave_type')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- CTO Others Name Field (hidden by default) -->
                <div id="ctoOthersNameDiv" class="hidden">
                    <label for="cto_others_name" class="block text-sm font-medium text-gray-700">Specify Leave Type Name</label>
                    <input type="text" name="cto_others_name" id="cto_others_name" maxlength="50"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                           placeholder="Enter leave type name">
                    @error('cto_others_name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="cto_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="cto_start_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('start_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="cto_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="cto_end_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('end_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    <div id="cto_days_info" class="text-cyan-600 text-xs mt-1 hidden">
                        Total days: <span id="cto_total_days">0</span>
                    </div>
                    <div id="cto_balance_warning" class="text-red-500 text-xs mt-1 hidden">
                        Requested days exceed your available CTO balance.
                    </div>
                </div>

                <div>
                    <label for="use_cto_reason" class="block text-sm font-medium text-gray-700">Reason</label>
                    <textarea name="reason" id="use_cto_reason" rows="2" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Enter reason for using CTO..."></textarea>
                    @error('reason')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <button type="submit" id="useCtoSubmitBtn" class="w-full px-6 py-2 bg-cyan-600 text-white rounded-xl font-semibold hover:bg-cyan-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Submit Compensatory Time Off
                </button>
            </form>
        </div>
    </div>

    <!-- CTO Request Modal -->
    <div id="ctoRequestModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeCtoRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700" onclick="document.getElementById('ctoRequestModal').classList.add('hidden'); document.getElementById('ctoRequestModal').classList.remove('flex');">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Add Compensatory Time Off</h3>
            @if(session('cto_success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">{{ session('cto_success') }}</div>
            @endif
            @if($errors->has('total_hours') || $errors->has('work_date') || $errors->has('morning_in') || $errors->has('morning_out') || $errors->has('afternoon_in') || $errors->has('afternoon_out') || $errors->has('time') || $errors->has('reason') || $errors->has('description'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        @if($errors->has('requested_hours'))<li class="text-sm">{{ $errors->first('requested_hours') }}</li>@endif
                        @if($errors->has('work_date'))<li class="text-sm">{{ $errors->first('work_date') }}</li>@endif
                        @if($errors->has('total_hours'))<li class="text-sm">{{ $errors->first('total_hours') }}</li>@endif
                        @if($errors->has('morning_in'))<li class="text-sm">{{ $errors->first('morning_in') }}</li>@endif
                        @if($errors->has('morning_out'))<li class="text-sm">{{ $errors->first('morning_out') }}</li>@endif
                        @if($errors->has('afternoon_in'))<li class="text-sm">{{ $errors->first('afternoon_in') }}</li>@endif
                        @if($errors->has('afternoon_out'))<li class="text-sm">{{ $errors->first('afternoon_out') }}</li>@endif
                        @if($errors->has('time'))<li class="text-sm">{{ $errors->first('time') }}</li>@endif
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
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Morning Time</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="morning_in" class="block text-xs text-gray-500">Time In</label>
                                    <input type="time" name="morning_in" id="morning_in"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm"
                                           value="{{ old('morning_in') }}">
                                </div>
                                <div>
                                    <label for="morning_out" class="block text-xs text-gray-500">Time Out</label>
                                    <input type="time" name="morning_out" id="morning_out"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm"
                                           value="{{ old('morning_out') }}">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Afternoon Time</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="afternoon_in" class="block text-xs text-gray-500">Time In</label>
                                    <input type="time" name="afternoon_in" id="afternoon_in"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm"
                                           value="{{ old('afternoon_in') }}">
                                </div>
                                <div>
                                    <label for="afternoon_out" class="block text-xs text-gray-500">Time Out</label>
                                    <input type="time" name="afternoon_out" id="afternoon_out"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm"
                                           value="{{ old('afternoon_out') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Provide at least one complete morning or afternoon time in/out pair</p>
                </div>
                <div>
                    <label for="total_hours" class="block text-sm font-medium text-gray-700">Total Hours Worked</label>
                    <input type="number" name="total_hours" id="total_hours" min="0" max="16" step="0.25"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                           value="{{ old('total_hours') }}" readonly>
                    <p class="text-xs text-gray-500 mt-1">Provide at least one complete morning or afternoon time in/out pair for accurate hours calculation</p>
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
                <button type="submit" id="cto_submit_btn" disabled class="w-full px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-md hover:bg-teal-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition">Submit Add Compensatory Time Off</button>
            </form>
        </div>
    </div>

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
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Maximum Monetizable</p>
                    <p class="font-bold text-orange-600" id="maxMonetizable">0 days</p>
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

    <!-- Deduct Leave Days Modal -->
    <div id="deductLeaveModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeDeductLeaveModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Deduct <span id="deductLeaveModalTitle">Leave</span> Days</h3>
            @if(session('success') && !session('cto_success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">{{ session('success') }}</div>
            @endif
            @if($errors->has('days_to_deduct') || $errors->has('reason') || $errors->has('year') || $errors->has('leave_type'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        @if($errors->has('days_to_deduct'))<li class="text-sm">{{ $errors->first('days_to_deduct') }}</li>@endif
                        @if($errors->has('reason'))<li class="text-sm">{{ $errors->first('reason') }}</li>@endif
                        @if($errors->has('year'))<li class="text-sm">{{ $errors->first('year') }}</li>@endif
                        @if($errors->has('leave_type'))<li class="text-sm">{{ $errors->first('leave_type') }}</li>@endif
                    </ul>
                </div>
            @endif
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <div class="text-sm text-red-800">
                        <p class="font-medium">Current Balance: <span id="deductCurrentBalance">0</span> days</p>
                        <p class="text-xs mt-1">Deducting leave days will reduce your available balance for this leave type.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('non_teaching.leaves.deduct') }}" class="space-y-4">
                @csrf
                <input type="hidden" id="deductLeaveType" name="leave_type" value="">
                <input type="hidden" name="year" value="{{ $year ?? date('Y') }}">
                <div>
                    <label for="days_to_deduct" class="block text-sm font-medium text-gray-700">Days to Deduct</label>
                    <input type="number" name="days_to_deduct" id="days_to_deduct" min="0.5" max="365" step="0.5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Enter number of days">
                </div>
                <div>
                    <label for="deduct_leave_reason" class="block text-sm font-medium text-gray-700">Reason for Deducting Leave</label>
                    <textarea name="reason" id="deduct_leave_reason" rows="3" required maxlength="255" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g., Leave without pay, Unauthorized absence, Correction..."></textarea>
                </div>
                <div id="deductLeavePreview" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-800">
                    <p class="font-medium">Preview:</p>
                    <p>Current balance: <span id="deductPreviewCurrent">0</span> days</p>
                    <p>Deducting: <span id="deductPreviewDeducting">0</span> days</p>
                    <p class="font-bold">New balance: <span id="deductPreviewNew">0</span> days</p>
                </div>
                <button type="submit" id="deductLeaveSubmitBtn" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">Deduct Leave Days</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to initialize all modal event listeners
    function initializeModals() {
        // Leave Request Modal
        const leaveRequestBtn = document.getElementById('leaveRequestBtn');
        const leaveRequestModal = document.getElementById('leaveRequestModal');
        const closeLeaveRequestModal = document.getElementById('closeLeaveRequestModal');

        if (leaveRequestBtn && leaveRequestModal) {
            leaveRequestBtn.replaceWith(leaveRequestBtn.cloneNode(true));
            const newBtn = document.getElementById('leaveRequestBtn');

            newBtn.addEventListener('click', function() {
                leaveRequestModal.classList.remove('hidden');
                leaveRequestModal.classList.add('flex');
            });
        }

        if (closeLeaveRequestModal && leaveRequestModal) {
            closeLeaveRequestModal.addEventListener('click', function() {
                leaveRequestModal.classList.add('hidden');
                leaveRequestModal.classList.remove('flex');
            });
        }

        // CTO Modal
        const ctoBtn = document.getElementById('ctoRequestBtn');
        const ctoModal = document.getElementById('ctoRequestModal');
        const ctoCloseBtn = document.getElementById('closeCtoRequestModal');

        if (ctoBtn && ctoModal) {
            ctoBtn.replaceWith(ctoBtn.cloneNode(true));
            const newCtoBtn = document.getElementById('ctoRequestBtn');

            newCtoBtn.addEventListener('click', function() {
                ctoModal.classList.remove('hidden');
                ctoModal.classList.add('flex');
            });
        }

        if (ctoCloseBtn && ctoModal) {
            ctoCloseBtn.addEventListener('click', function() {
                ctoModal.classList.add('hidden');
                ctoModal.classList.remove('flex');
            });
        }
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeModals();
        initializeLeaveModals();

        // Initialize CTO time calculation
        initializeCTOTimeCalculation();

        // Also initialize when Livewire updates
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', () => {
                setTimeout(() => {
                    initializeModals();
                    initializeLeaveModals();
                    initializeCTOTimeCalculation();
                }, 100);
            });
        }

        // Also initialize on page visibility change
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(() => {
                    initializeModals();
                    initializeLeaveModals();
                    initializeCTOTimeCalculation();
                }, 100);
            }
        });
    });

    // Function to initialize CTO time calculation
    function initializeCTOTimeCalculation() {
        const ctoMorningIn = document.getElementById('morning_in');
        const ctoMorningOut = document.getElementById('morning_out');
        const ctoAfternoonIn = document.getElementById('afternoon_in');
        const ctoAfternoonOut = document.getElementById('afternoon_out');
        const ctoWorkDate = document.getElementById('work_date');
        const ctoReason = document.getElementById('cto_reason');

        // Add event listeners for time calculation
        if (ctoMorningIn) {
            ctoMorningIn.addEventListener('input', calculateCTOHours);
            ctoMorningIn.addEventListener('change', calculateCTOHours);
        }
        if (ctoMorningOut) {
            ctoMorningOut.addEventListener('input', calculateCTOHours);
            ctoMorningOut.addEventListener('change', calculateCTOHours);
        }
        if (ctoAfternoonIn) {
            ctoAfternoonIn.addEventListener('input', calculateCTOHours);
            ctoAfternoonIn.addEventListener('change', calculateCTOHours);
        }
        if (ctoAfternoonOut) {
            ctoAfternoonOut.addEventListener('input', calculateCTOHours);
            ctoAfternoonOut.addEventListener('change', calculateCTOHours);
        }
        if (ctoWorkDate) ctoWorkDate.addEventListener('change', validateCTOForm);
        if (ctoReason) ctoReason.addEventListener('input', validateCTOForm);
    }
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

    @if(session('cto_success'))
        Swal.fire({
            icon: 'success',
            title: 'CTO Request Submitted!',
            text: '{{ session('cto_success') }}',
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

    const leaveBalances = @json($leaveBalances);
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

    // CTO Modal variables
    const ctoBtn = document.getElementById('ctoRequestBtn');
    const ctoModal = document.getElementById('ctoRequestModal');
    const ctoCloseBtn = document.getElementById('closeCtoRequestModal');
    const ctoMorningIn = document.getElementById('morning_in');
    const ctoMorningOut = document.getElementById('morning_out');
    const ctoAfternoonIn = document.getElementById('afternoon_in');
    const ctoAfternoonOut = document.getElementById('afternoon_out');
    const ctoTotalHoursInput = document.getElementById('total_hours');
    const ctoHoursInfo = document.getElementById('cto_hours_info');
    const ctoHoursDisplay = document.getElementById('cto_hours_display');
    const ctoDaysEarned = document.getElementById('cto_days_earned');
    const ctoSubmitBtn = document.getElementById('cto_submit_btn');

    if (ctoBtn && ctoModal && ctoCloseBtn) {
        ctoBtn.addEventListener('click', () => {
            ctoModal.classList.remove('hidden');
            ctoModal.classList.add('flex');
            // Re-initialize time calculation when modal opens
            setTimeout(initializeCTOTimeCalculation, 50);
        });
        ctoCloseBtn.addEventListener('click', () => {
            ctoModal.classList.add('hidden');
            ctoModal.classList.remove('flex');
        });
        ctoModal.addEventListener('click', (e) => {
            if (e.target === ctoModal) {
                ctoModal.classList.add('hidden');
                ctoModal.classList.remove('flex');
            }
        });
    }

    function calculateCTOHours() {
        var totalHours = 0;

        // Always calculate morning and afternoon hours separately
        // Calculate morning hours
        if (ctoMorningIn && ctoMorningOut && ctoMorningIn.value && ctoMorningOut.value) {
            const morningStart = new Date('2000-01-01 ' + ctoMorningIn.value);
            const morningEnd = new Date('2000-01-01 ' + ctoMorningOut.value);
            if (morningEnd > morningStart) {
                totalHours += (morningEnd.getTime() - morningStart.getTime()) / (1000 * 60 * 60);
            }
        }

        // Calculate afternoon hours
        if (ctoAfternoonIn && ctoAfternoonOut && ctoAfternoonIn.value && ctoAfternoonOut.value) {
            const afternoonStart = new Date('2000-01-01 ' + ctoAfternoonIn.value);
            const afternoonEnd = new Date('2000-01-01 ' + ctoAfternoonOut.value);
            if (afternoonEnd > afternoonStart) {
                totalHours += (afternoonEnd.getTime() - afternoonStart.getTime()) / (1000 * 60 * 60);
            }
        }

        // Round to 2 decimal places
        totalHours = Math.round(totalHours * 100) / 100;

        if (totalHours <= 0) {
            if (ctoHoursInfo) ctoHoursInfo.classList.add('hidden');
            if (ctoTotalHoursInput) ctoTotalHoursInput.value = '';
            if (ctoSubmitBtn) ctoSubmitBtn.disabled = true;
            return 0;
        }

        const days = (totalHours / 8).toFixed(2);
        if (ctoTotalHoursInput) ctoTotalHoursInput.value = totalHours;
        if (ctoHoursDisplay) ctoHoursDisplay.textContent = totalHours;
        if (ctoDaysEarned) ctoDaysEarned.textContent = days;
        if (ctoHoursInfo) ctoHoursInfo.classList.remove('hidden');
        validateCTOForm();
        return totalHours;
    }

    function validateCTOForm() {
        const workDate = document.getElementById('work_date');
        const reason = document.getElementById('cto_reason');
        const hours = ctoTotalHoursInput ? ctoTotalHoursInput.value : '';
        const isValid = workDate && workDate.value && reason && reason.value.trim().length >= 10 && hours > 0;
        if (ctoSubmitBtn) ctoSubmitBtn.disabled = !isValid;
        return isValid;
    }

    // Event listeners for time calculation are now handled in initializeCTOTimeCalculation()

    // Add Leave Days Modal
    function initializeLeaveModals() {
        // Add Leave Modal functionality
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

        // Add event listeners for add leave buttons
        addLeaveBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const leaveType = this.getAttribute('data-leave-type');
                const currentAvailable = this.getAttribute('data-current-available');
                addLeaveModalTitle.textContent = leaveType;
                addLeaveType.value = leaveType;
                currentBalance.textContent = currentAvailable;
                previewCurrent.textContent = currentAvailable;
                addLeaveModal.classList.remove('hidden');
                addLeaveModal.classList.add('flex');
            });
        });

        if (closeAddLeaveModal) closeAddLeaveModal.addEventListener('click', () => {
            addLeaveModal.classList.add('hidden');
            addLeaveModal.classList.remove('flex');
        });
        if (addLeaveModal) addLeaveModal.addEventListener('click', e => {
            if (e.target === addLeaveModal) {
                addLeaveModal.classList.add('hidden');
                addLeaveModal.classList.remove('flex');
            }
        });

        if (daysToAdd) daysToAdd.addEventListener('input', function() {
            const adding = parseInt(this.value)||0;
            const current = parseInt(previewCurrent.textContent)||0;
            const newTotal = current + adding;
            previewAdding.textContent=adding;
            previewNew.textContent=newTotal;
            if(adding>0){
                addLeavePreview.classList.remove('hidden');
            } else {
                addLeavePreview.classList.add('hidden');
            }
        });

        // Deduct Leave Modal functionality
        const deductLeaveModal = document.getElementById('deductLeaveModal');
        const closeDeductLeaveModal = document.getElementById('closeDeductLeaveModal');
        const deductLeaveBtns = document.querySelectorAll('.deductLeaveBtn');
        const deductLeaveModalTitle = document.getElementById('deductLeaveModalTitle');
        const deductLeaveType = document.getElementById('deductLeaveType');
        const deductCurrentBalance = document.getElementById('deductCurrentBalance');
        const daysToDeduct = document.getElementById('days_to_deduct');
        const deductLeavePreview = document.getElementById('deductLeavePreview');
        const deductPreviewCurrent = document.getElementById('deductPreviewCurrent');
        const deductPreviewDeducting = document.getElementById('deductPreviewDeducting');
        const deductPreviewNew = document.getElementById('deductPreviewNew');

        deductLeaveBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const leaveType = this.getAttribute('data-leave-type');
                const currentAvailable = this.getAttribute('data-current-available');
                deductLeaveModalTitle.textContent = leaveType;
                deductLeaveType.value = leaveType;
                deductCurrentBalance.textContent = currentAvailable;
                deductPreviewCurrent.textContent = currentAvailable;
                deductLeaveModal.classList.remove('hidden');
                deductLeaveModal.classList.add('flex');
            });
        });

        if (closeDeductLeaveModal) closeDeductLeaveModal.addEventListener('click', () => {
            deductLeaveModal.classList.add('hidden');
            deductLeaveModal.classList.remove('flex');
        });
        if (deductLeaveModal) deductLeaveModal.addEventListener('click', e => {
            if (e.target === deductLeaveModal) {
                deductLeaveModal.classList.add('hidden');
                deductLeaveModal.classList.remove('flex');
            }
        });

        if (daysToDeduct) daysToDeduct.addEventListener('input', function() {
            const deducting = parseFloat(this.value)||0;
            const current = parseFloat(deductPreviewCurrent.textContent)||0;
            const newTotal = current - deducting;
            deductPreviewDeducting.textContent=deducting;
            deductPreviewNew.textContent=newTotal;
            if(deducting>0){
                deductLeavePreview.classList.remove('hidden');
            } else {
                deductLeavePreview.classList.add('hidden');
            }
        });
    }


    function calculateDays() {
        if (!startDateInput.value || !endDateInput.value) {
            daysInfo.classList.add('hidden');
            return 0;
        }
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        if (endDate < startDate) {
            daysInfo.classList.add('hidden');
            return 0;
        }
        const daysDiff = Math.ceil((endDate - startDate)/(1000*3600*24)) + 1;
        totalDaysSpan.textContent = daysDiff;
        daysInfo.classList.remove('hidden');
        return daysDiff;
    }

    function validateLeaveRequest() {
        const selectedLeaveType = leaveTypeSelect.value;
        const totalDays = calculateDays();
        const availableDays = leaveBalances[selectedLeaveType] || 0;
        dateWarning.classList.add('hidden');
        leaveTypeWarning.classList.add('hidden');
        let isValid = true;
        if (selectedLeaveType && availableDays === 0) {
            leaveTypeWarning.classList.remove('hidden');
            isValid = false;
        }
        if (selectedLeaveType && totalDays > 0 && totalDays > availableDays) {
            dateWarning.classList.remove('hidden');
            dateWarning.innerHTML = `The selected dates (${totalDays} days) exceed your available ${selectedLeaveType} days (${availableDays} available).`;
            isValid = false;
        }
        submitBtn.disabled = !isValid || !selectedLeaveType || totalDays === 0;
        return isValid;
    }

    if (leaveTypeSelect) leaveTypeSelect.addEventListener('change', validateLeaveRequest);
    if (startDateInput) startDateInput.addEventListener('change', validateLeaveRequest);
    if (endDateInput) endDateInput.addEventListener('change', validateLeaveRequest);

    // Form submissions are handled normally by Laravel
    // SweetAlert notifications will show based on session messages on page reload

    // Form submissions are handled normally by Laravel
    // SweetAlert notifications will show based on session messages on page reload

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            [
                document.getElementById('leaveRequestModal'),
                document.getElementById('ctoRequestModal'),
                document.getElementById('addLeaveModal'),
                document.getElementById('deductLeaveModal'),
                document.getElementById('monetizationModal')
            ].forEach(modal => {
                if (modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }
    });

    // Auto-open modals on validation errors
    @if($errors->any())
        if (document.getElementById('leaveRequestModal')) {
            document.getElementById('leaveRequestModal').classList.remove('hidden');
            document.getElementById('leaveRequestModal').classList.add('flex');
        }
    @endif

    @if($errors->has('total_hours') || $errors->has('work_date') || $errors->has('morning_in') || $errors->has('morning_out') || $errors->has('afternoon_in') || $errors->has('afternoon_out') || $errors->has('time') || $errors->has('reason') || $errors->has('description'))
        if (ctoModal) {
            ctoModal.classList.remove('hidden');
            ctoModal.classList.add('flex');
            // Initialize time calculation when modal auto-opens
            setTimeout(initializeCTOTimeCalculation, 50);
        }
    @endif

    // Auto-open add leave modal if there are add leave validation errors
    @if($errors->has('days_to_add') || $errors->has('error'))
        document.addEventListener('DOMContentLoaded', function() {
            const addLeaveModal = document.getElementById('addLeaveModal');
            if (addLeaveModal) {
                addLeaveModal.classList.remove('hidden');
                addLeaveModal.classList.add('flex');
            }
        });
    @endif

    // Auto-open deduct leave modal if there are deduct leave validation errors
    @if($errors->has('days_to_deduct') || $errors->has('error'))
        document.addEventListener('DOMContentLoaded', function() {
            const deductLeaveModal = document.getElementById('deductLeaveModal');
            if (deductLeaveModal) {
                deductLeaveModal.classList.remove('hidden');
                deductLeaveModal.classList.add('flex');
            }
        });
    @endif

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
        const leaveBalances = @json($leaveBalances);
        console.log('Leave balances:', leaveBalances); // Debug log
        console.log('Available keys:', Object.keys(leaveBalances)); // Debug log

        // Try different possible keys
        const vlAvailable = leaveBalances['Vacation Leave'] ||
                           leaveBalances['VL'] ||
                           leaveBalances['vacation_leave'] || 0;
        const slAvailable = leaveBalances['Sick Leave'] ||
                           leaveBalances['SL'] ||
                           leaveBalances['sick_leave'] || 0;

        console.log('VL Available:', vlAvailable, 'SL Available:', slAvailable); // Debug log

        // Calculate maximum monetizable days (available - 5 buffer)
        const maxMonetizableDays = Math.max(0, vlAvailable - 5) + Math.max(0, slAvailable - 5);

        console.log('Max monetizable days:', maxMonetizableDays); // Debug log

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

                fetch('/non-teaching/monetization', {
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
                        return response.json().then(data => {
                            if (!response.ok) {
                                // Handle validation errors (422)
                                throw new Error(data.message || 'Validation failed');
                            }
                            return data;
                        });
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
</script>

<!-- Additional script to ensure buttons work -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure buttons work even if there are conflicts
        const ctoRequestBtn = document.getElementById('ctoRequestBtn');
        const leaveRequestBtn = document.getElementById('leaveRequestBtn');
        const closeCtoBtn = document.getElementById('closeCtoRequestModal');
        const closeLeaveBtn = document.getElementById('closeLeaveRequestModal');

        // Add Leave Modal buttons
        const addLeaveBtns = document.querySelectorAll('.addLeaveBtn');
        const deductLeaveBtns = document.querySelectorAll('.deductLeaveBtn');
        const closeAddLeaveBtn = document.getElementById('closeAddLeaveModal');
        const closeDeductLeaveBtn = document.getElementById('closeDeductLeaveModal');

        if (ctoRequestBtn) {
            ctoRequestBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const modal = document.getElementById('ctoRequestModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        }

        if (leaveRequestBtn) {
            leaveRequestBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const modal = document.getElementById('leaveRequestModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        }

        // Add Leave button listeners
        addLeaveBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const leaveType = this.getAttribute('data-leave-type');
                const currentAvailable = this.getAttribute('data-current-available');
                const modal = document.getElementById('addLeaveModal');

                if (modal) {
                    // Set modal values
                    const modalTitle = document.getElementById('addLeaveModalTitle');
                    const leaveTypeInput = document.getElementById('addLeaveType');
                    const currentBalanceSpan = document.getElementById('currentBalance');
                    const previewCurrent = document.getElementById('previewCurrent');

                    if (modalTitle) modalTitle.textContent = leaveType;
                    if (leaveTypeInput) leaveTypeInput.value = leaveType;
                    if (currentBalanceSpan) currentBalanceSpan.textContent = currentAvailable;
                    if (previewCurrent) previewCurrent.textContent = currentAvailable;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        });

        // Deduct Leave button listeners
        deductLeaveBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const leaveType = this.getAttribute('data-leave-type');
                const currentAvailable = this.getAttribute('data-current-available');
                const modal = document.getElementById('deductLeaveModal');

                if (modal) {
                    // Set modal values
                    const modalTitle = document.getElementById('deductLeaveModalTitle');
                    const leaveTypeInput = document.getElementById('deductLeaveType');
                    const currentBalanceSpan = document.getElementById('deductCurrentBalance');
                    const previewCurrent = document.getElementById('deductPreviewCurrent');

                    if (modalTitle) modalTitle.textContent = leaveType;
                    if (leaveTypeInput) leaveTypeInput.value = leaveType;
                    if (currentBalanceSpan) currentBalanceSpan.textContent = currentAvailable;
                    if (previewCurrent) previewCurrent.textContent = currentAvailable;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        });

        // Close button listeners for add/deduct modals
        if (closeAddLeaveBtn) {
            closeAddLeaveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const modal = document.getElementById('addLeaveModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }

        if (closeDeductLeaveBtn) {
            closeDeductLeaveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const modal = document.getElementById('deductLeaveModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }

        // Click outside to close for add/deduct modals
        const addLeaveModal = document.getElementById('addLeaveModal');
        const deductLeaveModal = document.getElementById('deductLeaveModal');

        if (addLeaveModal) {
            addLeaveModal.addEventListener('click', function(e) {
                if (e.target === addLeaveModal) {
                    addLeaveModal.classList.add('hidden');
                    addLeaveModal.classList.remove('flex');
                }
            });
        }

        if (deductLeaveModal) {
            deductLeaveModal.addEventListener('click', function(e) {
                if (e.target === deductLeaveModal) {
                    deductLeaveModal.classList.add('hidden');
                    deductLeaveModal.classList.remove('flex');
                }
            });
        }

        if (closeCtoBtn) {
            closeCtoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const modal = document.getElementById('ctoRequestModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }

        if (closeLeaveBtn) {
            closeLeaveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const modal = document.getElementById('leaveRequestModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }

        // Also close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                e.target.classList.add('hidden');
                e.target.classList.remove('flex');
            }
        });
    });

    // Function to handle leave type change
    function handleLeaveTypeChange(value) {
        const submenu = document.getElementById('monetizationSubmenu');
        const customLeaveDiv = document.getElementById('customLeaveNameDiv');
        const reasonField = document.querySelector('textarea[name="reason"]');

        if (value === 'others') {
            submenu.classList.remove('hidden');
            customLeaveDiv.classList.add('hidden');
            // Reset the select to show placeholder
            document.getElementById('leave_type').value = '';
        } else if (value === 'custom') {
            submenu.classList.add('hidden');
            customLeaveDiv.classList.remove('hidden');
            // Make reason field required for custom leave
            reasonField.required = true;
            reasonField.placeholder = 'Please specify the reason for this custom leave...';
        } else {
            submenu.classList.add('hidden');
            customLeaveDiv.classList.add('hidden');
            // Make reason field required for regular leaves
            reasonField.required = true;
            reasonField.placeholder = 'Enter reason for leave...';
        }
    }

    // Function to toggle CTO Others name field
    function toggleCtoOthersName() {
        const ctoLeaveType = document.getElementById('cto_leave_type');
        const ctoOthersNameDiv = document.getElementById('ctoOthersNameDiv');
        const ctoOthersNameInput = document.getElementById('cto_others_name');

        if (ctoLeaveType.value === 'Others') {
            ctoOthersNameDiv.classList.remove('hidden');
            ctoOthersNameInput.required = true;
        } else {
            ctoOthersNameDiv.classList.add('hidden');
            ctoOthersNameInput.required = false;
            ctoOthersNameInput.value = '';
        }
    }

    // Use CTO Modal - Live validation for date selection
    const ctoAvailableBalance = {{ $ctoBalance['total_available'] ?? 0 }};

    function validateUseCtoForm() {
        const startDate = document.getElementById('cto_start_date');
        const endDate = document.getElementById('cto_end_date');
        const daysInfo = document.getElementById('cto_days_info');
        const totalDaysSpan = document.getElementById('cto_total_days');
        const balanceWarning = document.getElementById('cto_balance_warning');
        const submitBtn = document.getElementById('useCtoSubmitBtn');
        const leaveType = document.getElementById('cto_leave_type');
        const reason = document.getElementById('use_cto_reason');

        if (!startDate || !endDate || !startDate.value || !endDate.value) {
            if (daysInfo) daysInfo.classList.add('hidden');
            if (balanceWarning) balanceWarning.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = true;
            return;
        }

        const start = new Date(startDate.value);
        const end = new Date(endDate.value);

        if (end < start) {
            if (daysInfo) daysInfo.classList.add('hidden');
            if (balanceWarning) {
                balanceWarning.textContent = 'End date must be after start date.';
                balanceWarning.classList.remove('hidden');
            }
            if (submitBtn) submitBtn.disabled = true;
            return;
        }

        // Calculate days (inclusive)
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        // Show days info
        if (daysInfo && totalDaysSpan) {
            totalDaysSpan.textContent = diffDays;
            daysInfo.classList.remove('hidden');
        }

        // Check against balance
        if (diffDays > ctoAvailableBalance) {
            if (balanceWarning) {
                balanceWarning.textContent = `Requested ${diffDays} day(s) exceeds your available CTO balance of ${ctoAvailableBalance.toFixed(1)} day(s).`;
                balanceWarning.classList.remove('hidden');
            }
            if (submitBtn) submitBtn.disabled = true;
        } else {
            if (balanceWarning) balanceWarning.classList.add('hidden');
            // Enable submit only if all required fields are filled
            const isValid = leaveType && leaveType.value && reason && reason.value.trim().length > 0;
            if (submitBtn) submitBtn.disabled = !isValid;
        }
    }

    // Attach event listeners for Use CTO form validation
    document.addEventListener('DOMContentLoaded', function() {
        const ctoStartDate = document.getElementById('cto_start_date');
        const ctoEndDate = document.getElementById('cto_end_date');
        const ctoLeaveType = document.getElementById('cto_leave_type');
        const ctoReason = document.getElementById('use_cto_reason');

        if (ctoStartDate) ctoStartDate.addEventListener('change', validateUseCtoForm);
        if (ctoEndDate) ctoEndDate.addEventListener('change', validateUseCtoForm);
        if (ctoLeaveType) ctoLeaveType.addEventListener('change', validateUseCtoForm);
        if (ctoReason) ctoReason.addEventListener('input', validateUseCtoForm);

        // Initial validation
        validateUseCtoForm();
    });

    // Function to close monetization submenu
    function closeMonetizationSubmenu() {
        document.getElementById('monetizationSubmenu').classList.add('hidden');
    }

    // Close submenu when clicking outside
    document.addEventListener('click', function(event) {
        const submenu = document.getElementById('monetizationSubmenu');
        const select = document.getElementById('leave_type');

        if (!select.contains(event.target) && !submenu.contains(event.target)) {
            submenu.classList.add('hidden');
        }
    });
</script>
