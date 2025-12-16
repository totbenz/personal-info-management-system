<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Create New Personnel</h2>
        <a href="{{ route('personnels.index') }}" class="text-gray-600 hover:text-gray-800">
            <button class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                Back to List
            </button>
        </a>
    </div>

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="{{ $activeTab === 'personal' ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                    1
                </div>
                <span class="ml-2 text-sm font-medium {{ $activeTab === 'personal' ? 'text-blue-600' : 'text-gray-500' }}">Personal Info</span>
            </div>
            <div class="flex-1 h-1 mx-4 {{ $activeTab === 'work' || $activeTab === 'government' ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
            <div class="flex items-center">
                <div class="{{ $activeTab === 'work' ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                    2
                </div>
                <span class="ml-2 text-sm font-medium {{ $activeTab === 'work' ? 'text-blue-600' : 'text-gray-500' }}">Work Info</span>
            </div>
            <div class="flex-1 h-1 mx-4 {{ $activeTab === 'government' ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
            <div class="flex items-center">
                <div class="{{ $activeTab === 'government' ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                    3
                </div>
                <span class="ml-2 text-sm font-medium {{ $activeTab === 'government' ? 'text-blue-600' : 'text-gray-500' }}">Government Info</span>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form wire:submit.prevent="save">
        <!-- Personal Information Tab -->
        <div class="{{ $activeTab !== 'personal' ? 'hidden' : '' }}">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Name Fields -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('first_name') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.live="first_name" class="w-full px-3 py-2 {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('middle_name') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Middle Name
                        </label>
                        <input type="text" wire:model.live="middle_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('last_name') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.live="last_name" class="w-full px-3 py-2 {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('name_ext') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Name Extension
                        </label>
                        <input type="text" wire:model.live="name_ext" placeholder="e.g., Jr., Sr., III" class="w-full px-3 py-2 {{ $errors->has('name_ext') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('name_ext') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Gender and Civil Status -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('sex') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Sex <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="sex" class="w-full px-3 py-2 {{ $errors->has('sex') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('sex') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('civil_status') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Civil Status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="civil_status" class="w-full px-3 py-2 {{ $errors->has('civil_status') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="widowed">Widowed</option>
                            <option value="divorced">Divorced</option>
                            <option value="seperated">Separated</option>
                            <option value="others">Others</option>
                        </select>
                        @error('civil_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Birth Details -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('date_of_birth') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Date of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="date_of_birth" class="w-full px-3 py-2 {{ $errors->has('date_of_birth') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('date_of_birth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('place_of_birth') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Place of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="place_of_birth" class="w-full px-3 py-2 {{ $errors->has('place_of_birth') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('place_of_birth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Physical Attributes -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('citizenship') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Citizenship <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="citizenship" class="w-full px-3 py-2 {{ $errors->has('citizenship') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('citizenship') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Height in CM</label>
                        <input type="text" wire:model="height" placeholder="e.g., 170 cm" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight in KG</label>
                        <input type="text" wire:model="weight" placeholder="e.g., 65 kg" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                        <select wire:model="blood_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('email') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Email
                        </label>
                        <input type="email" wire:model.live="email" class="w-full px-3 py-2 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telephone No.</label>
                        <input type="tel" wire:model="tel_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile No.</label>
                        <input type="tel" wire:model="mobile_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Information Tab -->
        <div class="{{ $activeTab !== 'work' ? 'hidden' : '' }}">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Work Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Employee ID -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('personnel_id') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Employee ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.live="personnel_id" class="w-full px-3 py-2 {{ $errors->has('personnel_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('personnel_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- School -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                        <select wire:model.live="school_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->school_id }} - {{ $school->school_name }}</option>
                            @endforeach
                        </select>
                        @error('school_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('position_id') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Position <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="position_id" class="w-full px-3 py-2 {{ $errors->has('position_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Position</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->title }}</option>
                            @endforeach
                        </select>
                        @error('position_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Appointment -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('appointment') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Appointment <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="appointment" class="w-full px-3 py-2 {{ $errors->has('appointment') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="regular">Regular</option>
                            <option value="part-time">Part-time</option>
                            <option value="temporary">Temporary</option>
                            <option value="contract">Contract</option>
                        </select>
                        @error('appointment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Fund Source -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('fund_source') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Fund Source <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="fund_source" class="w-full px-3 py-2 {{ $errors->has('fund_source') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('fund_source') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Salary Grade and Step -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('salary_grade_id') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Salary Grade <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="salary_grade_id" class="w-full px-3 py-2 {{ $errors->has('salary_grade_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            @foreach($salaryGrades as $grade)
                                <option value="{{ $grade->id }}">Grade {{ $grade->grade }}</option>
                            @endforeach
                        </select>
                        @error('salary_grade_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('step_increment') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Step Increment <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="step_increment" class="w-full px-3 py-2 {{ $errors->has('step_increment') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            @foreach($steps as $step)
                                <option value="{{ $step }}">Step {{ $step }}</option>
                            @endforeach
                        </select>
                        @error('step_increment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('category') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="category" class="w-full px-3 py-2 {{ $errors->has('category') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="SDO Personnel">SDO Personnel</option>
                            <option value="School Head">School Head</option>
                            <option value="Elementary School Teacher">Elementary School Teacher</option>
                            <option value="Junior High School Teacher">Junior High School Teacher</option>
                            <option value="Senior High School Teacher">Senior High School Teacher</option>
                            <option value="School Non-teaching Personnel">School Non-teaching Personnel</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Job Status -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('job_status') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Job Status <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="job_status" placeholder="e.g., Active, On Leave" class="w-full px-3 py-2 {{ $errors->has('job_status') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('job_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Employment Dates -->
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('employment_start') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Employment Start <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="employment_start" class="w-full px-3 py-2 {{ $errors->has('employment_start') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('employment_start') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('employment_end') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Employment End
                        </label>
                        <input type="date" wire:model="employment_end" class="w-full px-3 py-2 {{ $errors->has('employment_end') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('employment_end') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pantilla -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pantilla of Personnel</label>
                        <input type="text" wire:model="pantilla_of_personnel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <!-- Solo Parent -->
                    <div class="flex items-center mt-6">
                        <input type="checkbox" wire:model="is_solo_parent" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        <label class="ml-2 block text-sm text-gray-700">Is Solo Parent</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Government Information Tab -->
        <div class="{{ $activeTab !== 'government' ? 'hidden' : '' }}">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Government Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('tin') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            TIN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.live="tin" maxlength="12" placeholder="12 digits" class="w-full px-3 py-2 {{ $errors->has('tin') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('tin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('sss_num') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            SSS Number
                        </label>
                        <input type="text" wire:model.live="sss_num" maxlength="10" placeholder="10 digits" class="w-full px-3 py-2 {{ $errors->has('sss_num') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('sss_num') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('gsis_num') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            GSIS Number
                        </label>
                        <input type="text" wire:model.live="gsis_num" maxlength="11" placeholder="11 digits" class="w-full px-3 py-2 {{ $errors->has('gsis_num') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('gsis_num') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('philhealth_num') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            PhilHealth Number
                        </label>
                        <input type="text" wire:model.live="philhealth_num" maxlength="12" placeholder="12 digits" class="w-full px-3 py-2 {{ $errors->has('philhealth_num') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('philhealth_num') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium {{ $errors->has('pagibig_num') ? 'text-red-600' : 'text-gray-700' }} mb-1">
                            Pag-IBIG Number
                        </label>
                        <input type="text" wire:model.live="pagibig_num" maxlength="12" placeholder="12 digits" class="w-full px-3 py-2 {{ $errors->has('pagibig_num') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('pagibig_num') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between items-center">
            <div>
                @if($activeTab !== 'personal')
                    <button type="button" wire:click="previousTab" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                        Previous
                    </button>
                @endif
            </div>

            <div>
                @if($activeTab !== 'government')
                    <button type="button" wire:click="nextTab" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                        Next
                    </button>
                @else
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                        Save Personnel
                    </button>
                @endif
            </div>
        </div>
    </form>

    <!-- Success/Error Messages Script -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showSuccess', (message) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false,
                });
            });

            Livewire.on('showError', (message) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                });
            });

            Livewire.on('showValidationErrors', (errors) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errors.map(err => `<div class="text-left">• ${err}</div>`).join(''),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                });
            });

            Livewire.on('redirectToList', (url) => {
                setTimeout(() => {
                    window.location.href = url;
                }, 2000);
            });
        });
    </script>
</div>
