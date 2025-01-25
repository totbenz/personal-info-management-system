<div class="mx-auto py-8 px-10" >
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
                            <x-input id="elementary_school_name" type="text" label="School Name" name="elementary_school_name"  wire:model="elementary_school_name"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input id="elementary_degree_course" type="text" label="Basic Education/Degree/ Course" name="elementary_degree_course" wire:model="elementary_degree_course"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="elementary_period_from" type="number" name="elementary_period_from" placeholder="From" wire:model="elementary_period_from"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="elementary_period_to" type="number" name="elementary_period_to" placeholder="To" wire:model="elementary_period_to"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="elementary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="elementary_highest_level_units" wire:model="elementary_highest_level_units"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input id="elementary_year_graduated" type="number" label="Year Graduated" name="elementary_year_graduated" wire:model="elementary_year_graduated" class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-4/12">
                            <x-input id="elementary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="elementary_scholarship_honors" wire:model="elementary_scholarship_honors"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Secondary</h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/4">
                            <x-input id="secondary_school_name" type="text" label="School Name" name="secondary_school_name" wire:model="secondary_school_name"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input id="secondary_degree_course" type="text" label="Basic Education/Degree/ Course" name="secondary_degree_course" wire:model="secondary_degree_course"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="secondary_period_from" type="number" name="secondary_period_from" placeholder="From" wire:model="secondary_period_from"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="secondary_period_to" type="text" name="secondary_period_to" placeholder="To" wire:model="secondary_period_to"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="secondary_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="secondary_highest_level_units" wire:model="secondary_highest_level_units"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input id="secondary_year_graduated" type="number" label="Year Graduated" name="secondary_year_graduated" wire:model="secondary_year_graduated"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-4/12">
                            <x-input id="secondary_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="secondary_scholarship_honors" wire:model="secondary_scholarship_honors"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Vocational/Trade Course</h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/4">
                            <x-input id="vocational_school_name" type="text" label="School Name" name="vocational_school_name" wire:model="vocational_school_name"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input id="vocational_degree_course" type="text" label="Basic Education/Degree/ Course" name="vocational_degree_course" wire:model="vocational_degree_course"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="vocational_period_from" type="number" name="vocational_period_from" placeholder="From" wire:model="vocational_period_from"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="vocational_period_too" type="number" name="vocational_period_to" placeholder="To" wire:model="vocational_period_to"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="vocational_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="vocational_highest_level_units" wire:model="vocational_highest_level_units"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input id="vocational_year_graduated" type="number" label="Year Graduated" name="vocational_year_graduated" wire:model="vocational_year_graduated"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-4/12">
                            <x-input id="vocational_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="vocational_scholarship_honors" wire:model="vocational_scholarship_honors"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Graduate</h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/4">
                            <x-input id="graduate_school_name" type="text" label="School Name" name="graduate_school_name" wire:model="graduate_school_name"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input id="graduate_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_degree_course" wire:model="graduate_degree_course"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="graduate_period_from" type="number" name="graduate_period_from" placeholder="From" wire:model="graduate_period_from"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="graduate_period_to" type="number" name="graduate_period_to" placeholder="To" wire:model="graduate_period_to"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_highest_level_units" wire:model="graduate_highest_level_units"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input id="graduate_year_graduated" type="number" label="Year Graduated" name="graduate_year_graduated" wire:model="graduate_year_graduated"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_scholarship_honors" wire:model="graduate_scholarship_honors"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                </section>

                <section>
                    <h4 class="mt-8 mb-3 font-bold text-base text-gray-darkest">Graduate Studies</h4>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-3/4">
                            <x-input id="graduate_studies_school_name" type="text" label="School Name" name="graduate_studies_school_name" wire:model="graduate_studies_school_name"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input id="graduate_studies_degree_course" type="text" label="Basic Education/Degree/ Course" name="graduate_studies_degree_course" wire:model="graduate_studies_degree_course"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex items-center space-x-3 justify-between">
                        <span class="w-2/12">
                            <label for="email" class="block font-medium text-sm text-center text-gray-700">Period Of Attendance</label>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <x-input id="graduate_studies_period_from" type="number" name="graduate_studies_period_from" placeholder="From" wire:model="graduate_studies_period_from"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                                <div class="w-1/2">
                                    <x-input id="graduate_studies_period_to" type="number" name="graduate_studies_period_to" placeholder="To" wire:model="graduate_studies_period_to"  class="bg-gray-50 border-gray-300" readonly/>
                                </div>
                            </div>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_studies_highest_level_units" type="text" label="Highest Level/Units Earned(If Not Graduated)" name="graduate_studies_highest_level_units" wire:model="graduate_studies_highest_level_units"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input id="graduate_studies_year_graduated" type="number" label="Year Graduated" name="graduate_studies_year_graduated" wire:model="graduate_studies_year_graduated"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                        <span class="w-4/12">
                            <x-input id="graduate_studies_scholarship_honors" type="text" label="Scholarship/Academic Honors Received" name="graduate_studies_scholarship_honors" wire:model="graduate_studies_scholarship_honors"  class="bg-gray-50 border-gray-300" readonly/>
                        </span>
                    </div>
                </section>
            </div>
        </section>
    @else
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

        @livewire('form.update-education-form', ['id' => $personnel->id])
    @endif
</div>
