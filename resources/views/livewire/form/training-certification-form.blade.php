<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
        <section>
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Training & Certifications</h4>

                <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                        <p>Edit</p>
                    </span>
                </button>
            </div>

            @if ($old_training_certifications != null)
            <section class="mt-2">
                @foreach ($old_training_certifications as $index => $old_training_certification)
                    {{-- <div class="mb-2 px-3 py-2 w-full space-x-3 h-40 border border-gray-200 rounded focus:outline-none">
                        <div class="mb-3 flex space-x-5">
                            <div class="w-6/12 text-xs">
                                <x-input type="text" wire:model="old_training_certifications.{{ $index }}.training_seminar_title" name="old_training_certification[{{ $index }}][training_seminar_title]" class="form-control" readonly/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input type="date" wire:model="old_training_certifications.{{ $index }}.inclusive_from" name="old_training_certification[{{ $index }}][inclusive_from]" class="form-control" readonly/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input type="date" wire:model="old_training_certifications.{{ $index }}.inclusive_to" name="old_training_certification[{{ $index }}][inclusive_to]" class="form-control" readonly/>
                            </div>
                        </div>
                        <div class="mb-3 flex space-x-5 bg-pink-400">
                            <div class="w-6/12 text-xs">
                                <x-input type="text" wire:model="old_training_certifications.{{ $index }}.sponsored" name="old_training_certification[{{ $index }}][sponsored]" class="text-xs" readonly/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input type="text" wire:model="old_training_certifications.{{ $index }}.type" name="old_training_certification[{{ $index }}][type]" class="text-xs" readonly/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input type="number" wire:model="old_training_certifications.{{ $index }}.hours" name="old_training_certification[{{ $index }}][hours]" class="text-xs" readonly/>
                            </div>
                        </div>
                    </div> --}}
                    <div class="flex justify-between">
                        <div class="w-11/12 mb-4 px-3 py-3 bg-gray-50 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-6/12 text-xs">
                                    <x-input type="text" wire:model="old_training_certifications.{{ $index }}.training_seminar_title" name="old_training_certification[{{ $index }}][training_seminar_title]" label="Development Interventions/ Training Programs" class="form-control" readonly/>
                                </div>
                                <div class="w-6/12 text-xs">
                                    <x-input type="text" wire:model="old_training_certifications.{{ $index }}.sponsored" name="old_training_certification[{{ $index }}][sponsored]" class="text-xs" label="Sponsored" readonly/>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center space-x-3">
                                <div class="w-3/12 text-xs">
                                    <x-input type="date" wire:model="old_training_certifications.{{ $index }}.inclusive_from" name="old_training_certification[{{ $index }}][inclusive_from]" class="form-control" label="Start Date" readonly/>
                                </div>
                                <div class="w-3/12 text-xs">
                                    <x-input type="date" wire:model="old_training_certifications.{{ $index }}.inclusive_to" name="old_training_certification[{{ $index }}][inclusive_to]" class="form-control" label="End Date" readonly/>
                                </div>
                                <div class="w-3/12 text-xs">
                                    <x-input type="text" wire:model="old_training_certifications.{{ $index }}.type" name="old_training_certification[{{ $index }}][type]" class="text-xs" label="Type" readonly/>
                                </div>
                                <div class="w-3/12 text-xs">
                                    <x-input type="number" wire:model="old_training_certifications.{{ $index }}.hours" name="old_training_certification[{{ $index }}][hours]" class="text-xs" label="Hours" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/12 mb-4 px-3 py-3">
                        </div>
                    </div>
                @endforeach
            </section>
            @else
                <p class="mt-1 w-full py-2.5 font-medium text-xs text-center border border-gray-200 bg-gray-50 drop-shadow-sm">No Training / Certifications Found</p>
            @endif
        </section>
    @else
        @isset($personnel)
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Edit Training & Certifications</h4>

                <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>

                        <p>Back</p>
                    </span>
                </button>
            </div>
            @livewire('form.update-training-certification-form', ['id' => $personnel->id])
        @endisset
    @endif
</div>
