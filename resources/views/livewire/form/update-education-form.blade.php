<div>
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

    <section>
        <h4 class="mt-5 mb-3 font-bold text-base text-gray-darkest">Elementary</h4>
        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
            <span class="w-3/4">
                <x-input id="elementary_school_name" type="text" label="School Name" name="elementary_school_name"  wire:model="elementary_school_name" />
                @error('elementary_school_name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-1/4">
                <x-input id="elementary_degree_course" type="text" label="Basic Education/Degree/ Course" name="elementary_degree_course" wire:model="elementary_degree_course" />
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
                        <x-input id="elementary_period_from" type="number" name="elementary_period_from" placeholder="From" wire:model="elementary_period_from" />
                        @error('elementary_period_from')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <x-input id="elementary_period_to" type="number" name="elementary_period_to" placeholder="To" wire:model="elementary_period_to" />
                        @error('elementary_period_to')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </span>
            <span class="w-4/12">
                <x-input id="elementary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="elementary_highest_level_units" wire:model="elementary_highest_level_units" />
                @error('elementary_highest_level_units')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-2/12">
                <x-input id="elementary_year_graduated" type="number" label="Year Graduated" name="elementary_year_graduated" wire:model="elementary_year_graduated" />
                @error('elementary_year_graduated')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-4/12">
                <x-input id="elementary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="elementary_scholarship_honors" wire:model="elementary_scholarship_honors" />
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
                <x-input id="secondary_school_name" type="text" label="School Name" name="secondary_school_name" wire:model="secondary_school_name" />
                @error('secondary_school_name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-1/4">
                <x-input id="secondary_degree_course" type="text" label="Basic Education/Degree/ Course" name="secondary_degree_course" wire:model="secondary_degree_course" />
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
                        <x-input id="secondary_period_from" type="number" name="secondary_period_from" placeholder="From" wire:model="secondary_period_from" required/>
                        @error('secondary_period_from')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <x-input id="secondary_period_to" type="number" name="secondary_period_to" placeholder="To" wire:model="secondary_period_to" />
                        @error('secondary_period_to')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </span>
            <span class="w-4/12">
                <x-input id="secondary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="secondary_highest_level_units" wire:model="secondary_highest_level_units" />
                @error('secondary_highest_level_units')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-2/12">
                <x-input id="secondary_year_graduated" type="number" label="Year Graduated" name="secondary_year_graduated" wire:model="secondary_year_graduated" />
                @error('secondary_year_graduated')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-4/12">
                <x-input id="secondary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="secondary_scholarship_honors" wire:model="secondary_scholarship_honors" />
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
                <x-input id="vocational_school_name" type="text" label="School Name" name="vocational_school_name" wire:model="vocational_school_name" />
                @error('vocational_school_name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-1/4">
                <x-input id="vocational_degree_course" type="text" label="Basic Education/Degree/ Course" name="vocational_degree_course" wire:model="vocational_degree_course" />
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
                        <x-input id="vocational_period_from" type="number" name="vocational_period_from" placeholder="From" wire:model="vocational_period_from" />
                        @error('vocational_period_from')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <x-input id="vocational_period_to" type="number" name="vocational_period_to" placeholder="To" wire:model="vocational_period_to" />
                        @error('vocational_period_to')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </span>
            <span class="w-4/12">
                <x-input id="vocational_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="vocational_highest_level_units" wire:model="vocational_highest_level_units" />
                @error('vocational_highest_level_units')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-2/12">
                <x-input id="vocational_year_graduated" type="number" label="Year Graduated" name="vocational_year_graduated" wire:model="vocational_year_graduated" />
                @error('vocational_year_graduated')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-4/12">
                <x-input id="vocational_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="vocational_scholarship_honors" wire:model="vocational_scholarship_honors" />
                @error('vocational_scholarship_honors')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
        </div>
    </section>

    <section>
        <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Graduate</h4>
        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
            <span class="w-3/12">
                <x-input id="graduate_school_name" type="text" label="School Name" name="graduate_school_name" wire:model="graduate_school_name"/>
                @error('graduate_school_name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-3/12">
                <x-input id="graduate_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_degree_course" wire:model="graduate_degree_course"/>
                @error('graduate_degree_course')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-3/12">
                <x-input id="graduate_major" type="text" label="Major" name="graduate_major" wire:model="graduate_major"/>
                @error('graduate_major')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-3/12">
                <x-input id="graduate_minor" type="text" label="Minor" name="graduate_minor" wire:model="graduate_minor"/>
                @error('graduate_minor')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
        </div>
        <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
            <span class="w-2/12">
                <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                <div class="flex space-x-2">
                    <div class="w-1/2">
                        <x-input id="graduate_period_from" type="number" name="graduate_period_from" placeholder="From" wire:model="graduate_period_from"/>
                        @error('graduate_period_from')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <x-input id="graduate_period_to" type="number" name="graduate_period_to" placeholder="To" wire:model="graduate_period_to"/>
                        @error('graduate_period_to')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </span>
            <span class="w-4/12">
                <x-input id="graduate_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_highest_level_units" wire:model="graduate_highest_level_units" />
                @error('graduate_highest_level_units')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-2/12">
                <x-input id="graduate_year_graduated" type="number" label="Year Graduated" name="graduate_year_graduated" wire:model="graduate_year_graduated" />
                @error('graduate_year_graduated')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-4/12">
                <x-input id="graduate_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_scholarship_honors" wire:model="graduate_scholarship_honors"/>
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
                <x-input id="graduate_studies_school_name" type="text" label="School Name" name="graduate_studies_school_name" wire:model="graduate_studies_school_name"/>
                @error('graduate_studies_school_name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-1/4">
                <x-input id="graduate_studies_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_studies_degree_course" wire:model="graduate_studies_degree_course"/>
                @error('graduate_studies_degree_course')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
        </div>
        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
            <span class="w-3/12">
                <x-input id="graduate_studies_major" type="text" label="Major" name="graduate_studies_major" wire:model="graduate_studies_major"/>
                @error('graduate_studies_major')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-3/12">
                <x-input id="graduate_studies_minor" type="text" label="Minor" name="graduate_studies_minor" wire:model="graduate_studies_minor"/>
                @error('graduate_studies_minor')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-6/12">
                <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                <div class="flex space-x-2">
                    <div class="w-1/2">
                        <x-input id="graduate_studies_period_from" type="number" name="graduate_studies_period_from" placeholder="From" wire:model="graduate_studies_period_from"/>
                        @error('graduate_studies_period_from')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <x-input id="graduate_studies_period_to" type="number" name="graduate_studies_period_to" placeholder="To" wire:model="graduate_studies_period_to"/>
                        @error('graduate_studies_period_to')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </span>
        </div>
        <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
            <span class="w-4/12">
                <x-input id="graduate_studies_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_studies_highest_level_units" wire:model="graduate_studies_highest_level_units"/>
                @error('graduate_studies_highest_level_units')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-2/12">
                <x-input id="graduate_studies_year_graduated" type="number" label="Year Graduated" name="graduate_studies_year_graduated" wire:model="graduate_studies_year_graduated"/>
                @error('graduate_studies_year_graduated')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
            <span class="w-4/12">
                <x-input id="graduate_studies_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_studies_scholarship_honors" wire:model="graduate_studies_scholarship_honors"/>
                @error('graduate_studies_scholarship_honors')
                <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </span>
        </div>
    </section>

    <div class="my-5 p-0 flex space-x-3 justify-end">
        <div class="w-2/12">
            <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
        </div>
        <div class="w-2/12">
            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
        </div>
    </div>
</div>
