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

    // Create an array of leave balances for JavaScript access
    $leaveBalances = [];
    foreach($leaveData as $leave) {
        $leaveBalances[$leave['type']] = $leave['available'];
    }
@endphp

<div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-8 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Available Leaves ({{ $year }})</h3>
        </div>
        <!-- Leave Request Icon Button -->
        <button id="leaveRequestBtn" class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200" title="File a Leave Request">
            <!-- Document with Plus Icon -->
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3h-6m8 5a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2h10z" />
            </svg>
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($leaveData as $leave)
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
                <p class="text-lg font-bold text-gray-900">Available: {{ $leave['available'] }} / {{ $leave['max'] }}</p>
                <p class="text-sm text-gray-600">Used: {{ $leave['used'] }}</p>
                @if($leave['ctos_earned'])
                <p class="text-sm text-teal-600">CTO Earned: {{ $leave['ctos_earned'] }}</p>
                @endif
                @if($leave['remarks'])
                <p class="text-xs text-gray-500 italic">{{ $leave['remarks'] }}</p>
                @endif
            </div>
        </div>
        @endforeach
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
                        @foreach($leaveData as $leave)
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

        // Auto-open modal if there are validation errors
        @if($errors->any())
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
