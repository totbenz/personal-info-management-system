<div>
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
                <h6 class="w-3/12 text-center">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Position Title</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-center text-xs text-gray-dark font-semibold uppercase">No. of Hours</span>
                </h6>
            </div>
            <div class="mt-2">
                <!-- Old Data Children -->
                @foreach ($old_voluntary_works as $index => $old_voluntary_work)
                    <div class="mb-2">
                        <div class="px-3 bg-gray-100 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
                            <div class="w-2/12 flex space-x-2">
                                <x-input id="old_voluntary_works.{{ $index }}.inclusive_from"
                                                 wire:model="old_voluntary_works.{{ $index }}.inclusive_from" name="old_voluntary_works.{{ $index }}.inclusive_from"
                                                 type="date" class="form-control" required/>
                            </div>
                            <div class="w-2/12">
                                <x-input id="old_voluntary_works.{{ $index }}.organization"
                                                 wire:model="old_voluntary_works.{{ $index }}.inclusive_to" name="old_voluntary_works.{{ $index }}.inclusive_to"
                                                 type="date" class="form-control" required/>
                            </div>
                            <div class="w-3/12">
                                <x-input id="old_voluntary_works.{{ $index }}.organization"
                                                 wire:model="old_voluntary_works.{{ $index }}.organization" name="old_voluntary_works.{{ $index }}.organization"
                                                 type="text" class="form-control" required/>
                            </div>
                            <div class="w-3/12">
                                <x-input id="old_voluntary_works.{{ $index }}.position"
                                                 wire:model="old_voluntary_works.{{ $index }}.position" name="old_voluntary_works.{{ $index }}.position"
                                                 type="text" class="form-control" required/>
                            </div>
                            <div class="w-1/12">
                                <x-input id="old_voluntary_works.{{ $index }}.hours"
                                                 wire:model="old_voluntary_works.{{ $index }}.hours" name="old_voluntary_works.{{ $index }}.hours"
                                                 type="text" class="form-control" required/>
                            </div>
                            <div class="w-1/12 pe-3 text-xs text-center">
                                <button wire:click="confirmRemoveOldField({{ $index }})" wire:confirm="Are you sure you want to delete this child?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- New Data Children -->
                @foreach ($new_voluntary_works as $index => $new_voluntary_work)
                    <div class="mb-2">
                        <div class="px-3 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
                            <div class="w-2/12 flex space-x-2">
                                <x-input id="new_voluntary_works.{{ $index }}.inclusive_from"
                                                 wire:model="new_voluntary_works.{{ $index }}.inclusive_from" name="new_voluntary_works.{{ $index }}.inclusive_from"
                                                 type="date" class="form-control" required/>
                            </div>
                            <div class="w-2/12">
                                <x-input id="new_voluntary_works.{{ $index }}.inclusive_to"
                                                 wire:model="new_voluntary_works.{{ $index }}.inclusive_to" name="new_voluntary_works.{{ $index }}.inclusive_to"
                                                 type="date" class="form-control" required/>
                            </div>
                            <div class="w-3/12">
                                <x-input id="new_voluntary_works.{{ $index }}.organization"
                                                 wire:model="new_voluntary_works.{{ $index }}.organization" name="new_voluntary_works.{{ $index }}.organization"
                                                 type="text" class="form-control" required/>
                            </div>
                            <div class="w-3/12">
                                <x-input id="new_voluntary_works.{{ $index }}.position"
                                                 wire:model="new_voluntary_works.{{ $index }}.position" name="new_voluntary_works.{{ $index }}.position"
                                                 type="text" class="form-control" required/>
                            </div>
                            <div class="w-1/12">
                                <x-input id="new_voluntary_works.{{ $index }}.hours"
                                                 wire:model="new_voluntary_works.{{ $index }}.hours" name="new_voluntary_works.{{ $index }}.hours"
                                                 type="number" class="form-control" required/>
                            </div>
                            <div class="w-1/12 pe-3 text-xs text-center">
                                <button wire:click.prevent="removeNewField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
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
