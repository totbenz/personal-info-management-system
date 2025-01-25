<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
    <div class="flex justify-between">
        <h4 class="font-bold text-2xl text-gray-darkest">Civil Service Eligibility</h4>
        <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
            <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>

                <p>Edit</p>
            </span>
        </button>
    </div>
    <div class="mt-5">
        {{-- @if (count($personnel->civilServiceEligibilities)) --}}
            {{-- @foreach ($personnel->civilServiceEligibilities as $civil_service_eligibility)
                <div class="p-5 border border-gray-400">
                    <div class="flex">
                        <div class="w-3/12">
                            <p class="text-base font-medium leading-none text-gray-700 mr-2">
                            {{ $civil_service_eligibility->title }}
                            </p>
                        </div>
                        <div class="w-1/12 text-xs">
                            <p class="text-sm leading-none text-gray-600 ml-2">
                                {{ $civil_service_eligibility->rating }}
                            </p>
                        </div>
                        <div class="ps-3 w-2/12 text-xs">
                            <p class="text-sm leading-none text-gray-600 ml-2">
                                {{ date('m-d-Y', strtotime($civil_service_eligibility->date_of_exam)) }}
                            </p>
                        </div>
                        <div class="ps-3 w-2/12 text-xs">
                            <p class="text-sm leading-none text-gray-600 ml-2">
                                {{ $civil_service_eligibility->place_of_exam }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach --}}
        {{-- @endif --}}
        <div class="mt-3">
            <div class="w-full flex space-x-2 h-10 border border-gray-100 bg-gray-lightest items-center">
                <h6 class="ps-5 w-3/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Title</span>
                </h6>
                <h6 class="w-1/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Rating</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Date of Examination</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Place of Examination</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-xs text-center text-gray-dark font-semibold uppercase">License Number</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-xs text-center text-gray-dark font-semibold uppercase">License Date of Validity</span>
                </h6>
            </div>
            <div class="mt-2">
                @if (count($personnel->civilServiceEligibilities))
                    @foreach ($personnel->civilServiceEligibilities as $civil_service_eligibility)
                        <div class="mb-2 px-3 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
                            <div class="w-3/12">
                                <p class="text-base font-medium leading-none text-gray-700 mr-2">
                                {{ $civil_service_eligibility->title }}
                                </p>
                            </div>
                            <div class="w-1/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $civil_service_eligibility->rating }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ date('m-d-Y', strtotime($civil_service_eligibility->date_of_exam)) }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $civil_service_eligibility->place_of_exam }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ $civil_service_eligibility->license_num }}
                                </p>
                            </div>
                            <div class="ps-3 w-2/12 text-xs">
                                <p class="text-sm leading-none text-gray-600 ml-2">
                                    {{ date('m-d-Y', strtotime($civil_service_eligibility->license_date_of_validity)) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="w-full">
                        <p class="mt-3 w-full py-2 font-medium text-xs text-center bg-gray-200">No Civil Service Eligibility Found</p>
                    </div>
                @endif
                {{-- <template x-for="(civil_service, index) in civil_services" :key="index">
                    <div class="mb-2 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
                        <div class="w-3/12 text-xs">
                            <x-input x-model="civil_service.title" type="text" name="civil_service.title[]" class="text-xs" required/>
                        </div>
                        <div class="w-1/12 text-xs">
                            <x-input x-model="civil_service.rating" type="text" name="civil_service.rating[]" class="text-xs" required/>
                        </div>
                        <div class="w-2/12 text-xs">
                            <x-input x-model="civil_service.date" type="date" name="civil_service.date[]" class="text-xs" required/>
                        </div>
                        <div class="w-2/12 text-xs">
                            <x-input x-model="civil_service.place" type="text" name="civil_service.place[]" class="text-xs" required/>
                        </div>
                        <div class="w-1/12 text-xs">
                            <x-input x-model="civil_service.license_number" type="text" name="civil_service.license_number[]" class="text-xs" required/>
                        </div>
                        <div class="w-2/12 text-xs">
                            <x-input x-model="civil_service.license_date_validity" type="date" name="civil_service.license_date_validity[]" class="text-xs" required/>
                        </div>
                    </div>
                </template> --}}
            </div>
        </div>
    </div>
    @else
        <div class="flex justify-between">
            <h4 class="font-bold text-2xl text-gray-darkest">Edit Civil Service Eligibility</h4>

            <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                    <p>Back</p>
                </span>
            </button>
        </div>
        @livewire('form.update-civil-service-eligibility-form', ['id' => $personnel->id])
    @endif
</div>
