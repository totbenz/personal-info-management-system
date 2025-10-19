<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
        <section>
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Other Information</h4>

                <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                        <p>Edit</p>
                    </span>
                </button>
            </div>

            <div class="flex space-x-5">
                <div class="w-3/12">
                    <div>
                        <h5 class="mt-5 mb-3 font-semibold text-lg text-center text-gray-darkest">Special Skills and Hobbies</h5>
                        <div class="mt-3">
                            @if ($old_skills != null)
                                @foreach ($old_skills as $index => $old_skill)
                                    <div class="mb-2">
                                        <x-input type="text" wire:model="old_skills.{{ $index }}.name" name="old_skills[{{ $index }}][name]" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                @endforeach
                            @else
                                <p class="mt-1 w-full py-2.5 font-medium text-xs text-center border border-gray-200 bg-gray-50 drop-shadow-sm">No Special Skills and Hobbies Found</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="w-5/12">
                    <div>
                        <h5 class="mt-5 mb-3 font-semibold text-lg text-center text-gray-darkest">Non-academic Distinctions / Recognition</h5>
                        <div class="mt-3">
                            @if ($old_nonacademic_distinctions != null)
                                @foreach ($old_nonacademic_distinctions as $index => $old_nonacademic_distinction)
                                    <div class="mb-2">
                                        <x-input type="text" wire:model="old_nonacademic_distinctions.{{ $index }}.name" name="old_nonacademic_distinctions[{{ $index }}][name]" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                @endforeach
                            @else
                                <p class="mt-1 w-full py-2.5 font-medium text-xs text-center border border-gray-200 bg-gray-50 drop-shadow-sm">No Non-academic Distinctions/Recognition Found</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="w-4/12">
                    <div>
                        <h5 class="mt-5 mb-3 font-semibold text-lg text-center text-gray-darkest">Association Membership/Organization</h5>
                        <div class="mt-3">
                            @if ($old_associations != null)
                                @foreach ($old_associations as $index => $old_association)
                                    <div class="mb-2">
                                        <x-input type="text" wire:model="old_associations.{{ $index }}.name" name="old_associations[{{ $index }}][name]" class="bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                @endforeach
                            @else
                                <p class="mt-1 w-full py-2.5 font-semibold text-xs text-center border border-gray-200 bg-gray-50 drop-shadow-sm">No Association Membership/Organization Found</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </section>
    @else
        @isset($personnel)
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Edit Other Information</h4>

                <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>

                        <p>Back</p>
                    </span>
                </button>
            </div>
            @livewire('form.update-other-information-form', ['id' => $personnel->id])
        @endisset
    @endif
</div>
