<x-modal name="create-personnel-modal" blur="2xl">
    <x-card>
        <form action="{{ route('personnels.store') }}" method="post">
            @csrf
            <div class="px-5 py-5">
                <div>
                    <div class="mt-2 mb-4 p-0 flex space-x-5">
                        <span class="w-4/12">
                            <x-input type="text" class="form-control" id="first_name" label="First Name" wire:model="first_name" required/>
                        </span>
                        <span class="w-4/12">
                            <x-input type="text" class="form-control" id="middle_name" label="Middle Name" wire:model="middle_name" required/>
                        </span>
                        <span class="w-4/12">
                            <x-input type="text" class="form-control" id="last_name" label="Last Name" wire:model="last_name" required/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-5">
                        <span class="w-3/12">
                            <x-input type="date" class="form-control" id="date_of_birth" label="Date of Birth" wire:model="date_of_birth" required/>
                        </span>
                        <span class="w-3/12">
                            <x-input type="text" class="form-control" id="place_of_birth" label="Place of Birth" wire:model="place_of_birth" required/>
                        </span>
                        <span class="w-3/12">
                            <x-native-select wire:model="sex" class="form-control" label="Sex">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </x-native-select>
                        </span>
                        <span class="w-3/12">
                            <x-input type="text" class="form-control" id="name_ext" label="Name Extension" wire:model="name_ext" required/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-5">
                        <span class="w-3/12">
                            <x-input type="text" class="form-control" id="citizenship" label="Citizenship" wire:model="citizenship" required/>
                        </span>
                        <span class="w-3/12">
                            <x-native-select wire:model="civil_status" class="form-control" label="Civil Status">
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="seperated">Seperated</option>
                                <option value="widowed">Widowed</option>
                                <option value="divorced">Divorced</option>
                                <option value="others">Others</option>
                            </x-native-select>
                        </span>
                        <span class="w-2/12">
                            <x-native-select wire:model="blood_type" class="form-control" label="Blood Type">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </x-native-select>
                        </span>
                        <span class="w-2/12">
                            <x-input type="number" class="form-control" id="height" label="Height" suffix="m" wire:model="height" required/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="number" class="form-control" id="weight" label="Weight" suffix="kg" wire:model="weight" required/>
                        </span>
                    </div>
                </div>
                {{-- <div class="my-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Government Information</h5>
                    <div class="mt-2 pt-3 mb-4 p-0 flex space-x-5">
                        <span class="w-2/6">
                            <x-input type="number" class="form-control" id="tin" label="TIN" wire:model="tin" required/>
                        </span>
                        <span class="w-2/6">
                            <x-input type="number" class="form-control" id="sss_num" label="SSS No." wire:model="sss_num" required/>
                        </span>
                        <span class="w-2/6">
                            <x-input type="number" class="form-control" id="gsis_num" label="GSIS No." wire:model="gsis_num" required/>
                        </span>
                    </div>
                    <div class="mt-2 pt-3 mb-4 p-0 flex space-x-5">
                        <span class="w-2/6">
                            <x-input type="number" class="form-control" id="philhealth_num" label="PHILHEALTH NO." wire:model="philhealth_num" required/>
                        </span>
                        <span class="w-2/6">
                            <x-input type="number" class="form-control" id="pagibig_num" label="PAG-IBIG No" wire:model="pagibig_num" required/>
                        </span>
                    </div>
                </div> --}}
                <div class="my-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Work Information</h5>
                    <div class="mt-2 mb-4 p-0 flex space-x-3 items-center">
                        <span class="w-2/6">
                            <x-input type="number" class="form-control" id="personnel_id" label="Personnel ID" wire:model="personnel_id" required/>
                        </span>
                        <span class="w-3/6">
                            <x-select
                                wire:model="school_id"
                                id="school_id"
                                name="school_id"
                                placeholder="Select a school"
                                :async-data="route('api.schools.index')"
                                option-label="school_id"
                                option-value="id"
                                option-description="school_name"
                                label="School ID"
                                class="form-control"
                            />
                        </span>
                        <span class="w-1/6">
                            <x-native-select label="Job Status" wire:model="job_status" id="job_status" name="job_status" class="form-control">
                                @foreach(['active', 'vacation', 'terminated', 'on leave', 'suspended', 'resigned', 'probation'] as $status)
                                    <option value="{{ $status }}" class="capitalize">{{ ucfirst($status) }}</option>
                                @endforeach
                            </x-native-select>
                        </span>
                    </div>
                    <div class="mt-2 mb-4 p-0 flex space-x-3 item-center">
                        <span class="w-3/6">
                            <x-native-select label="Nature of Appointment" wire:model="appointment" name="appointment" class="form-control">
                                @foreach(['regular', 'part-time', 'temporary', 'contract'] as $appointment)
                                    <option value="{{ $appointment }}" classification="capitalize">{{ ucfirst($appointment) }}</option>
                                @endforeach
                            </x-native-select>
                        </span>
                        <span class="w-4/6">
                            <x-select
                                wire:model="position_id"
                                id="position_id"
                                name="position_id"
                                placeholder="Select a position"
                                :async-data="route('api.positions.index')"
                                option-label="title"
                                option-value="id"
                                label="Position"
                                class="form-control"
                            />
                        </span>
                    </div>
                    <div class="mt-2 mb-4 p-0 flex space-x-3 item-center">
                        <span class="w-3/6">
                            <x-input type="text" class="form-control" id="fund_source" label="Fund Source" wire:model="fund_source" name="fund_source" required/>
                        </span>
                        <span class="w-3/6">
                            <x-native-select label="Select Category" wire:model="category" id="category" name="category" class="form-control">
                                @foreach(['SDO Personnel', 'School Head', 'Elementary School Teacher', 'Junior High School Teacher', 'Senior High School Teacher', 'School Non-teaching Personnel'] as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </x-native-select>
                        </span>
                    </div>
                    <div class="mt-2 mb-4 p-0 flex space-x-5" x-data="{ jobStatus: @entangle('job_status') }">
                        <span class="w-2/6 flex items-center space-x-2">
                            <x-native-select label="Step" wire:model="step" id="step" name="step">
                                <option value="">None</option>
                                @foreach(['1', '2', '3', '4', '5', '6', '7', '8'] as $step)
                                    <option value="{{ $step }}">{{ $step }}</option>
                                @endforeach
                            </x-native-select>
                            <x-native-select label="Salary Grade" wire:model="salary_grade" id="salary_grade" name="salary_grade">
                                @foreach(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'] as $grade)
                                    <option value="{{ $grade }}">{{ $grade }}</option>
                                @endforeach
                            </x-native-select>
                        </span>
                        <span class="w-2/6">
                            <x-input type="date" class="form-control" id="employment_start" name="employment_start" label="Employment Start Date" wire:model="employment_start" required />
                        </span>
                        <span class="w-2/6">
                            <x-input type="date" class="form-control" id="employment_end" name="employment_end" label="Employment End Date" wire:model="employment_end" x-bind:disabled="jobStatus === 'terminated' || jobStatus === 'resigned'" />
                        </span>
                    </div>
                </div>
                {{-- <div class="mt-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Contact Information</h5>
                    <div class="mt-2 mb-4 p-0 flex space-x-5">
                        <span class="w-3/12">
                            <x-input type="email" class="form-control" id="email" name="email" label="Email" wire:model="email" required/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="text" class="form-control" id="tel_no" name="tel_no" label="Telephone No." wire:model="tel_no" required/>
                        </span>
                        <span class="w-2/12">
                            <x-input type="number" class="form-control" id="mobile_no" label="Mobile No." wire:model="mobile_no" name="mobile_no" required/>
                        </span>
                    </div>
                </div> --}}

                <div class="my-5 p-0 flex space-x-3 justify-end">
                    <div class="w-2/12">
                        <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
                    </div>
                    <div class="w-2/12">
                        <x-button type="submit" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                    </div>
                </div>

            </div>
        </form>
    </x-card>
</x-modal>
