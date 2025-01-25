<div class="py-3" x-data='appointmentFields({{ isset($school) && $school->appointments_fundings()->count() > 0 ? 1 : 0 }})'>

    <div class="mt-3">
        <div class="ps-5 w-full flex space-x-3 h-10 border border-gray-100 bg-gray-lightest items-center">
            <h6 class="w-4/12">
                <span class="text-xs text-gray-dark font-semibold uppercase">Title</span>
            </h6>
            <h6 class="w-2/12">
                <span class="text-xs text-gray-dark font-semibold uppercase">Appointment</span>
            </h6>
            <h6 class="w-2/12">
                <span class="text-xs text-gray-dark font-semibold uppercase">Funding</span>
            </h6>
            <h6 class="w-1/12 text-center">
                <span class="text-xs text-gray-dark font-semibold uppercase">Teaching</span>
            </h6>
            <h6 class="w-1/12 text-center">
                <span class="text-xs text-gray-dark font-semibold uppercase leading-none">Non-Teaching</span>
            </h6>
            <h6 class="w-1/12 text-center">
                <span class="text-xs text-gray-dark font-semibold uppercase"></span>
            </h6>
        </div>
        <div class="mt-2">
            @if(isset($school) && $school->appointments_fundings()->count() > 0)
                <div x-data="appointments_fundings = {{ $school->appointments_fundings }}">
                    <template x-for="(appointments_funding, index) in appointments_fundings" :key="index">
                        <div class="mb-2 w-full flex items-center space-x-4 h-14 border border-gray-200 rounded focus:outline-none"
                            x-cloak
                            x-transition:leave="transition ease-in-out duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95">
                            <x-input x-model="appointments_funding.id" type="hidden" name="appointments_funding.id[]"/>
                            <div class="w-4/12 text-xs">
                                <x-input x-model="appointments_funding.title" type="text" name="appointments_funding.title[]" class="text-xs self-center" required/>
                            </div>
                            <div class="w-2/12 text-xs">
                                <select x-model="appointments_funding.appointment" name="appointments_funding.appointment[]" class="appearance-none block w-full bg-gray-50 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                    <option value="teaching"">Contractual</option>
                                    <option value="non-teaching"">Substitute</option>
                                    <option value="teaching"">Volunteer</option>
                                    <option value="non-teaching"">Others</option>
                                </select>
                            </div>
                            <div class="w-2/12 text-xs">
                                <select x-model="appointments_funding.fund_source" name="appointments_funding.fund_source[]" class="py-3 px-4 appearance-none block w-full bg-gray-50 text-gray-700 text-sm border border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                    <option value="SEF">SEF</option>
                                    <option value="PTA">PTA</option>
                                    <option value="NGO">NGO</option>
                                    <option value="etc.">etc.</option>
                                </select>
                            </div>
                            <div class="w-1/12 text-xs">
                                <x-input x-model="appointments_funding.incumbent_teaching" type="number" name="appointments_funding.incumbent_teaching[]" class="text-xs" placeholder="0" required/>
                            </div>
                            <div class="w-1/12 text-xs">
                                <x-input x-model="appointments_funding.incumbent_non_teaching" type="number" name="appointments_funding.incumbent_non_teaching[]" class="text-xs" placeholder="0" required/>
                            </div>
                            <div class="ps-2 w-1/12 text-xs">
                                <button wire:click.prevent="confirmAppointmentsFundingDeletion(appointments_funding.id)" wire:loading.attr="disabled" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            @endif
            <div>
                <template x-for="(new_appointment, index) in new_appointments" :key="index">
                    <div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none"
                         x-cloak
                         x-transition:enter="transition ease-in-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in-out duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95">
                        <div class="w-4/12 px-3 text-xs text-center">
                            <x-input x-model="new_appointment.title" type="text" name="new_appointment.title[]" class="text-xs self-center" required/>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <select x-model="new_appointment.appointment" name="new_appointment.appointment[]" class="appearance-none block w-full bg-gray-50 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                <option value="teaching"">Contractual</option>
                                <option value="non-teaching"">Substitute</option>
                                <option value="teaching"">Volunteer</option>
                                <option value="non-teaching"">Others</option>
                            </select>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <select x-model="new_appointment.fund_source" name="new_appointment.fund_source[]" class="py-3 px-4 appearance-none block w-full bg-gray-50 text-gray-700 text-sm border border-gray-200 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                <option value="SEF">SEF</option>
                                <option value="PTA">PTA</option>
                                <option value="NGO">NGO</option>
                                <option value="etc.">etc.</option>
                            </select>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <x-input x-model="new_appointment.incumbent_teaching" type="number" name="new_appointment.incumbent_teaching[]" class="text-xs" placeholder="0" required/>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <x-input x-model="new_appointment.incumbent_non_teaching" type="number" name="new_appointment.incumbent_non_teaching[]" class="text-xs" placeholder="0" required/>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <button x-show="new_appointments.length > 1" @click="removeField()" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="mt-3 flex space-x-3 items-center">
        <div class="w-full">
            <button @click.prevent="addNewField()" class="py-2 w-full text-base bg-main text-white tracking-wide font-medium rounded hover:bg-main_hover hover:text-white duration-300 focus:outline-none">New Appointments Funding</button>
        </div>
    </div>

    @include('admin.school.confirmation-modal.delete_appointments_funding')

</div>
