<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Service Records</h3>
                <p class="text-sm text-gray-500 mt-1">{{ count($serviceRecords) }} {{ Str::plural('record', count($serviceRecords)) }} found</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Service Record
            </button>
        </div>

        <!-- Modal -->
        @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-0 relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 z-20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex flex-col h-96">
                    <div class="px-6 pt-6 pb-2 border-b">
                        <h2 class="text-xl font-bold">{{ $isEditMode ? 'Edit Service Record' : 'Add Service Record' }}</h2>
                    </div>
                    <div class="flex-1 overflow-y-auto px-6 py-2">
                        <form wire:submit.prevent="save" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Record Number</label>
                                <input type="text" wire:model.defer="personnel_id" class="mt-1 block w-full rounded border-gray-300 bg-gray-100 shadow-sm focus:ring focus:ring-blue-200" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Designation<span class="text-red-500">*</span></label>
                                <select wire:model.defer="position_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
                                    <option value="">Select a designation</option>
                                    @foreach($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->title }}</option>
                                    @endforeach
                                </select>
                                @error('position_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Appointment Status <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="appointment_status" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required maxlength="255">
                                @error('appointment_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Salary <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" min="0" wire:model.defer="salary" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
                                @error('salary') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">From Date <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model.defer="from_date" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
                                    @error('from_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" wire:model.defer="to_date" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    @error('to_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Station</label>
                                <input type="text" wire:model.defer="station" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" maxlength="255">
                                @error('station') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Branch</label>
                                <input type="text" wire:model.defer="branch" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" maxlength="255">
                                @error('branch') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Leave w/o Pay</label>
                                <input type="number" wire:model.defer="lv_wo_pay" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" maxlength="255">
                                @error('lv_wo_pay') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Separation Cause</label>
                                <input type="text" wire:model.defer="separation_date_cause" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" maxlength="255">
                                @error('separation_date_cause') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="px-6 py-4 border-t flex justify-end space-x-2 bg-white">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                        <button type="button" wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ $isEditMode ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50">
            <div class="bg-green-500 text-white px-6 py-3 rounded shadow-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Table and empty state below -->
        @if(count($serviceRecords) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Station</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave w/o Pay</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Separation Cause</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded At</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($serviceRecords as $record)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->position->title ?? '' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->appointment_status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($record->salary, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->from_date ? \Carbon\Carbon::parse($record->from_date)->format('M j, Y') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->to_date ? \Carbon\Carbon::parse($record->to_date)->format('M j, Y') : 'Present' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->station }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->branch }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->lv_wo_pay ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->separation_date_cause ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($record->created_at)
                            <div>{{ \Carbon\Carbon::parse($record->created_at)->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($record->created_at)->format('g:i A') }}</div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="editRecord({{ $record->id }})" class="text-blue-600 hover:text-blue-900 p-1 rounded focus:outline-none mr-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </button>
                            <button wire:click="confirmDelete({{ $record->id }})" class="text-red-600 hover:text-red-900 p-1 rounded focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No service records found</h3>
            <p class="mt-1 text-sm text-gray-500">This personnel member has no recorded service records.</p>
        </div>
        @endif
    </div>

    @if($showDeleteModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-xl font-bold mb-4 text-red-600">Confirm Delete</h2>
            <p class="mb-6 text-gray-700">Are you sure you want to delete this service record? This action cannot be undone.</p>
            <div class="flex justify-end space-x-2">
                <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                <button wire:click="deleteRecord" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
