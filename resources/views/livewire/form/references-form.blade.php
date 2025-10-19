<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
    <div class="flex justify-between">
        <h4 class="font-bold text-2xl text-gray-darkest">References</h4>
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
            <div class="w-full flex h-10 border border-gray-100 bg-gray-lightest items-center">
                <div class="w-3/12 px-4">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Fullname</span>
                </div>
                <div class="w-4/12 px-4">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Address</span>
                </div>
                <div class="w-2/12 px-4">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Phone</span>
                </div>
            </div>
            <div class="mt-2">
                @if (count($personnel->references))
                    @foreach ($personnel->references as $index => $old_reference)
                        <div class="mb-2 w-full flex h-12 border border-gray-200 rounded focus:outline-none">
                            <div class="w-3/12 px-4 flex items-center">
                                <x-input id="full_name_{{ $index }}" type="text" wire:model="old_references.{{ $index }}.full_name" name="old_references[{{ $index }}][full_name]" class="w-full" required/>
                            </div>
                            <div class="w-4/12 px-4 flex items-center">
                                <x-input id="address_{{ $index }}" type="text" wire:model="old_references.{{ $index }}.address" name="old_references[{{ $index }}][address]" class="w-full" required/>
                            </div>
                            <div class="w-2/12 px-4 flex items-center">
                                <x-input id="tel_no_{{ $index }}" type="text" wire:model="old_references.{{ $index }}.tel_no" name="old_references[{{ $index }}][tel_no]" class="w-full" required/>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="w-full">
                        <p class="mt-3 w-full py-2 font-medium text-xs text-center bg-gray-200">No References Found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
        <div class="flex justify-between">
            <h4 class="font-bold text-2xl text-gray-darkest">Edit References</h4>

            <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                    <p>Back</p>
                </span>
            </button>
        </div>
        @livewire('form.update-references-form', ['id' => $personnel->id])
    @endif
</div>
