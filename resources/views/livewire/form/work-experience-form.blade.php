<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
        <section>
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Work Experience</h4>

                <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                        <p>Edit</p>
                    </span>
                </button>
            </div>
            <div>
                <div class="mt-10">
                    @if ($old_work_experiences != null)
                        <section>
                            @foreach ($old_work_experiences as $index => $old_work_experience)
                                <div class="mb-2 px-3 w-full space-x-3 h-48 border border-gray-200 rounded focus:outline-none">
                                    <div class="mb-3 flex space-x-2">
                                        <div class="w-4/12 flex space-x-2">
                                            <div class="w-1/2">
                                                <x-input id="inclusive_from_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.inclusive_from" name="old_work_experiences[{{ $index }}][inclusive_from]" label="Start Date" readonly/>
                                            </div>
                                            <div class="w-1/2">
                                                <x-input id="inclusive_to_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.inclusive_to" name="old_work_experiences[{{ $index }}][inclusive_to]" label="End Date" readonly/>
                                            </div>
                                        </div>
                                        <div class="w-4/12">
                                            <x-input id="title_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.title" name="old_work_experiences[{{ $index }}][title]" label="Position Title" readonly/>
                                        </div>
                                        <div class="w-4/12">
                                            <x-input id="company_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.company" name="old_work_experiences[{{ $index }}][company]" label="Department/Company" readonly/>
                                        </div>
                                    </div>
                                    <div class="mb-3 flex space-x-2">
                                        <div class="w-3/12">
                                            <x-input id="monthly_salary_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.monthly_salary" name="old_work_experiences[{{ $index }}][monthly_salary]" label="Monthly Salary" readonly/>
                                        </div>
                                        <div class="w-3/12">
                                            <x-input id="paygrade_step_increment_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.paygrade_step_increment" name="old_work_experiences[{{ $index }}][paygrade_step_increment]" label="Pay Grade/Step Increment" readonly/>
                                        </div>
                                        <div class="w-4/12">
                                            <x-input id="appointment_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.appointment" name="old_work_experiences[{{ $index }}][appointment]" label="Appointment" readonly/>
                                        </div>
                                        <div class="w-2/12">
                                            <x-native-select wire:model="old_work_experiences.{{ $index }}.is_gov_service" class="form-control" label="Govn't Service?" readonly>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </x-native-select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </section>
                    @else
                        <p class="mt-3 w-full py-2 font-medium text-xs text-center bg-gray-200">No Work Experience Found</p>
                    @endif
                </div>
            </div>
        </section>
    @else
        @isset($personnel)
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Edit Work Experience</h4>

                <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>

                        <p>Back</p>
                    </span>
                </button>
            </div>
            @livewire('form.update-work-experience-form', ['id' => $personnel->id])
        @endisset
    @endif
</div>
