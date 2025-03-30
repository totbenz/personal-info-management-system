<div class="mx-auto pt-3 pb-10 px-5">
    <h4 class="font-bold text-2xl text-gray-darkest">Personal Information</h4>
    <div class="mt-5">
        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
            <span class="w-1/4">
                <label for="first_name" class="pb-1 block font-medium text-sm text-gray-700">First Name</label>
                <x-input id="first_name" wire:model="state.first_name" type="text" name="first_name" required/>
            </span>
            <span class="w-1/4">
                <label for="middle_name" class="pb-1 block font-medium text-sm text-gray-700">Middle Name</label>
                <x-input id="middle_name" wire:model="state.middle_name" type="text" name="middle_name" required/>
            </span>
            <span class="w-1/4">
                <label for="last_name" class="pb-1 block font-medium text-sm text-gray-700">Last Name</label>
                <x-input id="last_name" wire:model="state.last_name" type="text" name="last_name" required/>
            </span>
            <span class="w-1/4">
                <label for="name_ext" class="pb-1 block font-medium text-sm text-gray-700">Name Extension (JR., SR)</label>
                <x-input id="name_ext" wire:model="state.name_ext" type="text" name="name_ext" required/>
            </span>
        </div>
        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
            <span class="w-3/12">
                <label for="date_of_birth" class="pb-1 block font-medium text-sm text-gray-700">Date of Birth</label>
                <x-input id="date_of_birth" wire:model="state.date_of_birth" type="date" name="date_of_birth" required/>
            </span>
            <span class="w-3/12">
                <label for="place_of_birth" class="pb-1 block font-medium text-sm text-gray-700">Place of Birth</label>
                <x-input id="place_of_birth" wire:model="state.place_of_birth" type="text" name="place_of_birth" required/>
            </span>
            <span class="w-3/12">
                <x-native-select label="Civil Status" wire:model="state.civil_status">
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="widowed">Widowed</option>
                    <option value="seperated">Seperated</option>
                    <option value="others">Others</option>
                </x-native-select>
            </span>
            <span class="w-3/12">
                <label for="sex" class="pb-1 block font-medium text-sm text-gray-700">Sex</label>
                {{-- <div class="grid w-full grid-cols-2 gap-1 rounded-xl bg-gray-200 p-1">
                    <div>
                        <input id="male"  wire:model="state.sex" type="radio" name="sex" value="male" class="peer hidden"/>
                        <label for="male" class="block cursor-pointer select-none rounded-xl px-2 py-1 text-center peer-checked:bg-cyan-500 peer-checked:font-bold peer-checked:text-white">Male</label>
                    </div>

                    <div>
                        <input id="female" wire:model="state.sex" type="radio" name="sex" value="female" class="peer hidden"/>
                        <label for="female" class="block cursor-pointer select-none rounded-xl px-2 py-1 text-center peer-checked:bg-pink-500 peer-checked:font-bold peer-checked:text-white">Female</label>
                    </div>
                </div> --}}
                <label class="relative inline-flex items-center cursor-pointer">
                    <input class="sr-only peer" value="" type="checkbox">
                      <div class="peer rounded-full outline-none duration-100 after:duration-500 w-28 h-14 bg-blue-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500  after:content-['No'] after:absolute after:outline-none after:rounded-full after:h-12 after:w-12 after:bg-white after:top-1 after:left-1 after:flex after:justify-center after:items-center  after:text-sky-800 after:font-bold peer-checked:after:translate-x-14 peer-checked:after:content-['Yes'] peer-checked:after:border-white">
                    </div>
                  </label>
            </span>
        </div>
        <div class="m-0 mb-4 p-0 flex space-x-3">
            <span class="w-3/12">
                <label for="citizenship" class="pb-1 block font-medium text-sm text-gray-700">Citizenship</label>
                <x-input id="citizenship" wire:model="state.citizenship" type="text" name="citizenship" required/>
            </span>
            <span class="pt-1 w-3/12">
                <label for="blood_type" class="block font-medium text-sm text-gray-700">Blood Type</label>
                <x-input id="blood_type" wire:model="state.blood_type" type="text" name="blood_type" required/>
            </span>
            <span class="w-3/12">
                <x-input type="number" wire:model="state.height" class="pr-10" label="Height" suffix="m" required/>
            </span>
            <span class="w-3/12">
                <x-input type="number" wire:model="state.weight" class="pr-10" label="Weight" suffix="kg" required/>
            </span>
        </div>

        <h5 class="font-bold text-xl text-gray-darkest">Government Information</h5>
        <div class="mt-2 mb-4 p-0 flex space-x-3">
            <span class="w-1/3">
                <label for="tin" class="pb-1 block font-medium text-sm text-gray-700">TIN</label>
                <x-input id="tin" wire:model="state.tin" type="number" name="tin" required/>
            </span>
            <span class="w-1/3">
                <label for="sss_num" class="pb-1 block font-medium text-sm text-gray-700">SSS No.</label>
                <x-input id="sss_num" wire:model="state.sss_num" type="number" name="sss_num" required/>
            </span>
            <span class="w-1/3">
                <label for="gsis_num" class="pb-1 block font-medium text-sm text-gray-700">GSIS No</label>
                <x-input id="gsis_num" wire:model="state.gsis_num" type="number" name="gsis_num" required/>
            </span>
        </div>
        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
            <span class="w-1/3">
                <label for="philhealth_num" class="pb-1 block font-medium text-sm text-gray-700">PHILHEALTH NO.</label>
                <x-input id="philhealth_num" wire:model="state.philhealth_num" type="number" name="philhealth_num" required/>
            </span>
            <span class="w-1/3">
                <label for="pagibig_num" class="pb-1 block font-medium text-sm text-gray-700">PAG-IBIG No.</label>
                <x-input id="pagibig_num" wire:model="state.pagibig_num" type="number" name="pagibig_num" required/>
            </span>
            <span class="w-1/3">
                <label for="personnel_id" class="pb-1 block font-medium text-sm text-gray-700">Personnel ID</label>
                <x-input id="personnel_id" wire:model="state.personnel_id" type="number" name="personnel_id" required/>
            </span>
        </div>
    </div>
</div>
