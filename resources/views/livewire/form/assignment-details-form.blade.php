<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
    <div class="flex justify-between">
        <h4 class="font-bold text-2xl text-gray-darkest">Assignment Details</h4>
        <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
            <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>

                <p>Edit</p>
            </span>
        </button>
    </div>
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
                @if ($old_assignment_details != null)
                    <section>
                        @foreach ($old_assignment_details as $index => $old_assignment_detail)
                            <div class="mb-2 px-3 w-full space-x-3 h-14 border border-gray-200 rounded focus:outline-none">
                                <div class="mb-3 flex space-x-2">
                                    <div class="w-2/12 flex space-x-2">
                                        <x-input id="school_year_{{ $index }}" type="text" wire:model="old_assignment_details.{{ $index }}.school_year" name="old_assignment_details[{{ $index }}][school_year]" readonly/>
                                    </div>
                                    <div class="w-3/12">
                                        <x-input id="assignment_{{ $index }}" type="text" wire:model="old_assignment_details.{{ $index }}.assignment" name="old_assignment_details[{{ $index }}][assignment]" readonly/>
                                    </div>
                                    <div class="w-2/12">
                                        <x-input id="dtr_day_{{ $index }}" type="text" wire:model="old_assignment_details.{{ $index }}.dtr_day" name="old_assignment_details[{{ $index }}][dtr_day]" readonly/>
                                    </div>
                                    <div class="w-1/12">
                                        <x-input id="dtr_from_{{ $index }}" type="time" wire:model="old_assignment_details.{{ $index }}.dtr_from" name="old_assignment_details[{{ $index }}][dtr_from]" readonly/>
                                    </div>
                                    <div class="w-1/12">
                                        <x-input id="dtr_to_{{ $index }}" type="time" wire:model="old_assignment_details.{{ $index }}.dtr_to" name="old_assignment_details[{{ $index }}][dtr_to]" readonly/>
                                    </div>
                                    <div class="w-2/12">
                                        <x-input id="teaching_minutes_per_week_{{ $index }}" type="number" wire:model="old_assignment_details.{{ $index }}.teaching_minutes_per_week" name="old_assignment_details[{{ $index }}][teaching_minutes_per_week]" readonly/>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </section>
                @else
                    <p class="mt-3 w-full py-2 font-medium text-xs text-center bg-gray-200">No Assignment Details Found</p>
                @endif
            </div>
        </div>
    </div>
    @else
        <div class="flex justify-between">
            <h4 class="font-bold text-2xl text-gray-darkest">Edit Assignment Details</h4>

            <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                    <p>Back</p>
                </span>
            </button>
        </div>
        @livewire('form.update-assignment-details-form', ['id' => $personnel->id])
    @endif
</div>
