<div>
    <div x-data="{ formNav: @entangle('formNav')}">
        <div class="px-2 sm:px-6 lg:px-8 my-2.5 flex space-x-3 justify-end">
            @if (Auth::user()->role == "admin")
            <button type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <a wire:navigate href="{{ route('personnel-salary-changes.index', $personnelId) }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6.75V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V4.5A2.25 2.25 0 0 1 6.75 2.25h7.5L19.5 6.75z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12.75h7.5M8.25 16.5h7.5M8.25 9h3" />
                        </svg>
                        <p>View Salary Changes</p>
                    </span>
                </a>
            </button>
            <button type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <a wire:navigate href="{{ route('service-records.index', $personnelId) }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p>View Service Records</p>
                    </span>
                </a>
            </button>
            @endif
            <button type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <a href="{{ Auth::user()->role === 'teacher' ? route('teacher-profile.combined-export') : (Auth::user()->role === 'non_teaching' ? route('non-teaching-profile.combined-export') : (Auth::user()->role === 'school_head' ? route('pds.combined-export', $personnelId) : route('personnels.combined-export', $personnelId))) }}">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                        </svg>
                        <p>Export PDS</p>
                    </span>
                </a>
            </button>
            <!-- Downloads Dropdown -->
            <div x-data="{ downloadsOpen: false, downloading: false, downloadingItem: '' }" class="relative inline-block text-left">
                <button @click="downloadsOpen = !downloadsOpen" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5v-9m0 9 3-3m-3 3-3-3m9 6.75H6a2.25 2.25 0 0 1-2.25-2.25v-15A2.25 2.25 0 0 1 6 2.25h12A2.25 2.25 0 0 1 20.25 4.5v15a2.25 2.25 0 0 1-2.25 2.25z" />
                        </svg>
                        <p>Download</p>
                        <svg class="ml-2 -mr-1 h-4 w-4 transform transition-transform"
                            :class="{'rotate-180': downloadsOpen, 'rotate-0': !downloadsOpen}"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="downloadsOpen"
                    @click.away="downloadsOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-15 z-50 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                        <template x-for="item in [
                            { route: '{{ route('service-record.download', ['personnelId' => $personnelId]) }}', label: 'Service Record' }
                        ]" :key="item.label">
                            <a :href="item.route"
                                @click="downloadsOpen = false"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-3 h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5v-9m0 9 3-3m-3 3-3-3m9 6.75H6a2.25 2.25 0 0 1-2.25-2.25v-15A2.25 2.25 0 0 1 6 2.25h12A2.25 2.25 0 0 1 20.25 4.5v15a2.25 2.25 0 0 1-2.25 2.25z" />
                                </svg>
                                <span x-text="item.label"></span>
                            </a>
                        </template>
                    </div>
                </div>
                @if (Auth::user()->role == "admin")
                <button x-on:click.prevent="$openModal('myModal')"
                    class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-danger border border-red-600 rounded-lg hover:bg-red-500 hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <p>Delete</p>
                    </span>
                </button>
                @endif
            </div>
        </div>


        <div class="px-2 sm:px-6 lg:px-8 w-full">
            <div class="flex">
                <div class="w-2/12">
                    <div class="w-full" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'personal_information'}">
                        <a @click="formNav = 'personal_information'" href="#personal_information">
                            <div class="px-8 py-2 w-[14.4rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'personal_information'}">
                                <span class="text-sm font-medium">Personal Information</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-full" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'address'}">
                        <a @click="formNav = 'address'" href="#address">
                            <div class="px-8 py-2 w-[14.4rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'address'}">
                                <span class="text-sm font-medium">Address</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14.5rem]" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'family'}">
                        <a @click="formNav = 'family'" href="#family">
                            <div class="px-8 py-2 w-[14.4rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'family'}">
                                <span class="text-sm font-medium">Family</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14.5rem]" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'education'}">
                        <a @click="formNav = 'education'" href="#education">
                            <div class="px-8 py-2 w-[13.1rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'education'}">
                                <span class="text-sm font-medium">Education</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14.5rem]" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'civil_service_eligibility'}">
                        <a href="#civil_service_eligibility" @click.prevent="formNav = 'civil_service_eligibility'">
                            <div class="px-8 py-2 w-[13.1rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'civil_service_eligibility'}">
                                <span class="text-sm font-medium">Civil Service Eligibility</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14.5rem]" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'work_experience'}">
                        <a href="#work_experience" @click.prevent="formNav = 'work_experience'">
                            <div class="px-8 py-2 w-[13.1rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'work_experience'}">
                                <span class="text-sm font-medium">Work Experience</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14.5rem]" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'voluntary_work'}">
                        <a href="#voluntary_work" @click.prevent="formNav = 'voluntary_work'">
                            <div class="px-8 py-2 w-[13.1rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'voluntary_work'}">
                                <span class="text-sm font-medium">Voluntary Work</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14rem]" :class="{'w-[14.2rem] z-20 bg-white': formNav === 'training_certification'}">
                        <a href="#training_certification" @click.prevent="formNav = 'training_certification'">
                            <div class="px-8 py-2 w-[14rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'training_certification'}">
                                <span class="text-sm font-medium">Training & Certifications</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14rem]" :class="{'w-[14.2rem] z-20 bg-white': formNav === 'other_information'}">
                        <a href="#other_information" @click.prevent="formNav = 'other_information'">
                            <div class="px-8 py-2 w-[14rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'other_information'}">
                                <span class="text-sm font-medium">Other Information</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14rem]" :class="{'w-[14.2rem] z-20 bg-white': formNav === 'questionnaire'}">
                        <a href="#questionnaire" @click.prevent="formNav = 'questionnaire'">
                            <div class="px-8 py-2 w-[14rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'questionnaire'}">
                                <span class="text-sm font-medium">Questionnaire</span>
                            </div>
                        </a>
                    </div>

                    <div class="w-[14.5rem]" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'references'}">
                        <a href="#references" @click.prevent="formNav = 'references'">
                            <div class="px-8 py-2 w-[13.1rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'references'}">
                                <span class="text-sm font-medium">References</span>
                            </div>
                        </a>
                    </div>

                    {{-- <div class="w-[14.5rem]" :class="{'w-[14.5rem] z-10 bg-white': formNav === 'service_record'}">
                    <a href="#service_record" @click.prevent="formNav = 'service_record'">
                        <div class="px-8 py-2 w-[13.1rem]" :class="{'border-l-2 border-y-2 border-gray-300': formNav === 'service_record'}">
                            <span class="text-sm font-medium">Service Record</span>
                        </div>
                    </a>
                </div> --}}
                </div>

                <div class="w-10/12 bg-white h-[32rem] overflow-y-auto">
                    <section x-show="formNav === 'personal_information'" id="personal_information">
                        @livewire('form.personal-information-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'address'" id="address">
                        @livewire('form.address-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'family'" id="family">
                        @livewire('form.family-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'education'" id="education">
                        @livewire('form.education-entries-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'civil_service_eligibility'" id="civil_service_eligibility">
                        @livewire('form.civil-service-eligibility-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'work_experience'" id="work_experience">
                        @livewire('form.work-experience-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'voluntary_work'" id="voluntary_work">
                        @livewire('form.voluntary-work-form', ['id' => $personnelId, 'showMode' => true])
                    </section>

                    <section x-show="formNav === 'training_certification'" id="training_certification">
                        @livewire('form.training-certification-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'other_information'" id="other_information">
                        @livewire('form.other-information-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'questionnaire'" id="questionnaire">
                        @livewire('form.questionnaire-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'references'" id="references">
                        @livewire('form.references-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    <section x-show="formNav === 'award_received'" id="award_received">
                        @livewire('form.award-received-form', ['id' => $personnelId, 'showMode' => true])
                    </section>
                    {{-- <section x-show="formNav === 'service_record'" id="service_record">
                    @livewire('form.service-record-form', ['id' => $personnelId, 'showMode' => true])
                </section> --}}
                </div>
            </div>
        </div>

    </div>
