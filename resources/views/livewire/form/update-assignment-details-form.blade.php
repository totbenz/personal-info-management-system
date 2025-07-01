<div>
    <div class="mt-5">
        <div class="mt-3">
            <div class="w-full flex space-x-2 h-10 border border-gray-100 bg-gray-lightest items-center">
                <h6 class="w-2/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">School Year</span>
                </h6>
                <h6 class="w-3/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Assignment</span>
                </h6>
                <div class="w-4/12 leading-snug">
                    <div class="text-center items-center text-xs text-gray-dark font-semibold uppercase">DTR</div>
                    <div class="flex">
                        <h6 class="w-2/4 text-center">
                            <span class="text-xs text-gray-dark font-semibold uppercase">Day</span>
                        </h6>
                        <h6 class="w-1/4 text-center">
                            <span class="text-xs text-gray-dark font-semibold uppercase">From</span>
                        </h6>
                        <h6 class="w-1/4">
                            <span class="text-xs text-gray-dark font-semibold uppercase">To</span>
                        </h6>
                    </div>
                </div>
                <h6 class="w-2/12">
                    <span class="text-center text-xs text-gray-dark font-semibold uppercase">Minutes per Week</span>
                </h6>
                <h6 class="w-1/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Action</span>
                </h6>
            </div>
            <div class="mt-2">
                <!-- Old Data Children -->
                @foreach ($old_assignment_details as $index => $old_assignment_detail)
                    <div class="mb-2 px-3 w-full space-x-3 h-14 border border-gray-200 rounded focus:outline-none">
                        <div class="mb-3 flex space-x-2">
                            <div class="w-2/12 flex space-x-2">
                                <x-input id="school_year_{{ $index }}" type="text" wire:model="old_assignment_details.{{ $index }}.school_year" name="old_assignment_details[{{ $index }}][school_year]" />
                            </div>
                            <div class="w-3/12">
                                <x-input id="assignment_{{ $index }}" type="text" wire:model="old_assignment_details.{{ $index }}.assignment" name="old_assignment_details[{{ $index }}][assignment]" />
                            </div>
                            <div class="w-2/12">
                                <x-input id="dtr_day_{{ $index }}" type="text" wire:model="old_assignment_details.{{ $index }}.dtr_day" name="old_assignment_details[{{ $index }}][dtr_day]" />
                            </div>
                            <div class="w-1/12">
                                <x-input id="dtr_from_{{ $index }}" type="time" wire:model="old_assignment_details.{{ $index }}.dtr_from" name="old_assignment_details[{{ $index }}][dtr_from]" />
                            </div>
                            <div class="w-1/12">
                                <x-input id="dtr_to_{{ $index }}" type="time" wire:model="old_assignment_details.{{ $index }}.dtr_to" name="old_assignment_details[{{ $index }}][dtr_to]" />
                            </div>
                            <div class="w-2/12">
                                <x-input id="teaching_minutes_per_week_{{ $index }}" type="number" wire:model="old_assignment_details.{{ $index }}.teaching_minutes_per_week" name="old_assignment_details[{{ $index }}][teaching_minutes_per_week]" />
                            </div>
                            <div class="w-1/12 flex justify-center">
                                <button wire:click.prevent="removeOldField({{ $index }})" type="button" class="text-red-600 hover:text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- New Data Children -->
                @foreach ($new_assignment_details as $index => $new_assignment_detail)
                    <div class="mb-2 px-3 w-full space-x-3 h-14 border border-gray-200 rounded focus:outline-none">
                        <div class="mb-3 flex space-x-2">
                            <div class="w-2/12 flex space-x-2">
                                <x-input id="new_school_year_{{ $index }}" type="text" wire:model="new_assignment_details.{{ $index }}.school_year" name="new_assignment_details[{{ $index }}][school_year]" />
                            </div>
                            <div class="w-3/12">
                                <x-input id="new_assignment_{{ $index }}" type="text" wire:model="new_assignment_details.{{ $index }}.assignment" name="new_assignment_details[{{ $index }}][assignment]" />
                            </div>
                            <div class="w-2/12">
                                <x-input id="new_dtr_day_{{ $index }}" type="text" wire:model="new_assignment_details.{{ $index }}.dtr_day" name="new_assignment_details[{{ $index }}][dtr_day]" />
                            </div>
                            <div class="w-1/12">
                                <x-input id="new_dtr_from_{{ $index }}" type="time" wire:model="new_assignment_details.{{ $index }}.dtr_from" name="new_assignment_details[{{ $index }}][dtr_from]" />
                            </div>
                            <div class="w-1/12">
                                <x-input id="new_dtr_to_{{ $index }}" type="time" wire:model="new_assignment_details.{{ $index }}.dtr_to" name="new_assignment_details[{{ $index }}][dtr_to]" />
                            </div>
                            <div class="w-2/12">
                                <x-input id="new_teaching_minutes_per_week_{{ $index }}" type="number" wire:model="new_assignment_details.{{ $index }}.teaching_minutes_per_week" name="new_assignment_details[{{ $index }}][teaching_minutes_per_week]" />
                            </div>
                            <div class="w-1/12 flex justify-center">
                                <button wire:click.prevent="removeNewField({{ $index }})" type="button" class="text-red-600 hover:text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
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
            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
        </div>
    </div>
</div>
