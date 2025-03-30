<div class="mx-auto py-6 px-10">
    @if (!$updateMode)
        <section>
            <div class="mb-5 flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Personal Information</h4>

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
                <div>
                    <div class="mt-2 mb-4 p-0 flex space-x-5">
                        <span class="w-3/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="first_name" label="First Name" wire:model="first_name" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="middle_name" label="Middle Name" wire:model="middle_name" readonly/>
                        </span>
                        <span class="w-3/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="last_name" label="Last Name" wire:model="last_name" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="name_ext" label="Name Extension" wire:model="name_ext" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="sex" label="Sex" wire:model="sex" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-5">
                        <span class="w-3/12">
                            <x-input type="date" class="bg-gray-50 border-gray-300" id="date_of_birth" label="Date of Birth" wire:model="date_of_birth" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="place_of_birth" label="Place of Birth" wire:model="place_of_birth" readonly/>
                        </span>
                        <span class="w-3/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="citizenship" label="Citizenship" wire:model="citizenship" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="civil_status" label="Civil Status" wire:model="civil_status" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="blood_type" label="Blood Type" wire:model="blood_type" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-6">
                        <span class="w-1/12">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="height" label="Height" suffix="m" wire:model="height" readonly/>
                        </span>
                        <span class="w-1/12">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="weight" label="Weight" suffix="kg" wire:model="weight" readonly/>
                        </span>
                    </div>
                </div>
                <div class="my-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Government Information</h5>
                    <div class="mt-2 pt-3 mb-4 p-0 flex space-x-5">
                        <span class="w-1/4">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="tin" label="TIN" wire:model="tin" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="sss_num" label="SSS No." wire:model="sss_num" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="gsis_num" label="GSIS No." wire:model="gsis_num" readonly/>
                        </span>
                    </div>
                    <div class="mt-2 pt-3 mb-4 p-0 flex space-x-5">
                        <span class="w-1/4">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="philhealth_num" label="PHILHEALTH NO." wire:model="philhealth_num" readonly/>
                        </span>
                        <span class="w-1/4">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="pagibig_num" label="PAG-IBIG No" wire:model="pagibig_num" readonly/>
                        </span>
                    </div>
                </div>
                <div class="my-10">
    <h5 class="font-bold text-xl text-gray-darkest">Work Information</h5>
    <div class="mt-2 mb-4 p-0 flex space-x-3 items-center">
        <span class="w-3/12">
            <x-input type="number" class="bg-gray-50 border-gray-300" id="personnel_id" label="Personnel ID" wire:model="personnel_id" readonly/>
        </span>
        <span class="w-3/12">
            <x-input type="number" class="bg-gray-50 border-gray-300" id="school_id" label="School ID" wire:model="school_id" readonly/>
        </span>
        <span class="w-2/12">
            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="job_status" label="Job Position" wire:model="job_status" readonly/>
        </span>
        <span class="w-3/12">
            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="category" label="Category" wire:model="category" readonly/>
        </span>
    </div>
    <div class="mt-2 mb-4 p-0 flex space-x-3 item-center">
        <span class="w-3/12">
            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="position" label="Position" wire:model="position" readonly/>
        </span>
        <span class="w-3/12">
            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="fund_source" label="Fund Source" wire:model="fund_source" readonly/>
        </span>
        <span class="w-2/12">
            <x-input type="text" class="bg-gray-50 border-gray-300 capitalize" id="appointment" label="Nature of Appointment" wire:model="appointment" readonly/>
        </span>
        <div class="w-3/12 space-x-1 flex">
            <x-input type="text" class="bg-gray-50 border-gray-300" id="step" label="Step" wire:model="step" readonly/>
            <x-input type="text" class="bg-gray-50 border-gray-300" id="salary_grade" label="Salary Grade" wire:model="salary_grade" readonly/>
        </div>
    </div>
    <div class="mt-2 mb-4 p-0 flex space-x-5" x-data="{ jobStatus: @entangle('job_status') }">
        <span class="w-2/12">
            <x-input type="text" class="bg-gray-50 border-gray-300" id="employment_start" label="Employment Start Date" wire:model="employment_start" readonly/>
        </span>
        <span class="w-2/12">
            <x-input type="text" class="bg-gray-50 border-gray-300" id="employment_end" label="Employment End Date" wire:model="employment_end" readonly/>
        </span>
        <span class="w-2/12">
            <x-input type="number" class="bg-gray-50 border-gray-300" id="salary" label="Salary" wire:model="salary" readonly/>
        </span>
    </div>
</div>

                <div class="mt-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Contact Information</h5>
                    <div class="mt-2 mb-4 p-0 flex space-x-5">
                        <span class="w-3/12">
                            <x-input type="email" class="bg-gray-50 border-gray-300" id="email" label="Email" wire:model="email" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="bg-gray-50 border-gray-300" id="tel_no" label="Telephone No." wire:model="tel_no" readonly/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="number" class="bg-gray-50 border-gray-300" id="mobile_no" label="Mobile No." wire:model="mobile_no" readonly/>
                        </span>
                    </div>
                </div>
            </div>
        </section>
    @else
        @isset($personnel)
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">{{ $personnel ? 'Edit Personal Information' : 'New Personnel' }} </h4>

                <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                          </svg>

                        <p>Back</p>
                    </span>
                </button>
            </div>

            @livewire('form.update-personal-information-form', ['id' => $personnel->id])
        @endisset
    @endif
</div>
