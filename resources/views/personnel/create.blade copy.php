
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Personnel') }}
        </h2>
    </x-slot>

    <div class="max-w-8xl mx-auto sm:px-6 sm:py-3 lg:px-8 lg:py-5">
        {{-- BREADCRUMB LINKS --}}
        <ul class="inline-flex space-x-2">
            <li class="text-gray-600">
                <a href="{{ route('admin.home') }}">Dashboard</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('personnels.index') }}">Personnels</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('personnel.create') }}">Create</a>
                >
            </li>
        </ul>

        <div x-data="{ formNav : 'personal_information' }" >
            <div class="mt-5 flex justify-center m-auto bg-white">
                <div class="relative w-full h-full">
                    {{-- FORM--}}
                    {{-- <div class="absolute top-0 left-0 flex items-center w-[69rem] ml-52">
                        <div class="me-7 bg-white min-h-screen w-full border-2 border-main border-solid shadow-md">
                                <section x-show="formNav === 'personal_information'" id="personal_information">
                                    @livewire('form.personal_information')
                                </section>
                                <section x-show="formNav === 'address'" id="address">
                                    @livewire('form.address-form')
                                </section>
                                <section x-show="formNav === 'family'" id="family">
                                    @livewire('form.family-form')
                                </section>
                                <section x-show="formNav === 'education'" id="education">
                                    @include('personnel_profile.form.education')
                                </section>
                                <section x-show="formNav === 'civil_service_eligibility'" id="civil_service_eligibility">
                                    @include('personnel_profile.form.civil_service_eligibility')
                                </section>
                                <section x-show="formNav === 'work_experience'" id="work_experience">
                                    @include('personnel_profile.form.work_experience')
                                </section>
                                <section x-show="formNav === 'voluntary_work'" id="voluntary_work">
                                    @include('personnel_profile.form.voluntary_work')
                                </section>
                                <section x-show="formNav === 'training_certification'" id="training_certification">
                                    @include('personnel_profile.form.training_certification')
                                </section>
                                <section x-show="formNav === 'references'" id="references">
                                    @include('personnel_profile.form.reference')
                                </section>
                                <section x-show="formNav === 'assignment_details'" id="assignment_details">
                                    @include('personnel_profile.form.assignment_detail')
                                </section>
                        </div>
                    </div> --}}
                    {{-- OVERLAPPING MENU --}}
                    <div class="absolute top-0 left-0 mt-6 w-[13rem] bg-gray-100">
                        <div class="w-[13.5rem]"
                            :class="{'w-[13.2rem] z-10 bg-white': formNav === 'personal_information' }">
                            <a href="#personal_information" @click="formNav = 'personal_information'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'personal_information' }">
                                    <span class="text-sm font-medium">Personal Information</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'address' }">
                            <a href="#address" @click="formNav = 'address'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'address' }">
                                     <span class="text-sm font-medium">Address</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'family' }">
                            <a href="#family" @click="formNav = 'family'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'family' }">
                                     <span class="text-sm font-medium">Family</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'education' }">
                            <a href="#education" @click="formNav = 'education'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'education' }">
                                     <span class="text-sm font-medium">Education</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'civil_service_eligibility' }">
                            <a href="#civil_service_eligibility" @click="formNav = 'civil_service_eligibility'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'civil_service_eligibility' }">
                                     <span class="text-sm font-medium">Civil Service Eligibility</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'work_experience' }">
                            <a href="#work_experience" @click="formNav = 'work_experience'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'work_experience' }">
                                     <span class="text-sm font-medium">Work Experience</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'voluntary_work' }">
                            <a href="#voluntary_work" @click="formNav = 'voluntary_work'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'voluntary_work' }">
                                     <span class="text-sm font-medium">Voluntary Work</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'training_certification' }">
                            <a href="#training_certification" @click="formNav = 'training_certification'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'training_certification' }">
                                     <span class="text-sm font-medium">Training & Certification</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'references' }">
                            <a href="#references" @click="formNav = 'references'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'references' }">
                                     <span class="text-sm font-medium">References</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-[13.5rem]"
                             :class="{'w-[13.5rem] z-10 bg-white': formNav === 'assignment_details' }">
                            <a href="#assignment_details" @click="formNav = 'assignment_details'">
                                <div class="px-4 py-2 w-[13.1rem] "
                                     :class="{'border-l-2 border-y-2 border-main': formNav === 'assignment_details' }">
                                     <span class="text-sm font-medium">Assignment Details</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</x-app-layout>
