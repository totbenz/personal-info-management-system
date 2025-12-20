<div class="mx-auto py-8 px-10">
    {{-- Banner for success/error messages --}}
    @if (session()->has('flash.banner'))
    <div class="mb-4 px-4 py-2 rounded text-white {{ session('flash.bannerStyle') === 'success' ? 'bg-green-600' : 'bg-red-600' }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <div class="flex justify-between items-center">
            <span>{{ session('flash.banner') }}</span>
            <button @click="show = false" class="text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif
    @if (!$updateMode)
    <section>
        <div class="flex justify-between">
            <h4 class="font-bold text-2xl text-gray-darkest">Education Details</h4>
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
            <section>
                <h4 class="mt-5 mb-3 font-bold text-base text-gray-darkest">Elementary</h4>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-3/4">
                        <x-input id="elementary_school_name" type="text" label="School Name" name="elementary_school_name" wire:model="elementary_school_name" class="bg-gray-50 border-gray-300" readonly />
                        @error('elementary_school_name')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-1/4">
                        <x-input id="elementary_degree_course" type="text" label="Basic Education/Degree/ Course" name="elementary_degree_course" wire:model="elementary_degree_course" class="bg-gray-50 border-gray-300" readonly />
                        @error('elementary_degree_course')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                    <span class="w-2/12">
                        <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <x-input id="elementary_period_from" type="number" name="elementary_period_from" placeholder="From" wire:model="elementary_period_from" class="bg-gray-50 border-gray-300" readonly />
                                @error('elementary_period_from')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <x-input id="elementary_period_to" type="number" name="elementary_period_to" placeholder="To" wire:model="elementary_period_to" class="bg-gray-50 border-gray-300" readonly />
                                @error('elementary_period_to')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </span>
                    <span class="w-4/12">
                        <x-input id="elementary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="elementary_highest_level_units" wire:model="elementary_highest_level_units" class="bg-gray-50 border-gray-300" readonly />
                        @error('elementary_highest_level_units')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-2/12">
                        <x-input id="elementary_year_graduated" type="number" label="Year Graduated" name="elementary_year_graduated" wire:model="elementary_year_graduated" class="bg-gray-50 border-gray-300" readonly />
                        @error('elementary_year_graduated')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-4/12">
                        <x-input id="elementary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="elementary_scholarship_honors" wire:model="elementary_scholarship_honors" class="bg-gray-50 border-gray-300" readonly />
                        @error('elementary_scholarship_honors')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
            </section>

            <section>
                <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Secondary</h4>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-3/4">
                        <x-input id="secondary_school_name" type="text" label="School Name" name="secondary_school_name" wire:model="secondary_school_name" class="bg-gray-50 border-gray-300" readonly />
                        @error('secondary_school_name')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-1/4">
                        <x-input id="secondary_degree_course" type="text" label="Basic Education/Degree/ Course" name="secondary_degree_course" wire:model="secondary_degree_course" class="bg-gray-50 border-gray-300" readonly />
                        @error('secondary_degree_course')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                    <span class="w-2/12">
                        <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <x-input id="secondary_period_from" type="number" name="secondary_period_from" placeholder="From" wire:model="secondary_period_from" class="bg-gray-50 border-gray-300" readonly />
                                @error('secondary_period_from')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <x-input id="secondary_period_to" type="number" name="secondary_period_to" placeholder="To" wire:model="secondary_period_to" class="bg-gray-50 border-gray-300" readonly />
                                @error('secondary_period_to')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </span>
                    <span class="w-4/12">
                        <x-input id="secondary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="secondary_highest_level_units" wire:model="secondary_highest_level_units" class="bg-gray-50 border-gray-300" readonly />
                        @error('secondary_highest_level_units')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-2/12">
                        <x-input id="secondary_year_graduated" type="number" label="Year Graduated" name="secondary_year_graduated" wire:model="secondary_year_graduated" class="bg-gray-50 border-gray-300" readonly />
                        @error('secondary_year_graduated')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-4/12">
                        <x-input id="secondary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="secondary_scholarship_honors" wire:model="secondary_scholarship_honors" class="bg-gray-50 border-gray-300" readonly />
                        @error('secondary_scholarship_honors')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
            </section>

            <section>
                <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Vocational/Trade Course</h4>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-3/4">
                        <x-input id="vocational_school_name" type="text" label="School Name" name="vocational_school_name" wire:model="vocational_school_name" class="bg-gray-50 border-gray-300" readonly />
                        @error('vocational_school_name')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-1/4">
                        <x-input id="vocational_degree_course" type="text" label="Basic Education/Degree/ Course" name="vocational_degree_course" wire:model="vocational_degree_course" class="bg-gray-50 border-gray-300" readonly />
                        @error('vocational_degree_course')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                    <span class="w-2/12">
                        <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <x-input id="vocational_period_from" type="number" name="vocational_period_from" placeholder="From" wire:model="vocational_period_from" class="bg-gray-50 border-gray-300" readonly />
                                @error('vocational_period_from')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <x-input id="vocational_period_to" type="number" name="vocational_period_to" placeholder="To" wire:model="vocational_period_to" class="bg-gray-50 border-gray-300" readonly />
                                @error('vocational_period_to')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </span>
                    <span class="w-4/12">
                        <x-input id="vocational_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="vocational_highest_level_units" wire:model="vocational_highest_level_units" class="bg-gray-50 border-gray-300" readonly />
                        @error('vocational_highest_level_units')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-2/12">
                        <x-input id="vocational_year_graduated" type="number" label="Year Graduated" name="vocational_year_graduated" wire:model="vocational_year_graduated" class="bg-gray-50 border-gray-300" readonly />
                        @error('vocational_year_graduated')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-4/12">
                        <x-input id="vocational_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="vocational_scholarship_honors" wire:model="vocational_scholarship_honors" class="bg-gray-50 border-gray-300" readonly />
                        @error('vocational_scholarship_honors')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
            </section>

            <section>
                <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Graduate</h4>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-3/4">
                        <x-input id="graduate_school_name" type="text" label="School Name" name="graduate_school_name" wire:model="graduate_school_name" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_school_name')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-1/4">
                        <x-input id="graduate_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_degree_course" wire:model="graduate_degree_course" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_degree_course')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                    <span class="w-2/12">
                        <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <x-input id="graduate_period_from" type="number" name="graduate_period_from" placeholder="From" wire:model="graduate_period_from" class="bg-gray-50 border-gray-300" readonly />
                                @error('graduate_period_from')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <x-input id="graduate_period_to" type="number" name="graduate_period_to" placeholder="To" wire:model="graduate_period_to" class="bg-gray-50 border-gray-300" readonly />
                                @error('graduate_period_to')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </span>
                    <span class="w-4/12">
                        <x-input id="graduate_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_highest_level_units" wire:model="graduate_highest_level_units" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_highest_level_units')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-2/12">
                        <x-input id="graduate_year_graduated" type="number" label="Year Graduated" name="graduate_year_graduated" wire:model="graduate_year_graduated" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_year_graduated')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-4/12">
                        <x-input id="graduate_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_scholarship_honors" wire:model="graduate_scholarship_honors" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_scholarship_honors')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
            </section>

            <section>
                <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Graduate Studies</h4>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-3/4">
                        <x-input id="graduate_studies_school_name" type="text" label="School Name" name="graduate_studies_school_name" wire:model="graduate_studies_school_name" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_studies_school_name')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-1/4">
                        <x-input id="graduate_studies_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_studies_degree_course" wire:model="graduate_studies_degree_course" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_studies_degree_course')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                    <span class="w-2/12">
                        <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                        <div class="flex space-x-2">
                            <div class="w-1/2">
                                <x-input id="graduate_studies_period_from" type="number" name="graduate_studies_period_from" placeholder="From" wire:model="graduate_studies_period_from" class="bg-gray-50 border-gray-300" readonly />
                                @error('graduate_studies_period_from')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <x-input id="graduate_studies_period_to" type="number" name="graduate_studies_period_to" placeholder="To" wire:model="graduate_studies_period_to" class="bg-gray-50 border-gray-300" readonly />
                                @error('graduate_studies_period_to')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </span>
                    <span class="w-4/12">
                        <x-input id="graduate_studies_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_studies_highest_level_units" wire:model="graduate_studies_highest_level_units" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_studies_highest_level_units')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-2/12">
                        <x-input id="graduate_studies_year_graduated" type="number" label="Year Graduated" name="graduate_studies_year_graduated" wire:model="graduate_studies_year_graduated" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_studies_year_graduated')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                    <span class="w-4/12">
                        <x-input id="graduate_studies_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_studies_scholarship_honors" wire:model="graduate_studies_scholarship_honors" class="bg-gray-50 border-gray-300" readonly />
                        @error('graduate_studies_scholarship_honors')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </span>
                </div>
            </section>
        </div>
    </section>
    @else
    @isset($personnel)
        <div x-data="educationFormValidation()" x-init="init()">
            {{-- Banner for success/error messages in update mode --}}
            @if (session()->has('flash.banner'))
            <div class="mb-4 px-4 py-2 rounded text-white {{ session('flash.bannerStyle') === 'success' ? 'bg-green-600' : 'bg-red-600' }}"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="
                    // Only auto-hide success messages, keep error messages visible until manually closed
                    if ('{{ session('flash.bannerStyle') }}' === 'success') {
                        setTimeout(() => show = false, 5000);
                    }
                 ">
                <div class="flex justify-between items-center">
                    <span>{{ session('flash.banner') }}</span>
                    <button @click="show = false" class="text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Edit Education</h4>
                <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        <p>Back</p>
                    </span>
                </button>
            </div>

            {{-- Form Instructions --}}
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-blue-800 mb-1">Edit Education Information</h3>
                            <p class="text-sm text-blue-700 mb-2">
                                Fields marked with <span class="text-red-500 font-bold">*</span> are required.
                                Elementary and Secondary education information must be completed.
                                Vocational, Graduate, and Graduate Studies are optional.
                            </p>
                            @if($personnel && $personnel->educations()->count() > 0)
                            <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-green-800">
                                        <strong>Data loaded:</strong> {{ $personnel->educations()->count() }} education record(s) found and populated in the form.
                                    </span>
                                </div>
                            </div>
                            @endif
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

                <section>
                    <h4 class="mt-5 mb-3 font-bold text-base text-gray-darkest">Elementary <span class="text-red-500">*</span></h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/4">
                            <x-input id="elementary_school_name" type="text" label="School Name" name="elementary_school_name" wire:model.live="elementary_school_name" class="{{ $errors->has('elementary_school_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" />
                            <p class="text-xs text-gray-500 mt-1">Enter the complete name of your elementary school</p>
                            @error('elementary_school_name')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="elementary_school_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/4">
                            <x-input id="elementary_degree_course" type="text" label="Basic Education/Degree/ Course" name="elementary_degree_course" wire:model.live="elementary_degree_course" />
                            <p class="text-xs text-gray-500 mt-1">Elementary education level</p>
                            @error('elementary_degree_course')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="elementary_degree_course" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance <span class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="elementary_period_from" type="number" name="elementary_period_from" placeholder="e.g., 2010" wire:model.live="elementary_period_from" class="{{ $errors->has('elementary_period_from') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">Start year (1900-2100)</p>
                                    @error('elementary_period_from')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="elementary_period_from" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="elementary_period_to" type="number" name="elementary_period_to" placeholder="e.g., 2016" wire:model.live="elementary_period_to" class="{{ $errors->has('elementary_period_to') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">End year (must be ≥ start year)</p>
                                    @error('elementary_period_to')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="elementary_period_to" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="elementary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="elementary_highest_level_units" wire:model.live="elementary_highest_level_units" />
                            <p class="text-xs text-gray-500 mt-1">If not graduated, highest level achieved</p>
                            @error('elementary_highest_level_units')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="elementary_highest_level_units" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-2/12">
                            <x-input id="elementary_year_graduated" type="number" label="Year Graduated" name="elementary_year_graduated" wire:model.live="elementary_year_graduated" class="{{ $errors->has('elementary_year_graduated') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" min="1900" max="2100" step="1" placeholder="e.g., 2016" />
                            <p class="text-xs text-gray-500 mt-1">Graduation year (must be ≤ end year)</p>
                            @error('elementary_year_graduated')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="elementary_year_graduated" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="elementary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="elementary_scholarship_honors" wire:model.live="elementary_scholarship_honors" placeholder="e.g., Honor Student, Academic Excellence" />
                            <p class="text-xs text-gray-500 mt-1">List any scholarships or honors received</p>
                            @error('elementary_scholarship_honors')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="elementary_scholarship_honors" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Secondary <span class="text-red-500">*</span></h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/4">
                            <x-input id="secondary_school_name" type="text" label="School Name" name="secondary_school_name" wire:model.live="secondary_school_name" class="{{ $errors->has('secondary_school_name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" />
                            <p class="text-xs text-gray-500 mt-1">Enter the complete name of your secondary school</p>
                            @error('secondary_school_name')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="secondary_school_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/4">
                            <x-input id="secondary_degree_course" type="text" label="Basic Education/Degree/ Course" name="secondary_degree_course" wire:model.live="secondary_degree_course" />
                            <p class="text-xs text-gray-500 mt-1">High school education level</p>
                            @error('secondary_degree_course')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="secondary_degree_course" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance <span class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="secondary_period_from" type="number" name="secondary_period_from" placeholder="e.g., 2014" wire:model.live="secondary_period_from" class="{{ $errors->has('secondary_period_from') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">Start year (1900-2100)</p>
                                    @error('secondary_period_from')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="secondary_period_from" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="secondary_period_to" type="number" name="secondary_period_to" placeholder="e.g., 2020" wire:model.live="secondary_period_to" class="{{ $errors->has('secondary_period_to') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">End year (must be ≥ start year)</p>
                                    @error('secondary_period_to')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="secondary_period_to" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="secondary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="secondary_highest_level_units" wire:model.live="secondary_highest_level_units" />
                            <p class="text-xs text-gray-500 mt-1">If not graduated, highest level achieved</p>
                            @error('secondary_highest_level_units')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="secondary_highest_level_units" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-2/12">
                            <x-input id="secondary_year_graduated" type="number" label="Year Graduated" name="secondary_year_graduated" wire:model.live="secondary_year_graduated" class="{{ $errors->has('secondary_year_graduated') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" min="1900" max="2100" step="1" placeholder="e.g., 2020" />
                            <p class="text-xs text-gray-500 mt-1">Graduation year (must be ≤ end year)</p>
                            @error('secondary_year_graduated')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="secondary_year_graduated" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="secondary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="secondary_scholarship_honors" wire:model.live="secondary_scholarship_honors" placeholder="e.g., With Honors, Valedictorian" />
                            <p class="text-xs text-gray-500 mt-1">List any scholarships or honors received</p>
                            @error('secondary_scholarship_honors')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="secondary_scholarship_honors" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Vocational/Trade Course</h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/4">
                            <x-input id="vocational_school_name" type="text" label="School Name" name="vocational_school_name" wire:model.live="vocational_school_name" />
                            <p class="text-xs text-gray-500 mt-1">Enter vocational/trade school name (optional)</p>
                            @error('vocational_school_name')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="vocational_school_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/4">
                            <x-input id="vocational_degree_course" type="text" label="Basic Education/Degree/ Course" name="vocational_degree_course" wire:model.live="vocational_degree_course" />
                            <p class="text-xs text-gray-500 mt-1">Vocational course or trade</p>
                            @error('vocational_degree_course')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="vocational_degree_course" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="vocational_period_from" type="number" name="vocational_period_from" placeholder="e.g., 2018" wire:model.live="vocational_period_from" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">Start year (1900-2100)</p>
                                    @error('vocational_period_from')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="vocational_period_from" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="vocational_period_to" type="number" name="vocational_period_to" placeholder="e.g., 2020" wire:model.live="vocational_period_to" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">End year (must be ≥ start year)</p>
                                    @error('vocational_period_to')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="vocational_period_to" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="vocational_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="vocational_highest_level_units" wire:model.live="vocational_highest_level_units" />
                            <p class="text-xs text-gray-500 mt-1">If not graduated, highest level achieved</p>
                            @error('vocational_highest_level_units')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="vocational_highest_level_units" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-2/12">
                            <x-input id="vocational_year_graduated" type="number" label="Year Graduated" name="vocational_year_graduated" wire:model.live="vocational_year_graduated" min="1900" max="2100" step="1" placeholder="e.g., 2020" />
                            <p class="text-xs text-gray-500 mt-1">Graduation year (must be ≤ end year)</p>
                            @error('vocational_year_graduated')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="vocational_year_graduated" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="vocational_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="vocational_scholarship_honors" wire:model.live="vocational_scholarship_honors" />
                            <p class="text-xs text-gray-500 mt-1">Any scholarships or academic honors received</p>
                            @error('vocational_scholarship_honors')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="vocational_scholarship_honors" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Graduate</h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/12">
                            <x-input id="graduate_school_name" type="text" label="School Name" name="graduate_school_name" wire:model.live="graduate_school_name"/>
                            <p class="text-xs text-gray-500 mt-1">College/university name (optional)</p>
                            @error('graduate_school_name')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_school_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-3/12">
                            <x-input id="graduate_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_degree_course" wire:model.live="graduate_degree_course"/>
                            <p class="text-xs text-gray-500 mt-1">Bachelor's degree or course</p>
                            @error('graduate_degree_course')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_degree_course" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-3/12">
                            <x-input id="graduate_major" type="text" label="Major" name="graduate_major" wire:model.live="graduate_major"/>
                            <p class="text-xs text-gray-500 mt-1">Field of specialization (optional)</p>
                            @error('graduate_major')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_major" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-3/12">
                            <x-input id="graduate_minor" type="text" label="Minor" name="graduate_minor" wire:model.live="graduate_minor"/>
                            <p class="text-xs text-gray-500 mt-1">Secondary field of study (optional)</p>
                            @error('graduate_minor')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_minor" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="graduate_period_from" type="number" name="graduate_period_from" placeholder="e.g., 2016" wire:model.live="graduate_period_from" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">Start year (1900-2100)</p>
                                    @error('graduate_period_from')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="graduate_period_from" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="graduate_period_to" type="number" name="graduate_period_to" placeholder="e.g., 2022" wire:model.live="graduate_period_to" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">End year (must be ≥ start year)</p>
                                    @error('graduate_period_to')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="graduate_period_to" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_highest_level_units" wire:model.live="graduate_highest_level_units" />
                            <p class="text-xs text-gray-500 mt-1">If not graduated, highest level achieved</p>
                            @error('graduate_highest_level_units')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_highest_level_units" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-2/12">
                            <x-input id="graduate_year_graduated" type="number" label="Year Graduated" name="graduate_year_graduated" wire:model.live="graduate_year_graduated" min="1900" max="2100" step="1" placeholder="e.g., 2022" />
                            <p class="text-xs text-gray-500 mt-1">Graduation year (must be ≤ end year)</p>
                            @error('graduate_year_graduated')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_year_graduated" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_scholarship_honors" wire:model.live="graduate_scholarship_honors" placeholder="e.g., Cum Laude, Dean's List" />
                            <p class="text-xs text-gray-500 mt-1">List any scholarships or honors received</p>
                            @error('graduate_scholarship_honors')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_scholarship_honors" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Graduate Studies</h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/12">
                            <x-input id="graduate_studies_school_name" type="text" label="School Name" name="graduate_studies_school_name" wire:model.live="graduate_studies_school_name"/>
                            <p class="text-xs text-gray-500 mt-1">University name (optional)</p>
                            @error('graduate_studies_school_name')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_studies_school_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-3/12">
                            <x-input id="graduate_studies_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_studies_degree_course" wire:model.live="graduate_studies_degree_course"/>
                            <p class="text-xs text-gray-500 mt-1">Master's/Doctoral degree</p>
                            @error('graduate_studies_degree_course')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_studies_degree_course" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-3/12">
                            <x-input id="graduate_studies_major" type="text" label="Major" name="graduate_studies_major" wire:model.live="graduate_studies_major"/>
                            <p class="text-xs text-gray-500 mt-1">Field of specialization (optional)</p>
                            @error('graduate_studies_major')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_studies_major" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-3/12">
                            <x-input id="graduate_studies_minor" type="text" label="Minor" name="graduate_studies_minor" wire:model.live="graduate_studies_minor"/>
                            <p class="text-xs text-gray-500 mt-1">Secondary field of study (optional)</p>
                            @error('graduate_studies_minor')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_studies_minor" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="graduate_studies_period_from" type="number" name="graduate_studies_period_from" placeholder="e.g., 2020" wire:model.live="graduate_studies_period_from" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">Start year (1900-2100)</p>
                                    @error('graduate_studies_period_from')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="graduate_studies_period_from" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="graduate_studies_period_to" type="number" name="graduate_studies_period_to" placeholder="e.g., 2024" wire:model.live="graduate_studies_period_to" min="1900" max="2100" step="1" />
                                    <p class="text-xs text-gray-500 mt-1">End year (must be ≥ start year)</p>
                                    @error('graduate_studies_period_to')
                                    <span class="text-red-600 text-xs flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    <div wire:loading wire:target="graduate_studies_period_to" class="text-xs text-blue-600 mt-1">Validating...</div>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_studies_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_studies_highest_level_units" wire:model.live="graduate_studies_highest_level_units" />
                            <p class="text-xs text-gray-500 mt-1">If not graduated, highest level achieved</p>
                            @error('graduate_studies_highest_level_units')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_studies_highest_level_units" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-2/12">
                            <x-input id="graduate_studies_year_graduated" type="number" label="Year Graduated" name="graduate_studies_year_graduated" wire:model.live="graduate_studies_year_graduated" min="1900" max="2100" step="1" placeholder="e.g., 2024" />
                            <p class="text-xs text-gray-500 mt-1">Graduation year (must be ≤ end year)</p>
                            @error('graduate_studies_year_graduated')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_studies_year_graduated" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_studies_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_studies_scholarship_honors" wire:model.live="graduate_studies_scholarship_honors" placeholder="e.g., PhD Scholar, Research Grant" />
                            <p class="text-xs text-gray-500 mt-1">List any scholarships or honors received</p>
                            @error('graduate_studies_scholarship_honors')
                            <span class="text-red-600 text-xs flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                            @enderror
                            <div wire:loading wire:target="graduate_studies_scholarship_honors" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                </section>

                {{-- Validation Summary --}}
                @if($errors->any())
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800 mb-2">Please correct the following errors:</h3>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                <li class="flex items-center">
                                    <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $error }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <div class="my-5 p-0 flex space-x-3 justify-end">
                    <div class="w-2/12">
                        <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
                    </div>
                    <div class="w-2/12" wire:key="save-button">
                        @if($this->isFormValid())
                            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
                        @else
                            <button disabled class="px-5 py-2.5 w-full bg-gray-400 font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-60">
                                Save ({{ $this->getMissingFieldsCount() }} fields missing)
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endisset
    @endif

<script>
function educationFormValidation() {
    return {
        init() {
            // Listen for Livewire dispatch events
            window.addEventListener('show-error-alert', e => {
                let msg = (e.detail && typeof e.detail.message !== 'undefined') ? e.detail.message : 'An error occurred.';
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: msg,
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert(msg);
                }
            });

            // Listen for field validation events
            window.addEventListener('field-validated', e => {
                const field = e.detail.field;
                const input = document.querySelector(`[name='${field}']`);
                if (input) {
                    input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.add('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');

                    // Add success icon
                    let successIcon = input.parentNode.querySelector('.success-icon');
                    if (!successIcon) {
                        successIcon = document.createElement('div');
                        successIcon.className = 'success-icon absolute right-3 top-1/2 transform -translate-y-1/2';
                        successIcon.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                        input.parentNode.style.position = 'relative';
                        input.parentNode.appendChild(successIcon);
                    }
                }
            });

            window.addEventListener('field-invalid', e => {
                const field = e.detail.field;
                const input = document.querySelector(`[name='${field}']`);
                if (input) {
                    input.classList.remove('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');
                    input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');

                    // Remove success icon
                    const successIcon = input.parentNode.querySelector('.success-icon');
                    if (successIcon) {
                        successIcon.remove();
                    }
                }
            });
        }
    }
}
</script>
