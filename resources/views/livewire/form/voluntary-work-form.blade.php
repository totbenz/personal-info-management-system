<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
    <div class="flex justify-between">
        <h4 class="font-bold text-2xl text-gray-darkest">Voluntary Work</h4>

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
                    <span class="text-xs text-gray-dark font-semibold uppercase">Start Date</span>
                </h6>
                <h6 class="w-2/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">End Date</span>
                </h6>
                <h6 class="w-3/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">NAME & ADDRESS OF ORGANIZATION  </span>
                </h6>
                <h6 class="w-3/12">
                    <span class="text-center text-xs text-gray-dark font-semibold uppercase">Position Title</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-center text-xs text-gray-dark font-semibold uppercase">No. of Hours</span>
                </h6>
            </div>
            <div class="mt-2">
                @if ($old_voluntary_works != null)
                    <section>
                        @foreach ($old_voluntary_works as $index => $old_voluntary_work)
                            <div class="mb-2 w-full">
                                <div class="px-3 mb-3 flex items-center space-x-4  justify-between h-12 border border-gray-200 rounded focus:outline-none">
                                    <div class="w-2/12 flex space-x-2">
                                        <x-input id="inclusive_from_{{ $index }}" type="text" wire:model="old_voluntary_works.{{ $index }}.inclusive_from" name="old_voluntary_works[{{ $index }}][inclusive_from]" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                    <div class="w-2/12">
                                        <x-input id="inclusive_to_{{ $index }}" type="text" wire:model="old_voluntary_works.{{ $index }}.inclusive_to" name="old_voluntary_works[{{ $index }}][inclusive_to]" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                    <div class="w-3/12">
                                        <x-input id="organization{{ $index }}" type="text" wire:model="old_voluntary_works.{{ $index }}.organization" name="old_voluntary_works[{{ $index }}][organization]" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                    <div class="w-3/12">
                                        <x-input id="position_{{ $index }}" type="text" wire:model="old_voluntary_works.{{ $index }}.position" name="old_voluntary_works[{{ $index }}][position]" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                    <div class="w-2/12">
                                        <x-input id="old_voluntary_works.{{ $index }}.hours"
                                                 wire:model="old_voluntary_works.{{ $index }}.hours" name="old_voluntary_works.{{ $index }}.hours"
                                                 type="text" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </section>
                @else
                    <p class="mt-3 w-full py-2 font-medium text-xs text-center bg-gray-200">No Voluntary Work Found</p>
                @endif
            </div>
        </div>
    </div>
    @else
        <div class="flex justify-between">
            <h4 class="font-bold text-2xl text-gray-darkest">Edit Voluntary Work</h4>

            <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                    <p>Back</p>
                </span>
            </button>
        </div>
        @livewire('form.update-voluntary-work-form', ['id' => $personnel->id])
    @endif
</div>

{{-- <div class="mx-auto pt-3 pb-10 px-5">
    <h4 class="font-bold text-2xl text-gray-darkest">Work Experience</h4>
    <div class="mt-5" x-data='civilServiceFields()'>
        <div class="mt-3">
            <div class="mt-2">
                @if ($personnel->workExperience)
                    @foreach ($personnel->workExperience as $work_experience)
                        <div class="mb-2 px-3 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
                            <div class="w-3/12">
                                <p class="text-base font-medium leading-none text-gray-700 mr-2">
                                {{ $work_experience->title }}
                                </p>
                            </div>
                            <div class="w-1/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $work_experience->company }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ date('m-d-Y', strtotime($work_experience->inclusive_from)) }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ date('m-d-Y', strtotime($work_experience->inclusive_to)) }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $work_experience->monthly_salary }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $work_experience->paygrade_step_increment }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $work_experience->appointment }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $work_experience->is_gov_service }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="w-full">
                        <p class="leading-none text-gray-600">No Work Experiences Found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> --}}


{{-- <div class="mx-auto pt-3 pb-10 px-5"  x-data='voluntaryWorkFields()'>
    <h4 class="font-bold text-2xl text-gray-darkest">Voluntary Work</h4>
    <div class="mt-5">
        <div class="mt-3">
            <div class="w-full flex space-x-1 h-12 border border-gray-100 bg-gray-lightest items-center">
                <h6 class="ps-4 w-3/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Name & Address Of Organization</span>
                </h6>
                <h6 class="w-3/12 text-center">
                    <span class="text-xs first-line:text-center text-gray-dark font-semibold uppercase">Position/Nature Of Work</span>
                </h6>
                <h6 class="w-4/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Inclusive Dates</span>
                    <div class="flex item-center">
                        <div class="w-2/4">
                            <span class="text-xs text-gray-dark font-semibold uppercase">From</span>
                        </div>
                        <div class="w-2/4">
                            <span class="text-xs text-gray-dark font-semibold uppercase">To</span>
                        </div>
                    </div>
                </h6>
                <h6 class="w-1/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Number of Hours</span>
                </h6>
            </div>
            <div class="mt-2">
                <template x-for="(new_voluntary_work, index) in new_voluntary_works" :key="index">
                    <div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none"
                            x-cloak
                            x-transition:enter="transition ease-in-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in-out duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95">
                            <div class="w-3/12 text-xs">
                                <x-input x-model="new_voluntary_work.organization_name" type="text" name=" new_voluntary_work.organization_name[]" class="text-xs" required/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input x-model=" new_voluntary_work.position" type="text" name=" new_voluntary_work.position[]" class="text-xs" required/>
                            </div>
                            <div class="w-4/12 text-xs">
                                <div class="flex space-x-2 justify-center">
                                    <div>
                                        <x-input x-model=" new_voluntary_work.from" type="date" name=" new_voluntary_work.from[]" class="text-xs" required/>
                                    </div>
                                    <div>
                                        <x-input x-model=" new_voluntary_work.to" type="date" name=" new_voluntary_work.to[]" class="text-xs" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="w-1/12 text-xs">
                                <x-input x-model="new_voluntary_work.number_of_hours" type="text" name=" new_voluntary_work.number_of_hours[]" class="text-xs" required/>
                            </div>
                            <div class="w-1/12 text-xs">
                                <button x-show="new_voluntary_works.length > 1" @click="removeField()" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="mt-3 flex space-x-3 items-center">
            <div class="w-full">
                <button @click.prevent="addNewField()" class="py-2 w-full text-base bg-main text-white tracking-wide font-medium rounded hover:bg-main_hover hover:text-white duration-300 focus:outline-none">New Voluntary Work</button>
            </div>
        </div>
    </div>
</div> --}}
