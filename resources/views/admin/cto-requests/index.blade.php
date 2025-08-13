<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                CTO Requests Management
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/30">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Header Card -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8 backdrop-blur-sm">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-500/10 to-teal-600/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Compensatory Time Off Requests</h3>
                            <p class="text-gray-600">Review and approve CTO requests from school heads</p>
                        </div>
                    </div>
                    
                    <!-- Summary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-xl border border-orange-200/50">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-orange-700 mb-1">Pending Requests</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $requests->count() }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-br from-teal-50 to-teal-100/50 rounded-xl border border-teal-200/50">
                            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-teal-700 mb-1">Total Hours</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $requests->sum('requested_hours') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl border border-purple-200/50">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-700 mb-1">CTO Days</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($requests->sum('requested_hours') / 8, 1) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- CTO Requests Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pending CTO Requests</h3>
                    <p class="text-sm text-gray-600">Review and approve compensatory time off requests</p>
                </div>
                
                @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    School Head
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    School
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Work Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hours / CTO Days
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reason
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Requested Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center">
                                                <span class="text-xs font-medium text-white">
                                                    {{ strtoupper(substr($request->schoolHead->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($request->schoolHead->last_name ?? '', 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $request->schoolHead->first_name ?? 'N/A' }} {{ $request->schoolHead->middle_name ?? '' }} {{ $request->schoolHead->last_name ?? '' }} {{ $request->schoolHead->name_ext ?? '' }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $request->schoolHead->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $request->schoolHead->school->school_name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->schoolHead->school->school_id ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($request->work_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($request->start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($request->end_time)->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->requested_hours }} hours</div>
                                    <div class="text-xs text-teal-600 font-medium">{{ number_format($request->cto_days_earned, 2) }} CTO days</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs" title="{{ $request->reason }}">
                                        {{ Str::limit($request->reason, 50) }}
                                    </div>
                                    @if($request->description)
                                    <div class="text-xs text-gray-500 mt-1" title="{{ $request->description }}">
                                        {{ Str::limit($request->description, 30) }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Approve Button -->
                                        <button onclick="openApproveModal({{ $request->id }}, '{{ $request->schoolHead->first_name }} {{ $request->schoolHead->last_name }}', {{ $request->requested_hours }}, {{ number_format($request->cto_days_earned, 2) }})" 
                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approve
                                        </button>
                                        
                                        <!-- Deny Button -->
                                        <button onclick="openDenyModal({{ $request->id }}, '{{ $request->schoolHead->first_name }} {{ $request->schoolHead->last_name }}')" 
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Deny
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending CTO Requests</h3>
                    <p class="text-gray-500">There are currently no CTO requests waiting for approval.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button onclick="closeApproveModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Approve CTO Request</h3>
            <div id="approveDetails" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg"></div>
            <form id="approveForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="approve_admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes (Optional)</label>
                    <textarea name="admin_notes" id="approve_admin_notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                              placeholder="Add any notes about this approval..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                        Approve Request
                    </button>
                    <button type="button" onclick="closeApproveModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deny Modal -->
    <div id="denyModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button onclick="closeDenyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Deny CTO Request</h3>
            <div id="denyDetails" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg"></div>
            <form id="denyForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="deny_admin_notes" class="block text-sm font-medium text-gray-700">Reason for Denial <span class="text-red-500">*</span></label>
                    <textarea name="admin_notes" id="deny_admin_notes" rows="3" required 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                              placeholder="Please provide a clear reason for denying this request..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">This reason will be visible to the school head.</p>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                        Deny Request
                    </button>
                    <button type="button" onclick="closeDenyModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id, name, hours, ctoDays) {
            const modal = document.getElementById('approveModal');
            const form = document.getElementById('approveForm');
            const details = document.getElementById('approveDetails');
            
            form.action = `/admin/cto-requests/${id}/approve`;
            details.innerHTML = `
                <h4 class="font-medium text-green-800 mb-2">Approve CTO Request</h4>
                <p class="text-sm text-green-700">School Head: <strong>${name}</strong></p>
                <p class="text-sm text-green-700">Hours Worked: <strong>${hours} hours</strong></p>
                <p class="text-sm text-green-700">CTO Days to be Added: <strong>${ctoDays} days</strong></p>
            `;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeApproveModal() {
            const modal = document.getElementById('approveModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('approve_admin_notes').value = '';
        }

        function openDenyModal(id, name) {
            const modal = document.getElementById('denyModal');
            const form = document.getElementById('denyForm');
            const details = document.getElementById('denyDetails');
            
            form.action = `/admin/cto-requests/${id}/deny`;
            details.innerHTML = `
                <h4 class="font-medium text-red-800 mb-2">Deny CTO Request</h4>
                <p class="text-sm text-red-700">School Head: <strong>${name}</strong></p>
                <p class="text-sm text-red-700">Please provide a clear reason for denial.</p>
            `;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDenyModal() {
            const modal = document.getElementById('denyModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('deny_admin_notes').value = '';
        }

        // Close modals when clicking outside
        document.getElementById('approveModal').addEventListener('click', function(e) {
            if (e.target === this) closeApproveModal();
        });

        document.getElementById('denyModal').addEventListener('click', function(e) {
            if (e.target === this) closeDenyModal();
        });
    </script>
</x-app-layout>
