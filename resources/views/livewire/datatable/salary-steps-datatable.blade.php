<div class="mx-5 my-8 p-3">
    <div class="flex justify-between">
        <div class="w-1/4 inline-flex space-x-4">
            <div class="flex justify-between space-x-3">
                <button wire:click="create" class="py-1 px-4 bg-white font-medium text-sm tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300">
                    New Salary Step
                </button>
            </div>
        </div>

        <div class="flex w-2/4 items-center rounded-md border border-gray-400 bg-white focus:bg-white focus:border-gray-500">
            <div class="pl-2">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                    <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                    </path>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search"
                class="appearance-none rounded-md border-none block pl-2 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700">
        </div>
    </div>

    <div class="mt-5 overflow-x-auto">
        <table class="table-auto w-full">
            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                <tr>
                    <th wire:click="doSort('id')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">ID</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('salary_grade_id')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Salary Grade</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('step')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Step</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('year')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Year</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('salary')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Salary</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <span class="font-semibold text-left">Actions</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salarySteps as $salaryStep)
                <tr wire:loading.class="opacity-75" class="text-sm">
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryStep->id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryStep->salaryGrade->grade }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryStep->step }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryStep->year }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryStep->formatted_salary }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="flex justify-between space-x-3">
                            <button wire:click="edit({{ $salaryStep->id }})" class="py-1 px-2 bg-white font-medium text-sm tracking-wider rounded-md border-2 border-blue-600 hover:bg-blue-600 hover:text-white text-blue-600 duration-300">
                                Edit
                            </button>
                            <button wire:click="confirmDelete({{ $salaryStep->id }})" class="py-1 px-2 bg-white font-medium text-sm tracking-wider rounded-md border-2 border-red-600 hover:bg-red-600 hover:text-white text-red-600 duration-300">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if ($salarySteps->isEmpty())
                <tr wire:loading.class="opacity-75">
                    <td colspan="8" class="p-2 w-full text-center">No Salary Steps Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $salarySteps->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <x-dialog-modal wire:model.live="showCreateModal">
        <x-slot name="title">
            {{ $editingSalaryStep['id'] ? 'Edit' : 'Create' }} Salary Step
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-label for="editingSalaryStep.salary_grade_id" value="Salary Grade" />
                <x-select id="editingSalaryStep.salary_grade_id" class="block mt-1 w-full" wire:model="editingSalaryStep.salary_grade_id">
                    <option value="">Select Salary Grade</option>
                    @foreach($salaryGrades as $grade)
                    <option value="{{ $grade->id }}">Grade {{ $grade->grade }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="editingSalaryStep.salary_grade_id" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="editingSalaryStep.step" value="Step" />
                <x-input id="editingSalaryStep.step" type="number" class="block mt-1 w-full" wire:model="editingSalaryStep.step" min="1" max="8" />
                <x-input-error for="editingSalaryStep.step" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="editingSalaryStep.year" value="Year" />
                <x-input id="editingSalaryStep.year" type="number" class="block mt-1 w-full" wire:model="editingSalaryStep.year" min="2020" max="2050" />
                <x-input-error for="editingSalaryStep.year" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="editingSalaryStep.salary" value="Salary" />
                <x-input id="editingSalaryStep.salary" type="number" step="0.01" class="block mt-1 w-full" wire:model="editingSalaryStep.salary" />
                <x-input-error for="editingSalaryStep.salary" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showCreateModal', false)" class="mr-2">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button wire:click="save">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Edit Modal -->
    <x-dialog-modal wire:model.live="showEditModal">
        <x-slot name="title">
            Edit Salary Step
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-label for="editingSalaryStep.salary_grade_id" value="Salary Grade" />
                <x-select id="editingSalaryStep.salary_grade_id" class="block mt-1 w-full" wire:model="editingSalaryStep.salary_grade_id">
                    <option value="">Select Salary Grade</option>
                    @foreach($salaryGrades as $grade)
                    <option value="{{ $grade->id }}">Grade {{ $grade->grade }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="editingSalaryStep.salary_grade_id" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="editingSalaryStep.step" value="Step" />
                <x-input id="editingSalaryStep.step" type="number" class="block mt-1 w-full" wire:model="editingSalaryStep.step" min="1" max="8" />
                <x-input-error for="editingSalaryStep.step" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="editingSalaryStep.year" value="Year" />
                <x-input id="editingSalaryStep.year" type="number" class="block mt-1 w-full" wire:model="editingSalaryStep.year" min="2020" max="2050" />
                <x-input-error for="editingSalaryStep.year" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="editingSalaryStep.salary" value="Salary" />
                <x-input id="editingSalaryStep.salary" type="number" step="0.01" class="block mt-1 w-full" wire:model="editingSalaryStep.salary" />
                <x-input-error for="editingSalaryStep.salary" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showEditModal', false)" class="mr-2">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button wire:click="save">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-salary-step-modal" wire:model.live="showDeleteModal">
        <x-card title="Delete Salary Step">
            <div class="px-8 py-5">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="rounded-full bg-red-100 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Delete Confirmation</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Are you sure you want to delete this salary step? This action cannot be undone.
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end space-x-3">
                        <x-button wire:click="$set('showDeleteModal', false)" type="button" class="bg-white text-gray-700 border-gray-300 hover:bg-gray-50">
                            Cancel
                        </x-button>
                        <x-button wire:click="delete" type="button" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                            Delete
                        </x-button>
                    </div>
                </div>
            </div>
        </x-card>
    </x-modal>

    <!-- Create Modal -->
    <x-modal name="create-salary-step-modal" wire:model="showCreateModal">
        <x-card title="New Salary Step">
            <div class="px-8 py-5">
                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div>
                            <x-native-select wire:model="editingSalaryStep.salary_grade_id" label="Salary Grade" required>
                                <option value="">Select Salary Grade</option>
                                @foreach($salaryGrades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->grade }}</option>
                                @endforeach
                            </x-native-select>
                            @error('editingSalaryStep.salary_grade_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input wire:model="editingSalaryStep.step" label="Step" type="number" min="1" max="8" required />
                            @error('editingSalaryStep.step') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input wire:model="editingSalaryStep.year" label="Year" type="number" min="2020" max="2050" required />
                            @error('editingSalaryStep.year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input wire:model="editingSalaryStep.salary" label="Salary" type="number" step="0.01" required />
                            @error('editingSalaryStep.salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end space-x-2">
                            <x-button wire:click="$set('showCreateModal', false)" type="button" class="bg-danger">
                                Cancel
                            </x-button>
                            <x-button type="submit" class="bg-main">
                                Save
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </x-card>
    </x-modal>

    <!-- Edit Modal -->
    <x-modal name="edit-salary-step-modal" wire:model="showEditModal">
        <x-card title="Edit Salary Step">
            <div class="px-8 py-5">
                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div>
                            <x-native-select wire:model="editingSalaryStep.salary_grade_id" label="Salary Grade" required>
                                <option value="">Select Salary Grade</option>
                                @foreach($salaryGrades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->grade }}</option>
                                @endforeach
                            </x-native-select>
                            @error('editingSalaryStep.salary_grade_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input wire:model="editingSalaryStep.step" label="Step" type="number" min="1" max="8" required />
                            @error('editingSalaryStep.step') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input wire:model="editingSalaryStep.year" label="Year" type="number" min="2020" max="2050" required />
                            @error('editingSalaryStep.year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-input wire:model="editingSalaryStep.salary" label="Salary" type="number" step="0.01" required />
                            @error('editingSalaryStep.salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end space-x-2">
                            <x-button wire:click="$set('showEditModal', false)" type="button" class="bg-danger">
                                Cancel
                            </x-button>
                            <x-button type="submit" class="bg-main">
                                Update
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </x-card>
    </x-modal>
</div>