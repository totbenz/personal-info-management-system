<div class="mx-5 my-8 p-3">
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
                        <span><strong>Edit Salaries:</strong> Double-click any salary cell to edit values directly</span>
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
    <div class="flex items-center gap-4">
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
            <button wire:click="addYear" class="bg-blue-500 text-white px-3 py-1 rounded" @if($isAddingYear) disabled @endif>Add Year</button>
            <button wire:click="showDeleteYearModal" class="bg-red-500 text-white px-3 py-1 rounded ml-2">Delete Year</button>
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
    <div class="flex justify-between items-center mb-4">
        <div>
            <!-- Old year selector removed here -->
        </div>
        <div class="flex gap-2 items-center">
            <input type="number" min="1" placeholder="Add Grade" wire:model.defer="newGrade" class="border rounded px-2 py-1 w-24" @if($isAddingGrade) disabled @endif>
            <button wire:click="addSalaryGrade" class="bg-green-500 text-white px-3 py-1 rounded" @if($isAddingGrade) disabled @endif>
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
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 bg-white">
            <thead>
                <tr>
                    <th class="border px-2 py-1 bg-gray-100">Salary Grade</th>
                    @for($step = 1; $step <= 8; $step++)
                        <th class="border px-2 py-1 bg-gray-100">Step {{ $step }}</th>
                        @endfor
                        <th class="border px-2 py-1 bg-gray-100">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salaryGrades as $grade)
                <tr>
                    <td class="border px-2 py-1 font-semibold text-center bg-gray-100 hover:bg-green-50">{{ $grade->grade }}</td>
                    @for($step = 1; $step <= 8; $step++)
                        <td class="border px-2 py-1 text-center cursor-pointer hover:bg-green-50"
                        ondblclick="@this.startEditCell({{ $grade->id }}, {{ $step }})">
                        @if($editingCell['salary_grade_id'] === $grade->id && $editingCell['step'] === $step)
                        <input type="number" step="0.01" wire:model.defer="editingCell.salary" wire:keydown.enter="saveCell" wire:blur="saveCell" class="w-20 border rounded px-1 py-0.5">
                        @else
                        {{ isset($salaryMatrix[$grade->id][$step]) ? number_format($salaryMatrix[$grade->id][$step], 2) : '0.00' }}
                        @endif
                        </td>
                        @endfor
                        <td class="border px-2 py-1 text-center">
                            <button wire:click.prevent="confirmDeleteGrade({{ $grade->id }})" class="text-red-500 hover:text-red-700 transition-colors duration-200" title="Delete Grade">
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

    <!-- Delete Grade Confirmation Modal -->
    @if($confirmDeleteGradeId)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl p-8 w-96 max-w-full border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-7 h-7 text-red-500 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Delete Salary Grade</h2>
            </div>
            <p class="mb-6 text-gray-700">Are you sure you want to delete this salary grade? <span class="font-semibold text-red-600">This action cannot be undone</span> and will remove all related salary steps for this grade.</p>
            <div class="flex justify-end gap-3">
                <button wire:click="cancelDeleteGrade" class="px-5 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">Cancel</button>
                <button wire:click="deleteSalaryGradeConfirmed" class="px-5 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition shadow">Delete</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Step Confirmation Modal -->
    @if($confirmDeleteStep['all'])
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80">
            <h2 class="text-lg font-semibold mb-4">Delete Step</h2>
            <p class="mb-6">Are you sure you want to delete all salary values for Step {{ $confirmDeleteStep['step'] }} in this year?</p>
            <div class="flex justify-end gap-2">
                <button wire:click="cancelDeleteStep" class="px-4 py-1 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                <button wire:click="deleteSalaryStepConfirmed" class="px-4 py-1 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>
    @endif

    @if($confirmDeleteYearModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl p-8 w-96 max-w-full border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-7 h-7 text-red-500 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Delete Year</h2>
            </div>
            <p class="mb-6 text-gray-700">Are you sure you want to delete all salary steps for year <span class="font-semibold text-red-600">{{ $selectedYear }}</span>? <span class="font-semibold text-red-600">This action cannot be undone.</span></p>
            <div class="flex justify-end gap-3">
                <button wire:click="cancelDeleteYearModal" class="px-5 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">Cancel</button>
                <button wire:click="deleteSelectedYear" class="px-5 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition shadow">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
