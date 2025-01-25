<div>
    <div class="flex space-x-5">
        <div class="w-3/12">
            <div>
                <h5 class="mt-5 mb-3 font-semibold text-lg text-center text-gray-darkest">Special Skills and Hobbies</h5>
                <div class="mt-3">
                    @foreach ($old_skills as $index => $skill)
                        <div class="flex space-x-2 items-center">
                            <div class="w-11/12 mb-2">
                                <x-input type="text" wire:model="old_skills.{{ $index }}.name" name="old_skills[{{ $index }}][name]" class="form-control" required/>
                            </div>
                            <div class="w-1/12 pe-3 text-xs text-center">
                                <button wire:click="confirmRemoveOldField({{ $index }}, 'special_skill')" wire:confirm="Are you sure you want to delete this data?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <!-- New Skill -->
                    @foreach ($new_skills as $index => $new_skill)
                        <div class="mt-3 flex space-x-2 items-center">
                            <div class="w-11/12">
                                <x-input type="text" wire:model="new_skills.{{ $index }}.name" name="new_skills[{{ $index }}][name]" class="form-control" required/>
                            </div>
                            <div class="w-1/12 pe-3 text-xs text-center">
                                <button wire:click="removeNewField({{ $index }}, 'special_skill')" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <x-button wire:click.prevent="addField('special_skill')" label="New" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
                </div>
            </div>
        </div>
        <div class="w-5/12">
            <div>
                <h5 class="mt-5 mb-3 font-semibold text-lg text-center text-gray-darkest">Non-academic Distinctions / Recognition</h5>
                <div class="mt-3">
                    @if ($personnel->nonacademicDistinctions)
                        @foreach ($old_nonacademic_distinctions as $index => $old_nonacademic_distinction)
                            <div class="flex space-x-2 items-center">
                                <div class="w-11/12 mb-2">
                                    <x-input type="text" wire:model="old_nonacademic_distinctions.{{ $index }}.name" name="old_nonacademic_distinctions[{{ $index }}][name]" class="form-control" required/>
                                </div>
                                <div class="w-1/12 pe-3 text-xs text-center">
                                    <button wire:click="confirmRemoveOldField({{ $index }}, 'nonacademic_distinction')" wire:confirm="Are you sure you want to delete this data?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- New Skill -->
                    @foreach ($new_nonacademic_distinctions as $index => $new_nonacademic_distinction)
                        <div class="mt-3 flex space-x-2 items-center">
                            <div class="w-11/12">
                                <x-input type="text" wire:model="new_nonacademic_distinctions.{{ $index }}.name" name="new_nonacademic_distinctions[{{ $index }}][name]" class="form-control" required/>
                            </div>
                            <div class="w-1/12 pe-3 text-xs text-center">
                                <button wire:click="removeNewField({{ $index }}, 'nonacademic_distinction')" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <x-button wire:click.prevent="addField('nonacademic_distinction')" label="New" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
                </div>
            </div>
        </div>
        <div class="w-4/12">
            <div>
                <h5 class="mt-5 mb-3 font-semibold text-lg text-center text-gray-darkest">Association Membership/Organization</h5>
                <div class="mt-3">
                    @if ($personnel->associations)
                        @foreach ($old_associations as $index => $old_association)
                            <div class="flex space-x-2 items-center">
                                <div class="w-11/12 mb-2">
                                    <x-input type="text" id="old_associations.{{ $index }}.name" wire:model="old_associations.{{ $index }}.name" name="old_associations[{{ $index }}][name]" class="form-control" required/>
                                </div>
                                <div class="w-1/12 pe-3 text-xs text-center">
                                    <button wire:click="confirmRemoveOldField({{ $index }}, 'association')" wire:confirm="Are you sure you want to delete this data?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- New Skill -->
                    @foreach ($new_associations as $index => $new_association)
                        <div class="mt-3 flex space-x-2 items-center">
                            <div class="w-11/12">
                                <x-input type="text" id="new_associations.{{ $index }}.name" wire:model="new_associations.{{ $index }}.name" name="new_associations[{{ $index }}][name]" class="form-control" required/>
                            </div>
                            <div class="w-1/12 pe-3 text-xs text-center">
                                <button wire:click="removeNewField({{ $index }}, 'association')" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <x-button wire:click.prevent="addField('association')" label="New" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
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
