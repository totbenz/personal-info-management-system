@php
    $colors = [
        'Vacation Leave' => 'blue',
        'Sick Leave' => 'emerald',
        'Special Privilege Leave' => 'purple',
        'Force Leave' => 'orange',
        'Compensatory Time Off' => 'teal',
        'Maternity Leave' => 'pink',
        'Rehabilitation Leave' => 'red',
        'Solo Parent Leave' => 'amber',
        'Study Leave' => 'indigo',
    ];

    // Get user gender to filter maternity leave for male users
    $userSex = Auth::user()->personnel->sex ?? null;
    
    // Filter leave data to exclude maternity leave for male users
    $filteredLeaveData = array_filter($leaveData, function($leave) use ($userSex) {
        return !($leave['type'] === 'Maternity Leave' && $userSex === 'male');
    });

    // Create an array of leave balances for JavaScript access
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
            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">Available Leaves ({{ $year }})</h3>
            <svg id="leavesToggleIcon" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="flex items-center space-x-2">
            <!-- CTO Request Icon Button -->
            <button id="ctoRequestBtn" class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="Request Compensatory Time Off">
                <!-- Clock Plus Icon -->
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            
            <!-- Leave Request Icon Button -->
            <button id="leaveRequestBtn" class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="File a Leave Request">
                <!-- Document with Plus Icon -->
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
                        @if($leave['available'] <= 0)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                No Days
                            </span>
                        @elseif($leave['available'] <= 3)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Low
                            </span>
                        @endif
                    </div>
                    <p class="text-lg font-bold text-gray-900">Available: {{ $leave['available'] }} / {{ $leave['type'] === 'Compensatory Time Off' ? $leave['ctos_earned'] : $leave['max'] }}</p>
                    <p class="text-sm text-gray-600">Used: {{ $leave['used'] }}</p>
                    @if(isset($leave['ctos_earned']) && $leave['ctos_earned'])
                    <p class="text-sm text-teal-600">CTO Earned: {{ $leave['ctos_earned'] }}</p>
                    @endif
                    <!-- @if($leave['remarks'])
                    <p class="text-xs text-gray-500 italic">{{ $leave['remarks'] }}</p>
                    @endif -->
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTO Details Section -->
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

            @if($ctoBalance['expired_days'] > 0)
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <span class="text-sm text-red-800">
                        <strong>{{ number_format($ctoBalance['expired_days'], 1) }} days</strong> of CTO have expired and are no longer available.
                    </span>
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
                                <p class="text-xs text-gray-600">
                                    Earned: {{ \Carbon\Carbon::parse($entry['earned_date'])->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    Expires: {{ \Carbon\Carbon::parse($entry['expiry_date'])->format('M d, Y') }}
                                </p>
                                @php
                                    $daysUntilExpiry = $entry['days_until_expiry'];
                                @endphp
                                @if($daysUntilExpiry < 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @elseif($daysUntilExpiry <= 30)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $daysUntilExpiry }} days left
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $daysUntilExpiry }} days left
                                    </span>
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
                            <li>When using CTO, the oldest earned days are used first (FIFO)</li>
                            <li>Expired CTO days cannot be used and are automatically removed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
                        @foreach($filteredLeaveData as $leave)
                            @if($leave['available'] > 0)
                                <option value="{{ $leave['type'] }}" data-available="{{ $leave['available'] }}">
                                    {{ $leave['type'] }} ({{ $leave['available'] }} days available)
                                </option>
                            @else
                                <option value="{{ $leave['type'] }}" disabled class="text-gray-400" data-available="0">
                                    {{ $leave['type'] }} (No days available)
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

    <!-- CTO Request Modal (hidden by default) -->
    <div id="ctoRequestModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeCtoRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Request Compensatory Time Off</h3>
            @if(session('cto_success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('cto_success') }}
                </div>
            @endif
            @if($errors->has('cto_error') || $errors->has('requested_hours') || $errors->has('work_date') || $errors->has('start_time') || $errors->has('end_time') || $errors->has('reason') || $errors->has('description'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        @if($errors->has('cto_error'))
                            <li class="text-sm">{{ $errors->first('cto_error') }}</li>
                        @endif
                        @if($errors->has('requested_hours'))
                            <li class="text-sm">{{ $errors->first('requested_hours') }}</li>
                        @endif
                        @if($errors->has('work_date'))
                            <li class="text-sm">{{ $errors->first('work_date') }}</li>
                        @endif
                        @if($errors->has('start_time'))
                            <li class="text-sm">{{ $errors->first('start_time') }}</li>
                        @endif
                        @if($errors->has('end_time'))
                            <li class="text-sm">{{ $errors->first('end_time') }}</li>
                        @endif
                        @if($errors->has('reason'))
                            <li class="text-sm">{{ $errors->first('reason') }}</li>
                        @endif
                        @if($errors->has('description'))
                            <li class="text-sm">{{ $errors->first('description') }}</li>
                        @endif
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('cto-request.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="work_date" class="block text-sm font-medium text-gray-700">Date of Work</label>
                    <input type="date" name="work_date" id="work_date" required max="{{ date('Y-m-d') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                           value="{{ old('work_date') }}">
                    <p class="text-xs text-gray-500 mt-1">The date you performed extra work</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" name="start_time" id="cto_start_time" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                               value="{{ old('start_time') }}">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" name="end_time" id="cto_end_time" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                               value="{{ old('end_time') }}">
                    </div>
                </div>
                
                <div>
                    <label for="requested_hours" class="block text-sm font-medium text-gray-700">Hours Worked</label>
                    <input type="number" name="requested_hours" id="requested_hours" min="1" max="24" required 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                           value="{{ old('requested_hours') }}" readonly>
                    <p class="text-xs text-gray-500 mt-1">Automatically calculated from start and end time</p>
                    <div id="cto_hours_info" class="mt-2 p-2 bg-teal-50 border border-teal-200 rounded text-sm text-teal-800 hidden">
                        You will earn <span id="cto_days_earned">0</span> CTO day(s) from <span id="cto_hours_display">0</span> hour(s) of work.
                    </div>
                </div>
                
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Extra Work</label>
                    <textarea name="reason" id="cto_reason" rows="3" required maxlength="500" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                              placeholder="e.g., Emergency response, Special event, Weekend duties...">{{ old('reason') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Briefly explain why you worked extra hours</p>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Additional Details (Optional)</label>
                    <textarea name="description" id="cto_description" rows="2" maxlength="1000" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                              placeholder="Any additional context or details about the work performed...">{{ old('description') }}</textarea>
                </div>
                
                <button type="submit" id="cto_submit_btn" disabled 
                        class="w-full px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-md hover:bg-teal-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition">
                    Submit CTO Request
                </button>
            </form>
        </div>
    </div>
</div>
<!-- Modal JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pass leave balances to JavaScript
        const leaveBalances = @json($leaveBalances);
        
        var btn = document.getElementById('leaveRequestBtn');
        var modal = document.getElementById('leaveRequestModal');
        var closeBtn = document.getElementById('closeLeaveRequestModal');
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
        var isLeavesMinimized = localStorage.getItem('leavesMinimized') === 'true';

        // Set initial state based on localStorage
        if (isLeavesMinimized) {
            leavesContent.style.height = '0';
            leavesContent.style.overflow = 'hidden';
            leavesContent.style.opacity = '0';
            leavesToggleIcon.style.transform = 'rotate(-90deg)';
        }

        if (leavesHeaderToggle && leavesContent) {
            leavesHeaderToggle.addEventListener('click', function() {
                if (isLeavesMinimized) {
                    // Expand
                    leavesContent.style.height = 'auto';
                    leavesContent.style.overflow = 'visible';
                    leavesContent.style.opacity = '1';
                    leavesToggleIcon.style.transform = 'rotate(0deg)';
                    localStorage.setItem('leavesMinimized', 'false');
                } else {
                    // Minimize
                    leavesContent.style.height = '0';
                    leavesContent.style.overflow = 'hidden';
                    leavesContent.style.opacity = '0';
                    leavesToggleIcon.style.transform = 'rotate(-90deg)';
                    localStorage.setItem('leavesMinimized', 'true');
                }
                isLeavesMinimized = !isLeavesMinimized;
            });
        }

        // Modal controls
        if(btn && modal && closeBtn) {
            btn.addEventListener('click', function() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
            closeBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }

        // CTO Modal controls
        var ctoBtn = document.getElementById('ctoRequestBtn');
        var ctoModal = document.getElementById('ctoRequestModal');
        var ctoCloseBtn = document.getElementById('closeCtoRequestModal');
        var ctoStartTime = document.getElementById('cto_start_time');
        var ctoEndTime = document.getElementById('cto_end_time');
        var ctoHoursInput = document.getElementById('requested_hours');
        var ctoHoursInfo = document.getElementById('cto_hours_info');
        var ctoHoursDisplay = document.getElementById('cto_hours_display');
        var ctoDaysEarned = document.getElementById('cto_days_earned');
        var ctoSubmitBtn = document.getElementById('cto_submit_btn');

        if(ctoBtn && ctoModal && ctoCloseBtn) {
            ctoBtn.addEventListener('click', function() {
                ctoModal.classList.remove('hidden');
                ctoModal.classList.add('flex');
            });

            ctoCloseBtn.addEventListener('click', function() {
                ctoModal.classList.add('hidden');
                ctoModal.classList.remove('flex');
            });

            // Close modal when clicking outside
            ctoModal.addEventListener('click', function(e) {
                if (e.target === ctoModal) {
                    ctoModal.classList.add('hidden');
                    ctoModal.classList.remove('flex');
                }
            });
        }

        // CTO time calculation
        function calculateCTOHours() {
            if (!ctoStartTime || !ctoEndTime || !ctoStartTime.value || !ctoEndTime.value) {
                if (ctoHoursInfo) ctoHoursInfo.classList.add('hidden');
                if (ctoHoursInput) ctoHoursInput.value = '';
                if (ctoSubmitBtn) ctoSubmitBtn.disabled = true;
                return 0;
            }

            const startTime = new Date('2000-01-01 ' + ctoStartTime.value);
            const endTime = new Date('2000-01-01 ' + ctoEndTime.value);
            
            if (endTime <= startTime) {
                if (ctoHoursInfo) ctoHoursInfo.classList.add('hidden');
                if (ctoHoursInput) ctoHoursInput.value = '';
                if (ctoSubmitBtn) ctoSubmitBtn.disabled = true;
                return 0;
            }

            const timeDiff = endTime.getTime() - startTime.getTime();
            const hours = Math.round(timeDiff / (1000 * 60 * 60));
            const days = (hours / 8).toFixed(2);
            
            if (ctoHoursInput) ctoHoursInput.value = hours;
            if (ctoHoursDisplay) ctoHoursDisplay.textContent = hours;
            if (ctoDaysEarned) ctoDaysEarned.textContent = days;
            if (ctoHoursInfo) ctoHoursInfo.classList.remove('hidden');
            
            // Enable submit if all required fields are filled
            validateCTOForm();
            
            return hours;
        }

        // CTO form validation
        function validateCTOForm() {
            const workDate = document.getElementById('work_date');
            const reason = document.getElementById('cto_reason');
            const hours = ctoHoursInput ? ctoHoursInput.value : '';
            
            const isValid = workDate && workDate.value && 
                           reason && reason.value.trim().length >= 10 && 
                           hours > 0;
            
            if (ctoSubmitBtn) ctoSubmitBtn.disabled = !isValid;
            
            return isValid;
        }

        // Event listeners for CTO calculation
        if (ctoStartTime) {
            ctoStartTime.addEventListener('change', calculateCTOHours);
        }

        if (ctoEndTime) {
            ctoEndTime.addEventListener('change', calculateCTOHours);
        }

        // Event listeners for CTO form validation
        var ctoWorkDate = document.getElementById('work_date');
        if (ctoWorkDate) {
            ctoWorkDate.addEventListener('change', validateCTOForm);
        }

        var ctoReason = document.getElementById('cto_reason');
        if (ctoReason) {
            ctoReason.addEventListener('input', validateCTOForm);
        }

        // Auto-open CTO modal if there are CTO validation errors
        @if($errors->has('requested_hours') || $errors->has('work_date') || $errors->has('start_time') || $errors->has('end_time') || $errors->has('reason') || $errors->has('description'))
            if (ctoModal) {
                ctoModal.classList.remove('hidden');
                ctoModal.classList.add('flex');
            }
        @endif

        // Auto-open leave modal if there are leave validation errors
        @if($errors->any() && !($errors->has('requested_hours') || $errors->has('work_date') || $errors->has('start_time') || $errors->has('end_time') || $errors->has('reason') || $errors->has('description')))
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        @endif

        // Date calculation function
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

            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end dates
            
            totalDaysSpan.textContent = daysDiff;
            daysInfo.classList.remove('hidden');
            
            return daysDiff;
        }

        // Validation function
        function validateLeaveRequest() {
            const selectedLeaveType = leaveTypeSelect.value;
            const totalDays = calculateDays();
            const availableDays = leaveBalances[selectedLeaveType] || 0;

            // Reset warnings
            dateWarning.classList.add('hidden');
            leaveTypeWarning.classList.add('hidden');
            
            let isValid = true;

            // Check if leave type has available days
            if (selectedLeaveType && availableDays === 0) {
                leaveTypeWarning.classList.remove('hidden');
                isValid = false;
            }

            // Check if requested days exceed available days
            if (selectedLeaveType && totalDays > 0 && totalDays > availableDays) {
                dateWarning.classList.remove('hidden');
                dateWarning.innerHTML = `The selected dates (${totalDays} days) exceed your available ${selectedLeaveType} days (${availableDays} available).`;
                isValid = false;
            }

            // Enable/disable submit button
            submitBtn.disabled = !isValid || !selectedLeaveType || totalDays === 0;
            
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

        // Form submission validation
        const form = document.querySelector('#leaveRequestModal form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateLeaveRequest()) {
                    e.preventDefault();
                    alert('Please fix the validation errors before submitting.');
                }
            });
        }

        // Initial validation
        validateLeaveRequest();
    });
</script>
