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

            <section x-data="familyFormValidation()" x-init="init()">
                {{-- Form Instructions --}}
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-blue-800 mb-1">Edit Family Information</h3>
                            <p class="text-sm text-blue-700 mb-2">
                                Fields marked with <span class="text-red-500 font-bold">*</span> are required.
                                Father and Mother information must be completed. Spouse information is optional.
                                Children information is required if you have children.
                            </p>
                            <div wire:key="form-status" class="mt-2">
                                @if(!$this->isFormValid())
                                <div class="p-2 bg-yellow-50 border border-yellow-200 rounded">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-yellow-800">
                                            <strong>{{ $this->getMissingFieldsCount() }} required fields</strong> still need to be completed before saving.
                                        </span>
                                    </div>
                                </div>
                                @else
                                <div class="p-2 bg-green-50 border border-green-200 rounded">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-green-800">
                                            <strong>All required fields completed!</strong> You can now save the form.
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mt-5">
                        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                            <span class="w-1/4">
                                <label for="fathers_first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Father's First Name <span class="text-red-500">*</span>
                                </label>
                                <input id="fathers_first_name" wire:model.live="fathers_first_name" type="text" name="fathers_first_name" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {{ $errors->has('fathers_first_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" required/>
                                <p class="text-xs text-gray-500 mt-1">Enter your father's legal first name</p>
                                @error('fathers_first_name')
                                <span class="text-red-600 text-xs flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading wire:target="fathers_first_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/4">
                                <label for="fathers_middle_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Father's Middle Name <span class="text-red-500">*</span>
                                </label>
                                <input id="fathers_middle_name" wire:model.live="fathers_middle_name" type="text" name="fathers_middle_name" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {{ $errors->has('fathers_middle_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" required/>
                                <p class="text-xs text-gray-500 mt-1">Enter your father's middle name</p>
                                @error('fathers_middle_name')
                                <span class="text-red-600 text-xs flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading wire:target="fathers_middle_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/4">
                                <label for="fathers_last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Father's Last Name <span class="text-red-500">*</span>
                                </label>
                                <input id="fathers_last_name" wire:model.live="fathers_last_name" type="text" name="fathers_last_name" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {{ $errors->has('fathers_last_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" required/>
                                <p class="text-xs text-gray-500 mt-1">Enter your father's family name/last name</p>
                                @error('fathers_last_name')
                                <span class="text-red-600 text-xs flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading wire:target="fathers_last_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/4">
                                <label for="fathers_name_ext" class="block text-sm font-medium text-gray-700 mb-1">
                                    Father's Name Extension
                                </label>
                                <input id="fathers_name_ext" wire:model.live="fathers_name_ext" type="text" name="fathers_name_ext" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {{ $errors->has('fathers_name_ext') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"/>
                                <p class="text-xs text-gray-500 mt-1">e.g., Jr., Sr., III (if applicable)</p>
                                @error('fathers_name_ext')
                                <span class="text-red-600 text-xs flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading wire:target="fathers_name_ext" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                        </div>
                        <div class="m-0 mb-4 p-0 flex space-x-3">
                            <span class="w-1/4">
                                <label for="mothers_first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mother's First Name <span class="text-red-500">*</span>
                                </label>
                                <input id="mothers_first_name" wire:model.live="mothers_first_name" type="text" name="mothers_first_name" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {{ $errors->has('mothers_first_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" required/>
                                <p class="text-xs text-gray-500 mt-1">Enter your mother's legal first name</p>
                                @error('mothers_first_name')
                                <span class="text-red-600 text-xs flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading wire:target="mothers_first_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/4">
                                <label for="mothers_middle_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mother's Middle Name <span class="text-red-500">*</span>
                                </label>
                                <input id="mothers_middle_name" wire:model.live="mothers_middle_name" type="text" name="mothers_middle_name" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {{ $errors->has('mothers_middle_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" required/>
                                <p class="text-xs text-gray-500 mt-1">Enter your mother's middle name</p>
                                @error('mothers_middle_name')
                                <span class="text-red-600 text-xs flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading wire:target="mothers_middle_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/4">
                                <label for="mothers_last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mother's Maiden Name <span class="text-red-500">*</span>
                                </label>
                                <input id="mothers_last_name" wire:model.live="mothers_last_name" type="text" name="mothers_last_name" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {{ $errors->has('mothers_last_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" required/>
                                <p class="text-xs text-gray-500 mt-1">Enter your mother's maiden name</p>
                                @error('mothers_last_name')
                                <span class="text-red-600 text-xs flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading wire:target="mothers_last_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h5 class="font-bold text-xl text-gray-darkest">Spouse <span class="text-sm text-gray-600 font-normal">(Optional - leave blank if not applicable)</span></h5>
                        <section>
                            <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                                <span class="w-1/4">
                                    <x-input id="spouse_first_name" wire:model.live="spouse_first_name" label="First Name" type="text" name="spouse_first_name" />
                                    <p class="text-xs text-gray-500 mt-1">First name of spouse (if applicable)</p>
                                    @error('spouse_first_name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_first_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                                <span class="w-1/4">
                                    <x-input id="spouse_middle_name" wire:model.live="spouse_middle_name" label="Middle Name" type="text" name="spouse_middle_name" />
                                    <p class="text-xs text-gray-500 mt-1">Middle name or initial</p>
                                    @error('spouse_middle_name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_middle_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                                <span class="w-1/4">
                                    <x-input id="spouse_last_name" wire:model.live="spouse_last_name" label="Last Name" type="text" name="spouse_last_name" />
                                    <p class="text-xs text-gray-500 mt-1">Family name/last name</p>
                                    @error('spouse_last_name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_last_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                                <span class="w-1/4">
                                    <x-input id="spouse_name_ext" wire:model.live="spouse_name_ext" label="Name Extension" type="text" name="spouse_name_ext" />
                                    <p class="text-xs text-gray-500 mt-1">e.g., Jr., Sr., III (if applicable)</p>
                                    @error('spouse_name_ext')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_name_ext" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                            </div>
                            <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                                <span class="w-1/2">
                                    <x-input id="spouse_occupation" wire:model.live="spouse_occupation" label="Occupation" type="text" name="spouse_occupation" />
                                    <p class="text-xs text-gray-500 mt-1">Current job or profession</p>
                                    @error('spouse_occupation')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_occupation" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                                <span class="w-1/2">
                                    <x-input id="spouse_business_name" wire:model.live="spouse_business_name" label="Employer/Business Name" type="text" name="spouse_business_name" />
                                    <p class="text-xs text-gray-500 mt-1">Company name or business name</p>
                                    @error('spouse_business_name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_business_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                            </div>
                            <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                                <span class="w-1/2">
                                    <x-input id="spouse_business_address" wire:model.live="spouse_business_address" label="Business Address" type="text" name="spouse_business_address" />
                                    <p class="text-xs text-gray-500 mt-1">Work or business address</p>
                                    @error('spouse_business_address')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_business_address" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                                <span class="w-1/2">
                                    <x-input id="spouse_tel_no" wire:model.live="spouse_tel_no" label="Telephone No." type="text" name="spouse_tel_no" />
                                    <p class="text-xs text-gray-500 mt-1">Contact telephone number</p>
                                    @error('spouse_tel_no')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                    <div wire:loading wire:target="spouse_tel_no" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </span>
                            </div>
                        </section>
                    </div>

                    <div class="mt-10">
                        <h5 class="font-bold text-xl text-gray-darkest">Children</h5>
                        <section>
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
                                    <!-- Old Data Children -->
                                    @foreach ($old_children as $index => $child)
                                        <div class="mb-2 px-3 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
                                            <div class="w-8/12 ps-3 text-xs">
                                                <div class="sm:flex space-x-1 rounded-md border border-gray-300 {{ $errors->has('old_children.'.$index.'.first_name') || $errors->has('old_children.'.$index.'.middle_name') || $errors->has('old_children.'.$index.'.last_name') ? 'border-red-500' : '' }}">
                                                    <input id="first_name_{{ $index }}" type="text" wire:model.live="old_children.{{ $index }}.first_name" name="old_children[{{ $index }}][first_name]" placeholder="First Name" class="w-[14rem] rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                                    <input id="middle_name_{{ $index }}" type="text" wire:model.live="old_children.{{ $index }}.middle_name" name="old_children[{{ $index }}][middle_name]" placeholder="M.I." class="w-[4rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                                    <input id="last_name_{{ $index }}" type="text" wire:model.live="old_children.{{ $index }}.last_name" name="old_children[{{ $index }}][last_name]" placeholder="Last Name" class="w-[14rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                                    <input id="name_ext_{{ $index }}" type="text" wire:model="old_children.{{ $index }}.name_ext" name="old_children[{{ $index }}][name_ext]" placeholder="Ext." class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0"/>
                                                </div>
                                                @error('old_children.'.$index.'.first_name')
                                                <span class="text-red-600 text-xs flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="w-3/12 ps-3 text-xs">
                                                <x-input type="date" wire:model.live="old_children.{{ $index }}.date_of_birth" name="old_children[{{ $index }}][date_of_birth]" class="text-xs {{ $errors->has('old_children.'.$index.'.date_of_birth') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" placeholder="0" required/>
                                                @error('old_children.'.$index.'.date_of_birth')
                                                <span class="text-red-600 text-xs flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="w-1/12 pe-3 text-xs text-center">
                                                <button wire:click="confirmRemoveOldField({{ $index }})" wire:confirm="Are you sure you want to delete this child?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- New Data Children -->
                                    @foreach ($new_children as $index => $new_child)
                                        <div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none"
                                            x-cloak
                                            x-transition:enter="transition ease-in-out duration-200"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in-out duration-200"
                                            x-transition:leave-start="opacity-100 transform scale-100"
                                            x-transition:leave-end="opacity-0 transform scale-95">
                                            <div class="w-8/12 ps-3 text-xs">
                                                <div class="sm:flex space-x-1 rounded-md border border-gray-300 {{ $errors->has('new_children.'.$index.'.first_name') || $errors->has('new_children.'.$index.'.middle_name') || $errors->has('new_children.'.$index.'.last_name') ? 'border-red-500' : '' }}">
                                                    <input id="new_first_name_{{ $index }}" type="text" wire:model.live="new_children.{{ $index }}.first_name" name="new_children[{{ $index }}][first_name]" placeholder="First Name" class="w-[14rem] rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                                    <input id="new_middle_name_{{ $index }}" type="text" wire:model.live="new_children.{{ $index }}.middle_name" name="new_children[{{ $index }}][middle_name]" placeholder="M.I." class="w-[4rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                                    <input id="new_last_name_{{ $index }}" type="text" wire:model.live="new_children.{{ $index }}.last_name" name="new_children[{{ $index }}][last_name]" placeholder="Last Name" class="w-[14rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                                    <input id="new_name_ext_{{ $index }}" type="text" wire:model="new_children.{{ $index }}.name_ext" name="new_children[{{ $index }}][name_ext]" placeholder="Ext." class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0"/>
                                                </div>
                                                @error('new_children.'.$index.'.first_name')
                                                <span class="text-red-600 text-xs flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="w-3/12 ps-3 text-xs">
                                                <x-input wire:model.live="new_children.{{ $index }}.date_of_birth" type="date" name="new_children[{{ $index }}][date_of_birth]" class="text-xs {{ $errors->has('new_children.'.$index.'.date_of_birth') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" placeholder="0" required/>
                                                @error('new_children.'.$index.'.date_of_birth')
                                                <span class="text-red-600 text-xs flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="w-1/12 pe-3 text-xs text-center">
                                                <button wire:click.prevent="removeNewField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
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
                        </section>
                    </div>
                </div>

                <!-- Validation Summary -->
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                        </div>
                        <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="my-5 p-0 flex space-x-3 justify-end">
                    <div class="w-2/12">
                        <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
                    </div>
                    <div class="w-2/12" wire:key="save-button">
                        @if($this->isFormValid())
                            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                        @else
                            <x-button disabled label="Save ({{ $this->getMissingFieldsCount() }} missing)" class="px-5 py-2.5 w-full bg-gray-400 font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed"/>
                        @endif
                    </div>
                </div>
            </section>
        @endisset
    @endif
</div>

<script>
function familyFormValidation() {
    return {
        init() {
            // Listen for Livewire events
            this.$wire.on('show-error-alert', (data) => {
                this.showErrorAlert(data.message);
            });

            this.$wire.on('field-validated', (data) => {
                this.showFieldValidation(data.field, data.isValid, data.message);
            });

            this.$wire.on('field-invalid', (data) => {
                this.showFieldValidation(data.field, false, data.message);
            });
        },

        showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc2626'
            });
        },

        showFieldValidation(field, isValid, message) {
            if (!isValid && message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Field Required',
                    text: message,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        }
    }
}
</script>
