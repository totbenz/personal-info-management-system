
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $school->school_name }}'s Profile
                </h2>
            </div>
            <div class="flex space-x-3 justify-end">

                {{-- <a href="{{ route('school.export', $school->id) }}" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                        </svg>
                        <p>Generate SF7</p>
                    </span>
                </a> --}}

                @if (Auth::user()->role === "admin")
                    <form action="{{ route('schools.destroy', $school->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-24 rounded-md px-5 py-2 bg-danger text-white tracking-wider hover:hover:bg-red-700 hover:scale-105 duration-100">
                            Delete
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </x-slot>

    <div>
        <div class="bg-slate-400">
            <div x-data="{ formNav: 'school_information'}">
                <div class="flex justify-center m-auto bg-white">
                    <div class="relative w-full h-full">
                        {{-- FORM--}}
                        <div class="pb-10 absolute top-0 left-0 flex items-center w-[73rem] ml-52">
                            <div class="me-7 bg-white w-full border-2 border-gray-300 border-solid shadow-lg">
                                <section x-show="formNav === 'school_information'" id="school_information">
                                    @livewire('form.school-information', ['showMode' => true, 'id' => $school->id])
                                </section>
                                <section x-show="formNav === 'school_personnels'" id="school_personnels">
                                    @livewire('datatable.personnels-datatable', ['schoolId' => $school->id])
                                </section>
                            </div>
                        </div>
                        {{-- OVERLAPPING MENU --}}
                        <div class="absolute top-0 left-0 mt-6 w-[13rem] bg-gray-100">
                            <div class="w-[13.5rem]"
                                :class="{'w-[13.2rem] z-10 bg-white': formNav === 'school_information' }">
                                <a href="#school_information" @click="formNav = 'school_information'">
                                    <div class="px-4 py-2 w-[13.1rem]"
                                         :class="{'border-l-2 border-y-2 border-gray-300 text-main': formNav === 'school_information' }">
                                        <span class="text-sm font-medium">School Information</span>
                                    </div>
                                </a>
                            </div>
                            <div class="w-[13.5rem]"
                                :class="{'w-[13.2rem] z-10 bg-white': formNav === 'school_personnels' }">
                                <a href="#school_personnels" @click="formNav = 'school_personnels'">
                                    <div class="px-4 py-2 w-[13.1rem]"
                                         :class="{'border-l-2 border-y-2 border-gray-300 text-main': formNav === 'school_personnels' }">
                                        <span class="text-sm font-medium">School Personnels</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
