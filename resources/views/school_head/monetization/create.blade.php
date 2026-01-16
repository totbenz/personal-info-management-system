<x-app-layout title="Leave Monetization Request">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Leave Monetization Request</h1>
                <p class="text-orange-100 mt-1">Convert your leave credits to cash</p>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form id="monetizationForm" method="POST" action="{{ route('school_head.monetization.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <!-- Available Leave Balances -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Available Leave Balances</h3>

                            @if(isset($message))
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm text-yellow-800">{{ $message }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                        <p class="text-sm font-medium text-blue-600">Vacation Leave</p>
                                        <p class="text-3xl font-bold text-blue-900">{{ $leaveBalances['Vacation Leave'] ?? 0 }} days</p>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                        <p class="text-sm font-medium text-green-600">Sick Leave</p>
                                        <p class="text-3xl font-bold text-green-900">{{ $leaveBalances['Sick Leave'] ?? 0 }} days</p>
                                    </div>
                                </div>
                                <div class="mt-4 p-4 bg-orange-50 rounded-lg border border-orange-200">
                                    <p class="text-sm text-gray-600">Maximum Monetizable</p>
                                    <p class="text-2xl font-bold text-orange-600" id="maxMonetizableDays">0 days</p>
                                    <p class="text-xs text-gray-500 mt-1">(5 days buffer per leave type is required)</p>
                                </div>
                            @endif
                        </div>

                        @unless(isset($message))
                        <!-- Days to Monetize -->
                        <div>
                            <label for="days_to_monetize" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Days to Monetize
                            </label>
                            <input type="number"
                                   name="days_to_monetize"
                                   id="days_to_monetize"
                                   min="1"
                                   required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                   placeholder="Enter number of days">
                            <p class="text-xs text-gray-500 mt-1">Days will be deducted from Vacation Leave first, then Sick Leave if needed.</p>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Monetization
                            </label>
                            <textarea name="reason"
                                      id="reason"
                                      rows="4"
                                      required
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                      placeholder="Please specify the reason for monetization..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('school_head.monetization.history') }}"
                               class="px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    id="submitBtn"
                                    class="px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Submit Request
                            </button>
                        </div>
                        @endunless
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notification" class="fixed top-4 right-4 hidden z-50"></div>

<script>
// Only run JavaScript if leave balances exist
@if(!isset($message))
// Leave balances from backend
const leaveBalances = @json($leaveBalances);

// Calculate max monetizable days
const vlAvailable = leaveBalances['Vacation Leave'] || 0;
const slAvailable = leaveBalances['Sick Leave'] || 0;
const maxMonetizableDays = Math.max(0, vlAvailable - 5) + Math.max(0, slAvailable - 5);

// Update UI
document.getElementById('maxMonetizableDays').textContent = maxMonetizableDays + ' days';

// Form submission
document.getElementById('monetizationForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const daysToMonetize = parseInt(formData.get('days_to_monetize'));

    // Add leave balances to form data
    formData.append('vl_available', vlAvailable);
    formData.append('sl_available', slAvailable);

    // Validate
    if (daysToMonetize > maxMonetizableDays) {
        showNotification('Invalid amount. You can only monetize up to ' + maxMonetizableDays + ' days.', 'error');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';

    try {
        const response = await fetch('{{ route("school_head.monetization.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                window.location.href = '{{ route("school_head.monetization.history") }}';
            }, 2000);
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ' +
        (type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white');
    notification.textContent = message;
    notification.classList.remove('hidden');

    setTimeout(() => {
        notification.classList.add('hidden');
    }, 5000);
}
@endif
</script>
</x-app-layout>
