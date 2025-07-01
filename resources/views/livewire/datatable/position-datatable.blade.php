<div class="mx-5 my-8 p-3 bg-white rounded-xl shadow-lg" x-data="{
    init() {
        window.addEventListener('show-success-alert', e => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: e.detail && e.detail.message ? e.detail.message : 'Position deleted successfully.',
                    timer: 1800,
                    showConfirmButton: false
                });
            } else {
                alert(e.detail && e.detail.message ? e.detail.message : 'Position deleted successfully.');
            }
        });
        window.addEventListener('show-error-alert', e => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: e.detail && e.detail.message ? e.detail.message : 'Failed to delete position.'
                });
            } else {
                alert(e.detail && e.detail.message ? e.detail.message : 'Failed to delete position.');
            }
        });
    }
}">
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-4">
            <button x-on:click="$openModal('create-position-modal')" class="bg-main text-white px-4 py-2 rounded-lg shadow hover:bg-main-dark transition font-semibold">
                Add Position
            </button>
        </div>
        <!-- Search Input -->
        <div class="relative">
            <input
                type="text"
                wire:model.live.debounce.150ms="search"
                placeholder="Search Position..."
                class="w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm pl-10 focus:ring-2 focus:ring-main focus:border-main text-sm" />
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
            </svg>
        </div>
        <!-- <div class="flex w-2/4 items-center rounded-md border border-gray-400 bg-white focus:bg-white focus:border-gray-500">
            <div class="pl-2">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                    <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                    </path>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search"
                class="appearance-none rounded-md border-none block pl-2 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700">
        </div> -->
    </div>
    <div class="mt-5 overflow-x-auto rounded-lg">
        <div class="my-2 flex space-x-2">
            <div class="w-[11.5rem] px-0.5 text-xs">
                <x-native-select wire:model.live.debounce.300ms="selectedClassification" id="selectedClassification" name="selectedClassification">
                    <option value="">Select classification</option>
                    <option value="teaching">Teaching</option>
                    <option value="teaching-related">Teaching-related</option>
                    <option value="non-teaching">Non-teaching</option>
                </x-native-select>
            </div>
        </div>
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="sticky top-0 z-10 bg-gray-100 text-gray-700 text-xs uppercase tracking-wider shadow">
                <tr>
                    <th wire:click="doSort('id')" class="w-1/12 p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="school_is">
                                <span class="font-semibold text-left">ID</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-3/12" wire:click="doSort('title')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Title</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-3/12" wire:click="doSort('classification')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Classification</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Action</span>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $positions as $position)
                <tr wire:loading.class="opacity-75" class="hover:bg-indigo-50">
                    <td class="p-2 whitespace-nowrap w-1/12 border-b border-gray-200">
                        <div class="text-left">{{ $position->id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12 border-b border-gray-200">
                        <div class="text-left">{{ $position->title }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12 border-b border-gray-200">
                        <div class="text-xs tracking-wider text-left uppercase">{{ $position->classification }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12 border-b border-gray-200">
                        {{-- @livewire('modal') --}}@include('position.forms.edit')

                        <div class="flex space-x-2">
                            <button wire:click="editPosition({{ $position->id }})" class="py-1 px-4 bg-main text-white font-medium text-sm rounded-md hover:bg-main-dark transition">
                                View
                            </button>
                            <button wire:click="setDeleteId({{ $position->id }})" x-on:click="$openModal('delete-position-modal')"
                                class="py-1 px-4 bg-red-600 font-medium text-sm tracking-wider rounded-md border-2 border-red-600 hover:bg-red-700 hover:text-white text-white duration-300">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if ($positions->isEmpty())
                <tr wire:loading.class="opacity-75">
                    <td colspan="5" class="p-2 w-full text-center">No Positions Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $positions->links() }}
    </div>
    @include('position.forms.create')
    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-position-modal" wire:model.live="showDeleteModal">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md mx-auto overflow-hidden">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Delete Confirmation</h3>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-6">
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    @php
                    $deletePosition = null;
                    if ($showDeleteModal && $deleteId) {
                    $deletePosition = \App\Models\Position::find($deleteId);
                    }
                    @endphp
                    <h4 class="text-lg font-medium text-gray-900 mb-2">
                        Are you sure want to delete
                        <span style="color: red; font-weight: bold;">
                            Position
                            @if($deletePosition)
                            ID:{{ $deletePosition->id }} - {{ $deletePosition->title }}
                            @else
                            ...
                            @endif
                        </span> ?
                    </h4>
                    <p class="text-gray-600 text-xs leading-relaxed">
                        This will permanently delete the position from the system.
                        <span class="font-medium">This action cannot be undone.</span>
                    </p>
                </div>

                @if ($deleteError)
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-700 text-sm">{{ $deleteError }}</p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <x-button
                        wire:click="cancelDelete()"
                        type="button"
                        class="flex-1 bg-blue-100 text-blue-700 border-0 hover:bg-blue-200 focus:ring-blue-300 py-3 font-medium transition-all duration-200">
                        Cancel
                    </x-button>
                    <button
                        wire:click="deletePosition()"
                        type="button"
                        class="flex-1 bg-red-600 text-white border-0 hover:bg-red-800 hover:border-red-600 border-2 py-3 font-medium transition-all duration-200">
                        <span class="flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Delete</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </x-modal>
</div>
