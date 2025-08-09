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
                <p class="text-sm font-medium text-{{ $colors[$leave['type']] ?? 'gray' }}-700 mb-1">{{ $leave['type'] }}</p>
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
    <div id="leaveRequestModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button id="closeLeaveRequestModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">File a Leave Request</h3>
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('leave-request.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700">Type of Leave</label>
                    <select name="leave_type" id="leave_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select type</option>
                        <option value="Sick">Sick Leave</option>
                        <option value="Vacation">Vacation Leave</option>
                        <option value="CTO">CTO</option>
                        <option value="Special Privilege">Special Privilege Leave</option>
                        <option value="Force">Force Leave</option>
                        <option value="Maternity">Maternity Leave</option>
                        <option value="Rehabilitation">Rehabilitation Leave</option>
                        <option value="Solo Parent">Solo Parent Leave</option>
                        <option value="Study">Study Leave</option>
                    </select>
                    @error('leave_type')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('start_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('end_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                    <input type="text" name="reason" id="reason" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('reason')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">File Leave</button>
            </form>
        </div>
    </div>
</div>
<!-- Modal JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('leaveRequestBtn');
        var modal = document.getElementById('leaveRequestModal');
        var closeBtn = document.getElementById('closeLeaveRequestModal');
        if(btn && modal && closeBtn) {
            btn.addEventListener('click', function() {
                modal.classList.remove('hidden');
            });
            closeBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        }
    });
</script>
