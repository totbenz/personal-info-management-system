
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit New School') }}
        </h2>
    </x-slot>

    <div class="max-w-8xl mx-auto sm:px-6 sm:py-3 lg:px-8 lg:py-5">
        <ul class="inline-flex space-x-2">
            <li class="text-gray-600">
                <a href="{{ route('admin.home') }}"">Dashboard</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('schools.index') }}">School</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('schools.show', ['school' => $school->id]) }}">{{ $school->school_name }}</a>
                >
            </li>
            <li class="text-gray-600">
                <a href="{{ route('schools.create') }}">Create</a>
                >
            </li>
        </ul>
    </div>
    <div class="flex space-x-10">
        <div class="mx-5 my-1 w-2/12">
            <div class="px-5 py-5 fixed z-10 border-2 border-main rounded-md">
                <ol x-cloak x-data="{ formNav : 1 }"  class="overflow-hidden space-y-8">
                    <li class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-main after:inline-block after:absolute after:-bottom-11 after:left-4 lg:after:left-5">
                        <a href="#school_information" class="flex items-center font-medium w-full  ">
                            <span class="w-8 h-8 bg-main border-2 border-transparent rounded-full flex justify-center items-center mr-3 text-sm text-white lg:w-10 lg:h-10">
                                <svg class="w-5 h-5 stroke-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 12L9.28722 16.2923C9.62045 16.6259 9.78706 16.7927 9.99421 16.7928C10.2014 16.7929 10.3681 16.6262 10.7016 16.2929L20 7" stroke="stroke-current" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="my-path"></path>
                                </svg>
                            </span>
                            <div class="block">
                                <h4 class="text-lg  text-main">Step 1</h4>
                                <span class="text-sm">School Information</span>
                            </div>
                        </a>
                    </li>
                    <li class="relative flex-1 after:content-['']  after:w-0.5 after:h-full  after:bg-gray-200 after:inline-block after:absolute after:-bottom-12 after:left-4 lg:after:left-5">
                        <a href="#school_resources" class="flex items-center font-medium w-full  ">
                            <span class="w-8 h-8 bg-indigo-50  border-2 border-main rounded-full flex justify-center items-center mr-3 text-sm text-main lg:w-10 lg:h-10">2</span>
                            <div class="block">
                                <h4 class="text-lg  text-main">Step 2</h4>
                                <span class="text-sm">School Resources</span>
                            </div>
                        </a>
                    </li>
                    <li class="relative flex-1 ">
                        <a href="#appointments_fundings" class="flex items-center font-medium w-full hover:text-main">
                            <span class="w-8 h-8 bg-gray-50 border-2 border-gray-200 rounded-full flex justify-center items-center mr-3 text-sm  lg:w-10 lg:h-10 duration-300">3</span>
                            <div class="block">
                                <h4 class="text-lg">Step 3</h4>
                                <span class="text-sm">Appointments Fundings</span>
                            </div>
                        </a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="max-w-9/12 lg:pe-8">
            <div x-cloak x-data="{ formNav : 1 }" class="flex space-x-8 justify-end">
                <div class="w-full">
                    <form action="{{ route('schools.update', $school->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                        <section id="school_information" class="bg-white overflow-auto shadow-md sm:rounded-lg">
                            <div class="mx-auto pt-8 pb-10 px-8">
                                <h4 class="font-bold text-2xl text-gray-darkest">School Information</h4>

                                <div class="mt-5">
                                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                                        <span class="w-1/4">
                                            <x-label for="school_id" value="{{ __('School ID') }}"/>
                                            <x-input id="school_id" type="number" name="school_id" :value="$school->school_id ?? ''" required/>
                                        </span>
                                        <span class="w-1/4">
                                            <x-label for="region" value="{{ __('Region') }}"/>
                                            <x-input id="region" type="text" name="region" :value="$school->region ?? ''" required/>
                                        </span>
                                        <span class="w-1/4">
                                            <x-label for="division" value="{{ __('Division') }}" />
                                            <x-input id="division" type="text" name="division" :value="$school->division ?? ''" required/>
                                        </span>
                                        <span class="w-1/4">
                                            <x-label for="district" value="{{ __('District') }}" />
                                            <x-input id="district" type="text" name="district" :value="$school->district ?? ''" required/>
                                        </span>
                                    </div>
                                    <div class="mb-4 flex space-x-3 justify-between">
                                        <span class="w-full">
                                            <x-label for="school_name" value="{{ __('School Name') }}" />
                                            <x-input id="school_name" type="text" name="school_name" :value="$school->school_name ?? ''" required/>
                                        </span>
                                    </div>
                                    <div class="mb-4 flex space-x-4 justify-between">
                                        <span class="w-full">
                                            <x-label for="address" value="{{ __('Address') }}" />
                                            <x-input id="address" type="text" name="address" :value="$school->address ?? ''" required/>
                                        </span>
                                    </div>
                                    <div class="mb-4 flex space-x-3 justify-between">
                                        <span class="w-2/4">
                                            <x-label for="email" value="{{ __('Email') }}" />
                                            <x-input id="email" type="email" name="email" :value="$school->email ?? ''" required/>
                                        </span>
                                        <span class="w-2/4">
                                            <x-label for="phone" value="{{ __('Phone') }}" />
                                            <x-input id="phone" type="text" name="phone" :value="$school->phone ?? ''" required/>
                                        </span>
                                    </div>
                                    <div class="flex space-x-4">
                                        <div class="w-full">
                                            <x-label for="curricular_classification" value="{{ __('Curricular Classifications') }}" />
                                            {{-- <p>{{ is_string($school->curricular_classification) ? 'uwu' : 'poo'}}</p>
                                            <p>{{ json_encode($school->curricular_classification) }}</p> --}}
                                            <x-native-select wire:model="curricular_classification" class="form-control" label="Curricular Classifications">
                                                <option value="grade 1-6">Grade 1-6</option>
                                                <option value="grade 7-10">Grade 7-10</option>
                                                <option value="grade 11-12">Grade 11-12</option>
                                            </x-native-select>
                                            {{-- <x-grade-level-multi-select :curricular_classification="{{ json_encode($school->curricular_classification) }}"/> --}}
                                            {{-- <x-grade-level-multi-select :curricular_classification="$school->curricular_classification" /> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="school_resources" class="mt-8 bg-white overflow-auto shadow-md sm:rounded-lg">
                            <div class="mx-auto pt-8 pb-10 px-8">
                                <h4 class="font-bold text-2xl text-gray-darkest">School Resources</h4>
                                <div class="m-0 p-0">
                                    <livewire:dynamic-form.funded-item-form :id="$school->id" />
                                        {{-- @livewire('dynamic-form.funded-item-form', ['id' => 'Hello, World!']) --}}
                                </div>
                            </div>
                        </section>
                        <section id="appointments_fundings" class="mt-8 bg-white overflow-auto shadow-md sm:rounded-lg">
                            <div class="mx-auto pt-8 pb-10 px-8">
                                <h4 class="font-bold text-2xl text-gray-darkest">Appointment Fundings</h4>
                                <div class="m-0 p-0">
                                    {{-- {{ $school->appointments_fundings }} --}}
                                    <livewire:dynamic-form.appointments-funding-form :id="$school->id"/>
                                </div>
                            </div>
                        </section>


                    <div class="py-5 flex flex-row space-x-8 justify-end text-end">
                            <x-button class="bg-danger hover:bg-red-500">
                                {{ __('Cancel') }}
                            </x-button>
                            <x-button type="submit" class="hover:shadow-[0.5rem_0.5rem_#FA0302,-0.5rem_-0.5rem_#FCC008] transition">
                                {{ __('Submit') }}
                            </x-button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
