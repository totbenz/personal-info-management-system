
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New School') }}
        </h2>
    </x-slot>

    <div class="max-w-8xl mx-auto sm:px-6 sm:py-3 lg:px-8 lg:py-5">
        <ul class="inline-flex space-x-2">
            <li class="text-gray-600">
                <a href="https://craft.demo.quebixtechnology.com">Dashboard</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('schools.index') }}">School</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('schools.create') }}">Create</a>
                >
            </li>
        </ul>
    </div>

    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div x-data="{ formNav : 'school_information' }" >
            <div class="flex justify-center m-auto bg-white">
                <div class="relative w-full h-full">
                    {{-- FORM--}}
                    <div class="absolute top-0 left-0 flex items-center w-[69rem] ml-52">
                        <div class="me-7 bg-white min-h-screen w-full border-2 border-main border-solid shadow-md">
                            <section x-show="formNav === 'school_information'" id="school_information">
                                @livewire('form.school-information', ['storeMode' => true])
                            </section>
                        </div>
                    </div>
                    {{-- OVERLAPPING MENU --}}
                    <div class="absolute top-0 left-0 w-[13rem] bg-gray-100">
                        <div class="w-[13.5rem]"
                            :class="{'w-[13.3rem] z-10 bg-white border-t-2 border-main': formNav === 'school_information' }">
                            <a href="#school_information" @click="formNav = 'school_information'">
                                <div class="px-4 py-2 w-[13rem]"
                                     :class="{'border-l-2 border-b-2 border-main text-main': formNav === 'school_information' }">
                                    <span class="text-sm font-medium">School Information</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
