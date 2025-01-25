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
            </div>
            <div class="mt-2">
                <!-- Old Data Children -->
                @foreach ($old_assignment_details as $index => $old_assignment_detail)
                    <div class="mb-2 px-3 w-full space-x-3 h-40 border border-gray-200 rounded focus:outline-none">
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
                        </div>
                    </div>
                @endforeach

                <!-- New Data Children -->
                @foreach ($new_assignment_details as $index => $new_assignment_detail)
                    <div class="mb-2 px-3 w-full space-x-3 h-14 border border-gray-200 rounded focus:outline-none">
                        <div class="mb-3 flex space-x-2">
                            <div class="w-2/12 flex space-x-2">
                                <x-input id="school_year_{{ $index }}" type="text" wire:model="new_assignment_details.{{ $index }}.school_year" name="new_assignment_details[{{ $index }}][school_year]" />
                            </div>
                            <div class="w-3/12">
                                <x-input id="assignment_{{ $index }}" type="text" wire:model="new_assignment_details.{{ $index }}.assignment" name="new_assignment_details[{{ $index }}][assignment]" />
                            </div>
                            <div class="w-2/12">
                                <x-input id="dtr_day_{{ $index }}" type="text" wire:model="new_assignment_details.{{ $index }}.dtr_day" name="new_assignment_details[{{ $index }}][dtr_day]" />
                            </div>
                            <div class="w-1/12">
                                <x-input id="dtr_from_{{ $index }}" type="time" wire:model="new_assignment_details.{{ $index }}.dtr_from" name="new_assignment_details[{{ $index }}][dtr_from]" />
                            </div>
                            <div class="w-1/12">
                                <x-input id="dtr_to_{{ $index }}" type="time" wire:model="new_assignment_details.{{ $index }}.dtr_to" name="new_assignment_details[{{ $index }}][dtr_to]" />
                            </div>
                            <div class="w-2/12">
                                <x-input id="teaching_minutes_per_week_{{ $index }}" type="number" wire:model="new_assignment_details.{{ $index }}.teaching_minutes_per_week" name="new_assignment_details[{{ $index }}][teaching_minutes_per_week]" />
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
