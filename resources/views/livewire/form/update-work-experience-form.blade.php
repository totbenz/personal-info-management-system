<div>
    <div class="mt-5">
        <div>
            <div class="mt-2">
                @if (count($personnel->workExperiences))
                    @foreach ($old_work_experiences as $index => $old_work_experience)
                        <div class="mb-2 px-3 py-4 w-full space-x-3 border border-gray-400">
                            <div class="my-3 mx-3 flex space-x-2">
                                <div class="w-4/12 flex space-x-2">
                                    <div class="w-1/2">
                                        <x-input id="inclusive_from_{{ $index }}" type="date" wire:model="old_work_experiences.{{ $index }}.inclusive_from" name="old_work_experiences[{{ $index }}][inclusive_from]" label="Start Date" class="form-control" required/>
                                    </div>
                                    <div class="w-1/2">
                                        <x-input id="inclusive_to_{{ $index }}" type="date" wire:model="old_work_experiences.{{ $index }}.inclusive_to" name="old_work_experiences[{{ $index }}][inclusive_to]" label="End Date" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="w-4/12">
                                    <x-input id="title_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.title" name="old_work_experiences[{{ $index }}][title]" label="Position Title" class="form-control" required/>
                                </div>
                                <div class="w-4/12">
                                    <x-input id="company_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.company" name="old_work_experiences[{{ $index }}][company]" label="Department/Company" required/>
                                </div>
                            </div>
                            <div class="mb-3 flex space-x-2">
                                <div class="w-3/12">
                                    <x-input id="monthly_salary_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.monthly_salary" name="old_work_experiences[{{ $index }}][monthly_salary]" label="Monthly Salary" required/>
                                </div>
                                <div class="w-3/12">
                                    <x-input id="paygrade_step_increment_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.paygrade_step_increment" name="old_work_experiences[{{ $index }}][paygrade_step_increment]" label="Pay Grade/Step Increment" required/>
                                </div>
                                <div class="w-4/12">
                                    <x-input id="appointment_{{ $index }}" type="text" wire:model="old_work_experiences.{{ $index }}.appointment" name="old_work_experiences[{{ $index }}][appointment]" label="Appointment" required/>
                                </div>
                                <div class="w-2/12">
                                    <x-native-select wire:model="old_work_experiences.{{ $index }}.is_gov_service" class="form-control" label="Govn't Service?">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </x-native-select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- New Data Children -->
                @foreach ($new_work_experiences as $index => $work_experience)
                    <div class="mb-2 px-3 py-2 w-full space-x-3 h-40 border border-gray-200 rounded focus:outline-none">
                        <div class="mb-3 flex space-x-2">
                            <div class="w-4/12 flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="new_work_experiences.{{ $index }}.inclusive_from"
                                             wire:model="new_work_experiences.{{ $index }}.inclusive_from"
                                             name="new_work_experiences.{{ $index }}.inclusive_from"
                                             type="date"  label="Start Date" required/>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="new_work_experiences.{{ $index }}.inclusive_to"
                                             wire:model="new_work_experiences.{{ $index }}.inclusive_to"
                                             name="new_work_experiences.{{ $index }}.inclusive_to"
                                             type="date"  label="End Date" required/>
                                </div>
                            </div>
                            <div class="w-4/12">
                                <x-input id="new_work_experiences.{{ $index }}.title"
                                         wire:model="new_work_experiences.{{ $index }}.title"
                                         name="new_work_experiences.{{ $index }}.title"
                                         type="text"  label="Position Title" required/>
                            </div>
                            <div class="w-4/12">
                                <x-input id="new_work_experiences.{{ $index }}.company"
                                             wire:model="new_work_experiences.{{ $index }}.company"
                                             name="new_work_experiences.{{ $index }}.company"
                                             type="text"  label="Department/Company" required/>
                            </div>
                        </div>
                        <div class="mb-3 flex space-x-2">
                            <div class="w-3/12">
                                <x-input id="new_work_experiences.{{ $index }}.monthly_salary"
                                             wire:model="new_work_experiences.{{ $index }}.monthly_salary"
                                             name="new_work_experiences.{{ $index }}.monthly_salary"
                                             type="text"  label="Monthly Salary" required/>
                            </div>
                            <div class="w-3/12">
                                <x-input id="new_work_experiences.{{ $index }}.paygrade_step_increment"
                                             wire:model="new_work_experiences.{{ $index }}.paygrade_step_increment"
                                             name="new_work_experiences.{{ $index }}.paygrade_step_increment"
                                             type="number"  label="Pay Grade/Step Increment" required/>
                            </div>
                            <div class="w-4/12">
                                <x-input id="new_work_experiences.{{ $index }}.appointment"
                                             wire:model="new_work_experiences.{{ $index }}.appointment"
                                             name="new_work_experiences.{{ $index }}.appointment"
                                             type="text"  label="Appointment" required/>
                            </div>
                            <div class="w-2/12">
                                <x-native-select id="new_work_experiences.{{ $index }}.is_gov_service"
                                                 wire:model="new_work_experiences.{{ $index }}.is_gov_service"
                                                 name="new_work_experiences.{{ $index }}.is_gov_service" class="form-control" label="Govn't Service?">
                                    <option value="1" {{ $work_experience['is_gov_service'] == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $work_experience['is_gov_service'] == '0' ? 'selected' : '' }}>No</option>
                                </x-native-select>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="mt-3 flex space-x-3 items-center">
                    <div class="w-full">
                        <x-button wire:click.prevent="addField" label="Add New" class="w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-5 p-0 flex space-x-3 justify-end">
        <div class="w-2/12">
            <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
        </div>
        <div class="w-2/12">
            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
        </div>
    </div>
</div>
