<div>
    <!-- Success/Error Messages -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showSuccess', (message) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false,
                });
            });

            Livewire.on('showError', (message) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                });
            });
        });
    </script>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <h1 class="text-2xl font-bold text-gray-800">Salary Grade Management System</h1>
        </div>
        <p class="text-gray-600 mb-4">Manage salary grades and steps across different years. Create, edit, and maintain your organization's salary structure with ease.</p>

        <!-- Quick Tutorial -->
        <div class="bg-white rounded-lg p-4 border border-blue-100">
            <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                How to Use
            </h3>
            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div class="space-y-2">
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold mr-2 mt-0.5">1</span>
                        <span><strong>Select Year:</strong> Choose the year you want to work with from the dropdown</span>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold mr-2 mt-0.5">2</span>
                        <span><strong>Add Grade:</strong> Enter a grade number and click "Add Grade" to create new salary grades</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold mr-2 mt-0.5">3</span>
                        <span><strong>Edit Salaries:</strong> Click any salary cell to edit values directly</span>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-semibold mr-2 mt-0.5">4</span>
                        <span><strong>Manage Data:</strong> Add new years or delete existing grades and years as needed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Year and Grade Management Controls -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <div>
                <label for="year-select" class="font-semibold mr-2 text-gray-700">Year:</label>
                <div class="relative inline-block">
                    <select id="year-select" wire:model.live="selectedYear"
                        class="appearance-none border border-gray-300 rounded-lg px-4 py-2 pr-10 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-150 ease-in-out shadow-sm">
                        @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="flex gap-2 items-center">
                <input type="number" min="2000" max="2100" placeholder="Add Year" wire:model.defer="newYear" class="border rounded px-2 py-1 w-24" @if($isAddingYear) disabled @endif>
                <button wire:click="addYear" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition" @if($isAddingYear) disabled @endif>
                    Add Year
                </button>
                <button wire:click="showDeleteYearModal" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition ml-2">
                    Delete Year
                </button>
                @if($isAddingYear)
                <svg class="animate-spin h-5 w-5 text-blue-600 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                @endif
            </div>
            @if($addYearError)
            <div class="text-red-600 text-sm mt-1">{{ $addYearError }}</div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <div class="flex gap-2 items-center">
                <input type="number" min="1" placeholder="Add Grade" wire:model.defer="newGrade" class="border rounded px-2 py-1 w-24" @if($isAddingGrade) disabled @endif>
                <button wire:click="addSalaryGrade" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition" @if($isAddingGrade) disabled @endif>
                    Add Grade
                </button>
                @if($isAddingGrade)
                <svg class="animate-spin h-5 w-5 text-green-600 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                @endif
            </div>
            @if($addGradeError)
            <div class="text-red-600 text-sm mt-1">{{ $addGradeError }}</div>
            @endif
        </div>
    </div>

    <!-- Salary Matrix Table -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-3 bg-gray-100 text-center">Salary Grade</th>
                        @for($step = 1; $step <= 8; $step++)
                            <th class="border px-4 py-3 bg-gray-100 text-center">Step {{ $step }}</th>
                        @endfor
                        <th class="border px-4 py-3 bg-gray-100 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salaryGrades as $grade)
                    <tr>
                        <td class="border px-4 py-3 font-semibold text-center bg-gray-100 hover:bg-green-50">{{ $grade->grade }}</td>
                        @for($step = 1; $step <= 8; $step++)
                            <td class="border px-4 py-3 text-center cursor-pointer hover:bg-green-50"
                                wire:click="startEditCell({{ $grade->id }}, {{ $step }})">
                                @if($editingCell['salary_grade_id'] === $grade->id && $editingCell['step'] === $step)
                                    <input type="number" step="0.01" wire:model.defer="editingCell.salary"
                                           wire:keydown.enter="saveCell" wire:blur="saveCell"
                                           class="w-24 border rounded px-2 py-1 text-center">
                                    @error('editingCell.salary') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @else
                                    {{ isset($salaryMatrix[$grade->id][$step]) ? number_format($salaryMatrix[$grade->id][$step], 2) : '0.00' }}
                                @endif
                            </td>
                        @endfor
                        <td class="border px-4 py-3 text-center">
                            <button wire:click.prevent="confirmDeleteGrade({{ $grade->id }})"
                                    class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                    title="Delete Grade">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Grade Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmDeleteGradeId') }"
         x-show="show != null"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Delete Salary Grade
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this salary grade? <span class="font-semibold text-red-600">This action cannot be undone</span> and will remove all related salary steps for this grade.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteSalaryGradeConfirmed"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button wire:click="cancelDeleteGrade"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Year Confirmation Modal -->
    <div x-data="{ show: @entangle('showDeleteYearModalVisible') }"
         x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Delete Year
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete all salary steps for year <span class="font-semibold text-red-600">{{ $selectedYear }}</span>? <span class="font-semibold text-red-600">This action cannot be undone.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteSelectedYear"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button wire:click="cancelDeleteYearModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
