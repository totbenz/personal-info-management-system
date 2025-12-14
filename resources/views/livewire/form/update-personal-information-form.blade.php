{{-- <section>
    <div class="mb-5 flex justify-between">
        <h4 class="font-bold text-2xl text-gray-darkest">{{ $personnel ? 'Edit' : 'New' }} Personal Information</h4>
<div class="w-[16.666667%]">
    <x-button wire:click.prevent="back" label="Back" class="px-5 py-2.5 w-full text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 font-semibold text-xs uppercase tracking-widest" />
</div>
</div>
<div>
    <div>
        <div class="mt-2 mb-4 p-0 flex space-x-5">
            <span class="w-3/12">
                <x-input type="text" class="form-control" id="first_name" label="First Name" wire:model="first_name" />
            </span>
            <span class="w-2/12">
                <x-input type="text" class="form-control" id="middle_name" label="Middle Name" wire:model="middle_name" />
            </span>
            <span class="w-3/12">
                <x-input type="text" class="form-control" id="last_name" label="Last Name" wire:model="last_name" />
            </span>
            <span class="w-2/12">
                <x-input type="text" class="form-control" id="name_ext" label="Name Extension" wire:model="name_ext" />
            </span>
            <span class="w-2/12">
                <x-native-select wire:model="sex" class="form-control" label="Sex">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </x-native-select>
            </span>
        </div>
        <div class="m-0 mb-4 p-0 flex space-x-5">
            <span class="w-3/12">
                <x-input type="date" class="form-control" id="date_of_birth" label="Date of Birth" wire:model="date_of_birth" />
            </span>
            <span class="w-2/12">
                <x-input type="text" class="form-control" id="place_of_birth" label="Place of Birth" wire:model="place_of_birth" />
            </span>
            <span class="w-3/12">
                <x-input type="text" class="form-control" id="citizenship" label="Citizenship" wire:model="citizenship" />
            </span>
            <span class="w-2/12">
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
        </div>
        <div class="m-0 mb-4 p-0 flex space-x-6">
            <span class="w-1/12">
                <x-input type="number" class="form-control" id="height" label="Height" suffix="m" wire:model="height" />
            </span>
            <span class="w-1/12">
                <x-input type="number" class="form-control" id="weight" label="Weight" suffix="kg" wire:model="weight" />
            </span>
        </div>
    </div>
    <div class="my-10">
        <h5 class="font-bold text-xl text-gray-darkest">Government Information</h5>
        <div class="mt-2 pt-3 mb-4 p-0 flex space-x-3 justify-between">
            <span class="w-1/5">
                <x-input type="number" class="form-control" id="tin" label="TIN" wire:model="tin" />
            </span>
            <span class="w-1/5">
                <x-input type="number" class="form-control" id="sss_num" label="SSS No." wire:model="sss_num" />
            </span>
            <span class="w-1/5">
                <x-input type="number" class="form-control" id="gsis_num" label="GSIS No." wire:model="gsis_num" />
            </span>
            <span class="w-1/5">
                <x-input type="number" class="form-control" id="philhealth_num" label="PHILHEALTH NO." wire:model="philhealth_num" />
            </span>
            <span class="w-1/5">
                <x-input type="number" class="form-control" id="pagibig_num" label="PAG-IBIG No" wire:model="pagibig_num" />
            </span>
        </div>
    </div>
    <div class="my-10">
        <h5 class="font-bold text-xl text-gray-darkest">Work Information</h5>
        <div class="mt-2 mb-4 p-0 flex space-x-3 items-center">
            <span class="w-3/12">
                <x-input type="number" class="form-control" id="personnel_id" label="Employee ID" wire:model="personnel_id" />
            </span>
            <span class="w-3/12">
                <x-select
                    wire:model.live.debounce.300ms="school_id"
                    placeholder="Select a school"
                    :async-data="route('api.schools.index')"
                    option-label="school_id"
                    option-value="id"
                    option-description="school_name"
                    label="School ID" />
            </span>
            <span class="w-3/12">
                <x-native-select label="Select Category" wire:model="category">
                    @foreach (['SDO Personnel', 'School Head', 'Elementary School Teacher', 'Junior High School Teacher', 'Senior High School Teacher', 'School Non-teaching Personnel'] as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </x-native-select>
            </span>
            <span class="w-2/12">
                <x-native-select label="Job Status" wire:model="job_status">
                    @foreach (['active', 'vacation', 'terminated', 'on leave', 'suspended', 'resigned', 'probation'] as $status)
                    <option value="{{ $status }}" classification="capitalize">{{ $status }}</option>
                    @endforeach
                </x-native-select>
            </span>
        </div>

        <div class="mt-2 mb-4 p-0 flex space-x-3 item-center">
            <span class="w-3/12">
                <x-select
                    wire:model="position_id"
                    placeholder="Select a position"
                    :async-data="route('api.positions.index')"
                    option-label="title"
                    option-value="id"
                    label="Position" />
            </span>
            <span class="w-3/12">
                <x-input type="text" class="form-control" id="fund_source" label="Fund Source" wire:model="fund_source" />
            </span>
            <span class="w-3/12">
                <x-native-select label="Nature of Appointment" wire:model="appointment">
                    @foreach (['regular', 'part-time', 'temporary', 'contract'] as $appointment)
                    <option value="{{ $appointment }}" classification="capitalize">{{ $appointment }}</option>
                    @endforeach
                </x-native-select>
            </span>
            <div class="w-2/12 space-x-1 flex">
                <x-native-select label="Step" wire:model="step_increment">
                    <option value="">None</option>
                    @foreach (['1', '2', '3', '4', '5', '6', '7', '8'] as $step)
                    <option value="{{ $step }}">{{ $step }}</option>
                    @endforeach
                </x-native-select>
                <x-native-select label="Salary Grade" wire:model="salary_grade_id">
                    @foreach (['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32'] as $grade)
                    <option value="{{ $grade }}">{{ $grade }}</option>
                    @endforeach
                </x-native-select>
            </div>
        </div>
        <div class="mt-2 mb-4 p-0 flex space-x-3" x-data="{ jobStatus: @entangle('job_status') }">
            <span class="w-2/12">
                <x-input type="date" class="form-control" id="employment_start" label="Employment Start Date" wire:model="employment_start" />
            </span>
            <!-- <span class="w-2/12">
                <x-input type="date" class="form-control" id="employment_end" label="Employment End Date" wire:model="employment_end" /> -->
            </span>
        </div>
    </div>
    <div class="mt-10">
        <h5 class="font-bold text-xl text-gray-darkest">Contact Information</h5>
        <div class="mt-2 mb-4 p-0 flex space-x-3">
            <span class="w-3/12">
                <x-input type="email" class="form-control" id="email" label="Email" wire:model="email" />
            </span>
            <span class="w-2/12">
                <x-input type="number" class="form-control" id="tel_no" label="Telephone No." wire:model="tel_no" />
                <span id="tel-error" style="color: red; display: none;">Please enter a valid telephone number.</span>
            </span>
            <span class="w-2/12">
                <x-input type="number" class="form-control" id="mobile_no" label="Mobile No." wire:model="mobile_no" />
            </span>
        </div>
    </div>
</div>
<div class="my-5 p-0 flex space-x-3 justify-end">
    <div class="w-2/12">
        <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150" />
    </div>
    <div class="w-2/12">
        <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover" />
    </div>
</div>
</section> --}}
<section>
    <div>
        <div>
            <div class="mt-2 mb-4 p-0 flex space-x-5">
                <span class="w-3/12">
                    <x-input type="text" class="form-control" id="first_name" label="First Name" wire:model.live="first_name"
                        required />
                    <p class="text-xs text-gray-500 mt-1">Enter your legal first name as shown in government documents</p>
                    @error('first_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="first_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-input type="text" class="form-control" id="middle_name" label="Middle Name"
                        wire:model.live="middle_name" required />
                    <p class="text-xs text-gray-500 mt-1">Enter your complete middle name</p>
                    @error('middle_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="middle_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-3/12">
                    <x-input type="text" class="form-control" id="last_name" label="Last Name" wire:model.live="last_name"
                        required />
                    <p class="text-xs text-gray-500 mt-1">Enter your family name/last name</p>
                    @error('last_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="last_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-input type="text" class="form-control" id="name_ext" label="Name Extension"
                        wire:model.live="name_ext" required />
                    <p class="text-xs text-gray-500 mt-1">e.g., Jr., Sr., III (if applicable)</p>
                    @error('name_ext')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="name_ext" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-native-select wire:model.live="sex" class="form-control" label="Sex">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Select your biological sex</p>
                    @error('sex')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="sex" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
            </div>
            <div class="m-0 mb-4 p-0 flex space-x-5">
                <span class="w-3/12">
                    <x-input type="date" class="form-control" id="date_of_birth" label="Date of Birth"
                        wire:model="date_of_birth" required />
                    <p class="text-xs text-gray-500 mt-1">Format: YYYY-MM-DD</p>
                    @error('date_of_birth')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="date_of_birth" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-input type="text" class="form-control" id="place_of_birth" label="Place of Birth"
                        wire:model="place_of_birth" required />
                    <p class="text-xs text-gray-500 mt-1">City/Municipality, Province</p>
                    @error('place_of_birth')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="place_of_birth" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-3/12">
                    <x-input type="text" class="form-control" id="citizenship" label="Citizenship"
                        wire:model.live="citizenship" required />
                    <p class="text-xs text-gray-500 mt-1">e.g., Filipino, American, etc.</p>
                    @error('citizenship')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="citizenship" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-native-select wire:model.live="civil_status" class="form-control" label="Civil Status">
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="seperated">Seperated</option>
                        <option value="widowed">Widowed</option>
                        <option value="divorced">Divorced</option>
                        <option value="others">Others</option>
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Select current marital status</p>
                    @error('civil_status')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="civil_status" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-native-select wire:model.live="blood_type" class="form-control" label="Blood Type">
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Important for medical emergencies</p>
                    @error('blood_type')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="blood_type" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
            </div>
            <div class="m-0 mb-4 p-0 flex space-x-6">
                <span class="w-1/12">
                    <x-input type="number" class="form-control" id="height" label="Height" suffix="m"
                        wire:model.live="height" required />
                    <p class="text-xs text-gray-500 mt-1">In meters (e.g., 1.65)</p>
                    @error('height')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="height" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-1/12">
                    <x-input type="number" class="form-control" id="weight" label="Weight" suffix="kg"
                        wire:model.live="weight" required />
                    <p class="text-xs text-gray-500 mt-1">In kilograms</p>
                    @error('weight')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="weight" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
            </div>
        </div>
        <div class="my-10">
            <h5 class="font-bold text-xl text-gray-darkest">Government Information</h5>
            <div class="mt-2 pt-3 mb-4 p-0 flex space-x-5">
                <span class="w-1/4">
                    <x-input type="number" class="form-control" id="tin" label="TIN" wire:model.live="tin" required />
                    <p class="text-xs text-gray-500 mt-1">Tax Identification Number (8-12 digits)</p>
                    @error('tin')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="tin" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-1/4">
                    <x-input type="number" class="form-control" id="sss_num" label="SSS No." wire:model.live="sss_num" required />
                    <p class="text-xs text-gray-500 mt-1">Social Security System Number (10 digits)</p>
                    @error('sss_num')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="sss_num" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-1/4">
                    <x-input type="number" class="form-control" id="gsis_num" label="GSIS No."
                        wire:model.live="gsis_num" required />
                    <p class="text-xs text-gray-500 mt-1">Government Service Insurance System</p>
                    @error('gsis_num')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="gsis_num" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
            </div>
            <div class="mt-2 pt-3 mb-4 p-0 flex space-x-5">
                <span class="w-1/4">
                    <x-input type="number" class="form-control" id="philhealth_num" label="PHILHEALTH NO."
                        wire:model.live="philhealth_num" required />
                    <p class="text-xs text-gray-500 mt-1">PhilHealth Insurance Number (11+ digits)</p>
                    @error('philhealth_num')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="philhealth_num" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-1/4">
                    <x-input type="number" class="form-control" id="pagibig_num" label="PAG-IBIG No"
                        wire:model.live="pagibig_num" required />
                    <p class="text-xs text-gray-500 mt-1">Home Development Mutual Fund Number</p>
                    @error('pagibig_num')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="pagibig_num" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-3/12">
                    <x-input type="text" class="form-control" id="pantilla_of_personnel" label="Pantilla of Personnel" wire:model.live="pantilla_of_personnel" name="pantilla_of_personnel" />
                    <p class="text-xs text-gray-500 mt-1">Payroll reference number</p>
                    @error('pantilla_of_personnel')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="pantilla_of_personnel" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
            </div>
        </div>
        @if(Auth::user() && in_array(Auth::user()->role, ['admin', 'school_head']))
        <div class="my-10">
            <h5 class="font-bold text-xl text-gray-darkest">Work Information</h5>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-amber-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-amber-800">
                        <strong>Important:</strong> Changing position, employment dates, or school will trigger service record separation and require a cause of separation.
                    </p>
                </div>
            </div>
            <div class="mt-2 mb-4 p-0 flex space-x-3 items-center">
                <span class="w-2/12">
                    <x-input type="number" class="form-control" id="personnel_id" label="Personnel ID" wire:model.live="personnel_id" required />
                    <p class="text-xs text-gray-500 mt-1">Unique employee identification number</p>
                    @error('personnel_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="personnel_id" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-select wire:model="school_id" id="school_id" name="school_id" placeholder="Select a school" :async-data="route('api.schools.index')" option-label="school_id" option-value="id" option-description="school_name" label="School ID" class="form-control" />
                    <p class="text-xs text-gray-500 mt-1">Assigned school</p>
                    @error('school_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="school_id" class="text-xs text-blue-600 mt-1">Loading...</div>
                    <div x-show="school_id != original_school_id" class="text-xs text-amber-600 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        This change will trigger service record separation
                    </div>
                </span>
                <span class="w-2/12">
                    <x-native-select label="Job Status" wire:model.live="job_status" id="job_status" name="job_status" class="form-control">
                        @php
                        $teachingClassifications = ['teaching', 'teaching-related'];
                        $position = null;
                        try {
                        if (!empty($position_id)) {
                        $position = \App\Models\Position::find($position_id);
                        }
                        } catch (\Exception $e) {}
                        @endphp
                        @if ($position && in_array(strtolower($position->classification), $teachingClassifications))
                        @foreach (["active", "vacation", "terminated", "suspended", "resigned", "probation", "personal leave", "sick leave"] as $status)
                        <option value="{{ $status }}" classification="capitalize">{{ ucfirst($status) }}</option>
                        @endforeach
                        @else
                        @foreach (["active", "vacation", "terminated", "suspended", "resigned", "probation", "vacation leave", "sick leave", "compensatory time off", "force leave", "special privilege leave", "personal leave", "maternity leave", "study leave", "rehabilitation leave"] as $status)
                        <option value="{{ $status }}" classification="capitalize">{{ ucfirst($status) }}</option>
                        @endforeach
                        @endif
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Current employment status</p>
                    @error('job_status')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="job_status" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-native-select label="Select Category" wire:model.live="category" id="category" name="category" class="form-control">
                        @foreach (["SDO Personnel", "School Head", "Elementary School Teacher", "Junior High School Teacher", "Senior High School Teacher", "School Non-teaching Personnel"] as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Employment category/classification</p>
                    @error('category')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="category" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-select wire:model="position_id" id="position_id" name="position_id" placeholder="Select a position" :async-data="route('api.positions.index')" option-label="title" option-value="id" label="Position" class="form-control" />
                    <p class="text-xs text-gray-500 mt-1">Current position/role</p>
                    @error('position_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="position_id" class="text-xs text-blue-600 mt-1">Loading...</div>
                    <div x-show="position_id != original_position_id" class="text-xs text-amber-600 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        This change will trigger service record separation
                    </div>
                </span>
                <span class="w-2/12">
                    <x-input type="text" class="form-control" id="fund_source" label="Fund Source" wire:model.live="fund_source" name="fund_source" required />
                    <p class="text-xs text-gray-500 mt-1">Source of funds (e.g., General Fund, SEF)</p>
                    @error('fund_source')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="fund_source" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-native-select label="Nature of Appointment" wire:model.live="appointment" name="appointment" class="form-control">
                        @foreach (["regular", "part-time", "temporary", "contract"] as $appointment)
                        <option value="{{ $appointment }}" classification="capitalize">{{ ucfirst($appointment) }}</option>
                        @endforeach
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Type of employment appointment</p>
                    @error('appointment')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="appointment" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-1/12">
                    <x-native-select label="Step Increment" wire:model.live="step_increment" id="step" name="step">
                        <option value="">None</option>
                        @foreach (["1", "2", "3", "4", "5", "6", "7", "8"] as $step_increment)
                        <option value="{{ $step_increment }}">{{ $step_increment }}</option>
                        @endforeach
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Salary step level</p>
                    @error('step_increment')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="step_increment" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-1/12">
                    <x-native-select label="Salary Grade" wire:model.live="salary_grade_id" id="salary_grade" name="salary_grade">
                        @foreach (["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32"] as $grade)
                        <option value="{{ $grade }}">{{ $grade }}</option>
                        @endforeach
                    </x-native-select>
                    <p class="text-xs text-gray-500 mt-1">Government salary grade level</p>
                    @error('salary_grade_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="salary_grade_id" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
            </div>
            <div class="mt-2 mb-4 p-0 flex space-x-3 items-center">
                <span class="w-2/12">
                    <x-input type="date" class="form-control" id="employment_start" name="employment_start" label="Employment Start Date" wire:model="employment_start" required />
                    <p class="text-xs text-gray-500 mt-1">Original hire date</p>
                    @error('employment_start')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div x-show="employment_start != original_employment_start" class="text-xs text-amber-600 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        This change will trigger service record separation
                    </div>
                </span>
                <span class="w-2/12">
                    <x-input type="date" class="form-control" id="employment_end" name="employment_end" label="Employment End Date" wire:model="employment_end" required />
                    <p class="text-xs text-gray-500 mt-1">Leave end date (if applicable)</p>
                    @error('employment_end')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div x-show="employment_end != original_employment_end" class="text-xs text-amber-600 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        This change will trigger service record separation
                    </div>
                </span>
                <span class="w-2/12">
                    <x-input type="number" class="form-control" id="leave_of_absence_without_pay_count" name="leave_of_absence_without_pay_count" label="LOA w/o pay" wire:model.live="leave_of_absence_without_pay_count" min="0" required />
                    <p class="text-xs text-gray-500 mt-1">Number of leave days without pay</p>
                    @error('leave_of_absence_without_pay_count')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="leave_of_absence_without_pay_count" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-input type="number" class="form-control bg-gray-50 border-gray-300" id="salary" name="salary" label="Calculated Salary" wire:model="salary" readonly />
                    <p class="text-xs text-gray-500 mt-1">Automatically calculated based on salary grade and step increment</p>
                </span>
            </div>
            @if($show_separation_cause_input)
            <div class="mt-2 mb-4 p-0">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-red-800">Service Record Separation Required</h3>
                            <div class="mt-2">
                                <x-input type="text" class="form-control border-red-300 focus:border-red-500 focus:ring-red-500" id="separation_cause_input" name="separation_cause_input" label="Cause of Separation" wire:model="separation_cause_input" placeholder="e.g. Promotion, Transfer, Retirement, Resignation, etc." required />
                                <p class="text-xs text-red-600 mt-1">This field is required when employment dates, position, or school is changed. Please specify the reason for this service record change.</p>
                                @error('separation_cause_input')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
        <div class="mt-10">
            <h5 class="font-bold text-xl text-gray-darkest">Contact Information</h5>
            <div class="mt-2 mb-4 p-0 flex space-x-5">
                <span class="w-3/12">
                    <x-input type="email" class="form-control" id="email" name="email" label="Email"
                        wire:model.live="email" required />
                    <p class="text-xs text-gray-500 mt-1">Professional email address</p>
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="email" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-input type="text" class="form-control" id="tel_no" name="tel_no"
                        label="Telephone No." wire:model.live="tel_no" required />
                    <p class="text-xs text-gray-500 mt-1">Landline number (with area code)</p>
                    @error('tel_no')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="tel_no" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
                <span class="w-2/12">
                    <x-input type="number" class="form-control" id="mobile_no" label="Mobile No."
                        wire:model.live="mobile_no" name="mobile_no" required />
                    <p class="text-xs text-gray-500 mt-1">Mobile phone number</p>
                    @error('mobile_no')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="mobile_no" class="text-xs text-blue-600 mt-1">Validating...</div>
                </span>
            </div>
        </div>

        <div class="my-5 p-0 flex space-x-3 justify-end">
            <div class="w-2/12">
                <x-button wire:click.prevent="cancel" label="Cancel"
                    class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150" />
            </div>
            <div class="w-2/12" x-data="personnelForm" x-init="initWatchers()">
                <button type="button"
                    class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:bg-main_hover hover:scale-105 duration-150 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!allRequiredFilled" @click.prevent="$wire.save()">
                    Save
                </button>
                <div x-show="missingFields.length > 0"
                    class="mt-2 text-xs text-red-600 bg-red-50 border border-red-200 rounded p-2" x-transition>
                    <span>Please fill the following required fields to enable saving.</span>
                    <ul class="list-disc ml-5">
                        <template x-for="field in missingFields" :key="field">
                            <li x-text="field"></li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Enhanced personnel form with real-time validation and warnings
        document.addEventListener('livewire:load', function() {
            // Personnel form validation and UX enhancements
            window.personnelForm = function() {
                return {
                    allRequiredFilled: false,
                    missingFields: [],
                    originalValues: {},

                    init() {
                        this.initWatchers();
                        this.validateRequiredFields();
                        this.storeOriginalValues();
                    },

                    initWatchers() {
                        // Watch for changes in work-related fields
                        const workFields = ['position_id', 'school_id', 'employment_start', 'employment_end'];
                        workFields.forEach(field => {
                            const element = document.getElementById(field);
                            if (element) {
                                element.addEventListener('change', () => this.checkWorkFieldChanges(field));
                                element.addEventListener('input', () => this.checkWorkFieldChanges(field));
                            }
                        });

                        // Watch all required fields for validation
                        const requiredFields = document.querySelectorAll('input[required], select[required]');
                        requiredFields.forEach(field => {
                            field.addEventListener('input', () => this.validateRequiredFields());
                            field.addEventListener('change', () => this.validateRequiredFields());
                        });
                    },

                    storeOriginalValues() {
                        // Store original values for comparison
                        this.originalValues = {
                            position_id: @json($original_position_id),
                            school_id: @json($original_school_id),
                            employment_start: @json($original_employment_start),
                            employment_end: @json($original_employment_end)
                        };
                    },

                    checkWorkFieldChanges(fieldName) {
                        const element = document.getElementById(fieldName);
                        if (!element) return;

                        const currentValue = element.value;
                        const originalValue = this.originalValues[fieldName];

                        // Show/hide separation warnings
                        const warningElement = element.parentElement.querySelector('.text-amber-600');
                        if (warningElement) {
                            if (currentValue !== originalValue) {
                                warningElement.style.display = 'flex';
                                this.animateWarning(warningElement);
                            } else {
                                warningElement.style.display = 'none';
                            }
                        }
                    },

                    animateWarning(element) {
                        element.style.animation = 'pulse 2s';
                        setTimeout(() => {
                            element.style.animation = '';
                        }, 2000);
                    },

                    validateRequiredFields() {
                        const requiredFields = document.querySelectorAll('input[required], select[required]');
                        this.missingFields = [];

                        requiredFields.forEach(field => {
                            if (!field.value.trim()) {
                                const label = field.getAttribute('label') || field.getAttribute('aria-label') || field.name || field.id;
                                this.missingFields.push(label);
                            }
                        });

                        this.allRequiredFilled = this.missingFields.length === 0;
                    }
                }
            };

            // Add CSS animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.7; }
                }

                .form-control:focus {
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
                }

                .form-control.is-invalid {
                    border-color: #ef4444;
                    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
                }

                .form-control.is-valid {
                    border-color: #10b981;
                    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
                }
            `;
            document.head.appendChild(style);

            // Real-time validation for specific fields
            const validationRules = {
                email: {
                    pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                    message: 'Please enter a valid email address'
                },
                tin: {
                    pattern: /^\d{8,12}$/,
                    message: 'TIN must be 8-12 digits'
                },
                sss_num: {
                    pattern: /^\d{10}$/,
                    message: 'SSS number must be exactly 10 digits'
                },
                mobile_no: {
                    pattern: /^\d{10,11}$/,
                    message: 'Mobile number must be 10-11 digits'
                }
            };

            // Add real-time validation listeners
            Object.keys(validationRules).forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('blur', function() {
                        const rule = validationRules[fieldName];
                        if (field.value && !rule.pattern.test(field.value)) {
                            field.classList.add('is-invalid');
                            field.classList.remove('is-valid');
                            showFieldError(field, rule.message);
                        } else if (field.value) {
                            field.classList.add('is-valid');
                            field.classList.remove('is-invalid');
                            hideFieldError(field);
                        }
                    });

                    field.addEventListener('input', function() {
                        if (field.classList.contains('is-invalid')) {
                            field.classList.remove('is-invalid');
                            hideFieldError(field);
                        }
                    });
                }
            });

            function showFieldError(field, message) {
                hideFieldError(field); // Remove existing error

                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-xs text-red-600 mt-1';
                errorDiv.textContent = message;
                errorDiv.setAttribute('data-error-for', field.id);

                field.parentElement.appendChild(errorDiv);
            }

            function hideFieldError(field) {
                const existingError = field.parentElement.querySelector(`[data-error-for="${field.id}"]`);
                if (existingError) {
                    existingError.remove();
                }
            }

            // SweetAlert for separation cause
            Livewire.on('prompt-separation-cause', () => {
                Swal.fire({
                    title: 'Cause of Separation Required',
                    html: `
                        <div class="text-left">
                            <p class="text-gray-600 mb-3">You've made changes that require a service record separation. Please specify the reason:</p>
                            <div class="bg-amber-50 border border-amber-200 rounded p-3 mb-3">
                                <p class="text-sm text-amber-800">
                                    <strong>Common reasons:</strong><br>
                                    â€¢ Promotion<br>
                                    â€¢ Transfer<br>
                                    â€¢ Retirement<br>
                                    â€¢ Resignation<br>
                                    â€¢ End of Contract<br>
                                    â€¢ Reassignment
                                </p>
                            </div>
                        </div>
                    `,
                    input: 'text',
                    inputLabel: 'Cause of Separation',
                    inputPlaceholder: 'e.g. Promotion to Senior Teacher',
                    showCancelButton: true,
                    confirmButtonText: 'Save Separation Cause',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc2626',
                    inputValidator: (value) => {
                        if (!value || value.trim().length < 3) {
                            return 'Please provide a detailed cause of separation (at least 3 characters)';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('saveSeparationCause', result.value);
                    }
                });
            });
        });
    </script>
</section>

