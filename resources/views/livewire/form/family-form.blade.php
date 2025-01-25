<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
        <section>
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Family</h4>
                <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                        <p>Edit</p>
                    </span>
                </button>
            </div>

            <div>
                <div class="mt-5 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/4">
                        <x-input id="fathers_first_name" wire:model="fathers_first_name" label="Father's First Name" type="text" name="fathers_first_name" class="bg-gray-50 border-gray-300" readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="fathers_middle_name" wire:model="fathers_middle_name" label="Father's Middle Name" type="text" name="fathers_middle_name" class="bg-gray-50 border-gray-300" readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="fathers_last_name" wire:model="fathers_last_name" label="Father's Last Name" type="text" name="fathers_last_name" class="bg-gray-50 border-gray-300" readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="fathers_name_ext" wire:model="fathers_name_ext" label="Father's Name Extension" type="text" name="name_ext" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                </div>
                <div class="mt-5 mb-4 p-0 flex space-x-3">
                    <span class="w-1/4">
                        <x-input id="mothers_first_name" wire:model="mothers_first_name" label="Mother's First Name" type="text" name="mothers_first_name" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="mothers_middle_name" wire:model="mothers_middle_name" label="Mother's Middle Name" type="text" name="mothers_middle_name" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="mothers_last_name" wire:model="mothers_last_name" label="Mother's Maiden Name" type="text" name="mothers_last_name" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/4">
                    </span>
                </div>
            </div>

            <div class="mt-10">
                <h5 class="mt-5 mb-3 font-bold text-xl text-gray-darkest">Spouse</h5>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/4">
                        <x-input id="spouse_first_name" wire:model="spouse_first_name" label="First Name" type="texr" name="spouse_first_name" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="spouse_middle_name" wire:model="spouse_middle_name" label="Middle Name" type="text" name="spouse_middle_name" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="spouse_last_name" wire:model="spouse_last_name" label="Last Name" type="text" name="spouse_last_name" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="spouse_name_ext" wire:model="spouse_name_ext" label="Name Extension" type="text" name="spouse_name_ext" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/2">
                        <x-input id="spouse_occupation" wire:model="spouse_occupation" label="Occupation" type="text" name="spouse_occupation" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/2">
                        <x-input id="spouse_business_name" wire:model="spouse_business_name" label="Employer/Business Name" type="text" name="spouse_business_name" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/2">
                        <x-input id="spouse_business_address" wire:model="spouse_business_address" label="Business Address" type="text" name="spouse_business_address" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                    <span class="w-1/2">
                        <x-input id="spouse_tel_no" wire:model="spouse_tel_no" label="Telephone No." type="text" name="spouse_tel_no" class="bg-gray-50 border-gray-300"  readonly/>
                    </span>
                </div>
            </div>

            <div class="mt-10">
                <h5 class="mt-5 mb-3 font-bold text-xl text-gray-darkest">Children</h5>
                <div class="mt-5">
                    <div class="mt-3">
                        <div class="ps-5 w-full flex space-x-3 h-10 border border-gray-100 bg-gray-lightest items-center">
                            <h6 class="w-9/12">
                                <span class="text-xs text-gray-dark font-semibold uppercase">Full Name</span>
                            </h6>
                            <h6 class="w-2/12">
                                <span class="text-xs text-gray-dark font-semibold uppercase">Date of Birth</span>
                            </h6>
                        </div>
                        <div class="mt-2">
                            @if (count($personnel->children))
                                @foreach ($personnel->children as $child)
                                <div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none">
                                    <div class="w-8/12 ps-3 text-xs">
                                        <div class="sm:flex space-x-1 rounded-md border border-gray-300">
                                            <x-input type="text" value="{{ $child->first_name }}" class="w-[14rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0 bg-gray-50 border-gray-300" readonly/>
                                            <x-input type="text" value="{{ $child->middle_name }}" class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0 bg-gray-50 border-gray-300" readonly/>
                                            <x-input type="text" value="{{ $child->last_name }}" class="w-[14rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0 bg-gray-50 border-gray-300" readonly/>
                                            <x-input type="text" value="{{ $child->name_ext }}" class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0 bg-gray-50 border-gray-300" readonly/>
                                        </div>
                                    </div>
                                    <div class="w-3/12 ps-3 text-xs">
                                        <x-input type="date" value="{{ $child->date_of_birth }}" class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0 bg-gray-50 border-gray-300" readonly/>
                                    </div>
                                    <div class="w-1/12 pe-3 text-xs text-end">
                                        <button wire:click.prevent="removeOldField({{ $child->id }})" wire:confirm="Are you sure you want to delete this data?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                {{-- {{ $old_children }} --}}
                            @else
                                <p class="mt-3 w-full py-2 font-medium text-xs text-center bg-gray-200">No Children</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @isset($personnel)
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Edit Family</h4>

                <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>

                        <p>Back</p>
                    </span>
                </button>
            </div>
            @livewire('form.update-family-form', ['id' => $personnel->id])
        @endisset
    @endif
</div>
