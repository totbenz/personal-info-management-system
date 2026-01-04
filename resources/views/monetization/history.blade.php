<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">Monetization History</h2>
                <p class="text-sm text-gray-600">View your leave monetization request history</p>
            </div>
        </div>
    </x-slot name="header">

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-yellow-50/20 to-orange-50/30">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Monetization History</h1>
                        <p class="text-gray-600 mt-1">View your leave monetization request history</p>
                    </div>
                    <button onclick="openMonetizationModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Request
                    </button>
                </div>
            </div>

            <!-- Monetization Requests Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                @if($monetizationRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Days</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VL Used</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL Used</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($monetizationRequests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->total_days }} days
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->vl_days_used }} days
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->sl_days_used }} days
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($request->status == 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($request->status == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @elseif($request->status == 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $request->reason ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($request->status == 'approved')
                                            <button onclick="openDownloadModal({{ $request->id }})" type="button" class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-700 text-xs font-semibold rounded-full hover:bg-blue-50 transition">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                        {{ $monetizationRequests->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No monetization requests</h3>
                        <p class="text-gray-500 mb-4">You haven't submitted any monetization requests yet.</p>
                        <button onclick="openMonetizationModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Your First Request
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

<!-- Monetization Modal -->
<div id="monetizationModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg relative">
        <form id="monetizationForm" method="POST">
            @csrf
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Leave Monetization Request</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Available Leave Balances</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <p class="text-sm text-blue-600 font-medium">Vacation Leave</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $leaveBalances['Vacation Leave'] ?? 0 }} days</p>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <p class="text-sm text-green-600 font-medium">Sick Leave</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $leaveBalances['Sick Leave'] ?? 0 }} days</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Maximum monetizable: <span id="maxMonetizableDays">0</span> days (5 days buffer per leave type)</p>
                        </div>

                        <div class="mb-4">
                            <label for="days_to_monetize" class="block text-sm font-medium text-gray-700 mb-2">Days to Monetize</label>
                            <input type="number" name="days_to_monetize" id="days_to_monetize" min="1" max="30" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                            <textarea name="reason" id="reason" rows="3" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Please specify the reason for monetization..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Submit Request
                </button>
                <button type="button" onclick="closeMonetizationModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </form>
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
        <h3 class="text-xl font-bold text-gray-900 mb-4">Download Monetization Application</h3>
        <p class="text-gray-600 mb-6">Choose the signature type for your monetization application:</p>
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
// Leave balances from backend
const leaveBalances = @json($leaveBalances);

// Calculate max monetizable days
const vlAvailable = leaveBalances['Vacation Leave'] || 0;
const slAvailable = leaveBalances['Sick Leave'] || 0;
const maxMonetizableDays = Math.max(0, vlAvailable - 5) + Math.max(0, slAvailable - 5);

// Update UI
document.getElementById('maxMonetizableDays').textContent = maxMonetizableDays;
document.getElementById('days_to_monetize').max = maxMonetizableDays;

// Modal functions
function openMonetizationModal() {
    document.getElementById('monetizationModal').classList.remove('hidden');
    document.getElementById('monetizationModal').classList.add('flex');
}

function closeMonetizationModal() {
    document.getElementById('monetizationModal').classList.add('hidden');
    document.getElementById('monetizationModal').classList.remove('flex');
}

// Form submission
document.getElementById('monetizationForm').addEventListener('submit', async function(e) {
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

    try {
        const response = await fetch('{{ route(Auth::user()->role . ".monetization.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showSuccess(data.message);
            closeMonetizationModal();
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showError(data.message);
        }
    } catch (error) {
        showError('An error occurred. Please try again.');
    }
});

function showError(message) {
    // Create error notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function showSuccess(message) {
    // Create success notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Download modal functions
function openDownloadModal(monetizationId) {
    const modal = document.getElementById('downloadModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Set download URLs based on user role
    const userRole = '{{ auth()->user()->role }}';
    if (userRole === 'teacher') {
        document.getElementById('downloadAssistant').href = `/teacher/monetization-application/download/${monetizationId}/assistant`;
        document.getElementById('downloadSchools').href = `/teacher/monetization-application/download/${monetizationId}/schools`;
    } else {
        document.getElementById('downloadAssistant').href = `/non-teaching/monetization-application/download/${monetizationId}/assistant`;
        document.getElementById('downloadSchools').href = `/non-teaching/monetization-application/download/${monetizationId}/schools`;
    }
}

function closeDownloadModal() {
    const modal = document.getElementById('downloadModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
</x-app-layout>
