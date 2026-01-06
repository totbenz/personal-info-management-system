<div wire:key="education-entries-form-{{ $personnel?->id }}" class="px-8 py-6">
@if (!$updateMode)
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h4 class="font-bold text-2xl text-gray-800 mb-1">Education Details</h4>
            <p class="text-sm text-gray-500">View your educational background and qualifications</p>
        </div>
        <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 border-0 rounded-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
            <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L8.863 9.83A3.75 3.75 0 0 0 7.5 6.75v-.75m0 0a3.75 3.75 0 0 1 7.5 0v.75m-7.5 0H18A2.25 2.25 0 0 1 20.25 9v.75m-8.5 6.75h.008v.008h-.008v-.008Z" />
                </svg>
                Edit Information
            </span>
        </button>
    </div>

    @if (session()->has('flash.banner'))
        <div class="mb-4 px-4 py-2 rounded text-white {{ session('flash.bannerStyle') === 'success' ? 'bg-green-600' : 'bg-red-600' }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex justify-between items-center">
                <span>{{ session('flash.banner') }}</span>
                <button @click="show = false" class="text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @foreach($this->typeLabels as $type => $title)
        @php
            $displayEntries = array_filter(($entries[$type] ?? []), function ($education) {
                return !empty($education['school_name']);
            });
        @endphp

        <div class="mt-8">
            <div class="mb-4">
                <h5 class="font-semibold text-lg text-gray-800 flex items-center">
                    <span class="w-1 h-6 bg-blue-600 rounded-full mr-3"></span>
                    {{ $title }}
                </h5>
            </div>

            @if(!empty($displayEntries))

            @foreach($displayEntries as $index => $education)
                <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 mb-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="group">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">School Name</span>
                            <p class="mt-2 text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors">{{ $education['school_name'] ?? '—' }}</p>
                            @if(!empty($education['school_address']))
                                <p class="mt-1 text-xs text-gray-500">{{ $education['school_address'] }}, {{ $education['school_city'] ?? '' }}{{ $education['school_city'] ? ', ' : '' }}{{ $education['school_province'] ?? '' }}</p>
                            @endif
                            @if(!empty($education['school_country']))
                                <p class="mt-1 text-xs text-gray-500">{{ $education['school_country'] }}</p>
                            @endif
                        </div>
                        <div class="group">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Degree/Course</span>
                            <p class="mt-2 text-sm text-gray-700 group-hover:text-blue-600 transition-colors">{{ $education['degree_course'] ?? '—' }}</p>
                            @if(!empty($education['academic_status']))
                                <p class="mt-1 text-xs text-gray-500">Status: {{ $education['academic_status'] }}</p>
                            @endif
                        </div>
                        <div class="group">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</span>
                            <p class="mt-2 text-sm text-gray-700 group-hover:text-blue-600 transition-colors">{{ $education['period_from'] ?? '—' }} - {{ $education['period_to'] ?? '—' }}</p>
                            @if(!empty($education['year_graduated']))
                                <p class="mt-1 text-xs text-gray-500">Graduated: {{ $education['year_graduated'] }}</p>
                            @endif
                            @if(!empty($education['enrollment_date']))
                                <p class="mt-1 text-xs text-gray-500">Enrolled: {{ $education['enrollment_date'] }}</p>
                            @endif
                            @if(!empty($education['completion_date']))
                                <p class="mt-1 text-xs text-gray-500">Completed: {{ $education['completion_date'] }}</p>
                            @endif
                        </div>

                        @if(in_array($type, ['college', 'graduate_studies'], true))
                            <div class="group">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Major</span>
                                <p class="mt-2 text-sm text-gray-700 group-hover:text-blue-600 transition-colors">{{ $education['major'] ?? '—' }}</p>
                            </div>
                            <div class="group">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Minor</span>
                                <p class="mt-2 text-sm text-gray-700 group-hover:text-blue-600 transition-colors">{{ $education['minor'] ?? '—' }}</p>
                            </div>
                        @endif

                        @if(!empty($education['gpa']))
                            <div class="group">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">GPA</span>
                                <p class="mt-2 text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors">{{ $education['gpa'] }}{{ $education['gpa_scale'] ? ' / ' . $education['gpa_scale'] : '' }}</p>
                                @if(!empty($education['class_rank']))
                                    <p class="mt-1 text-xs text-gray-500">Rank: {{ $education['class_rank'] }}</p>
                                @endif
                            </div>
                        @endif

                        @if(!empty($education['thesis_title']))
                            <div class="md:col-span-3">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Thesis/Dissertation</span>
                                <p class="mt-2 text-sm text-gray-700 font-medium">{{ $education['thesis_title'] }}</p>
                                @if(!empty($education['thesis_advisor']))
                                    <p class="mt-1 text-xs text-gray-500">Advisor: {{ $education['thesis_advisor'] }}</p>
                                @endif
                            </div>
                        @endif

                        @if(!empty($education['license_number']))
                            <div class="md:col-span-3 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                <span class="text-xs font-semibold text-green-700 uppercase tracking-wider">License Information</span>
                                <p class="mt-2 text-sm text-green-900 font-medium">License #: {{ $education['license_number'] }}</p>
                                @if(!empty($education['board_exam_rating']))
                                    <p class="mt-1 text-sm text-green-700">Board Exam Rating: {{ $education['board_exam_rating'] }}</p>
                                @endif
                                @if(!empty($education['license_date']))
                                    <p class="mt-1 text-xs text-green-700">Issued: {{ $education['license_date'] }} | Expires: {{ $education['license_expiry'] ?? 'N/A' }}</p>
                                @endif
                            </div>
                        @endif

                        @if(!empty($education['highest_level_units']))
                            <div class="md:col-span-3 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Highest Level/Units Earned</span>
                                <p class="mt-2 text-sm text-blue-900 font-medium">{{ $education['highest_level_units'] }}</p>
                            </div>
                        @endif

                        @if(!empty($education['scholarship_honors']) || !empty($education['achievements']) || !empty($education['awards']))
                            <div class="md:col-span-3 bg-amber-50 border-l-4 border-amber-400 p-4 rounded">
                                <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Achievements & Honors</span>
                                @if(!empty($education['scholarship_honors']))
                                    <p class="mt-2 text-sm text-amber-900 font-medium">{{ $education['scholarship_honors'] }}</p>
                                @endif
                                @if(!empty($education['achievements']))
                                    <p class="mt-2 text-sm text-amber-900">{{ $education['achievements'] }}</p>
                                @endif
                                @if(!empty($education['awards']))
                                    <p class="mt-2 text-sm text-amber-900">{{ $education['awards'] }}</p>
                                @endif
                            </div>
                        @endif

                        @if(!empty($education['extracurricular_activities']))
                            <div class="md:col-span-3 bg-purple-50 border-l-4 border-purple-400 p-4 rounded">
                                <span class="text-xs font-semibold text-purple-700 uppercase tracking-wider">Extracurricular Activities</span>
                                <p class="mt-2 text-sm text-purple-900">{{ $education['extracurricular_activities'] }}</p>
                            </div>
                        @endif

                        @if(!empty($education['leadership_roles']))
                            <div class="md:col-span-3 bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded">
                                <span class="text-xs font-semibold text-indigo-700 uppercase tracking-wider">Leadership Roles</span>
                                <p class="mt-2 text-sm text-indigo-900">{{ $education['leadership_roles'] }}</p>
                            </div>
                        @endif

                        @if(!empty($education['remarks']))
                            <div class="md:col-span-3 bg-gray-50 border-l-4 border-gray-400 p-4 rounded">
                                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Remarks</span>
                                <p class="mt-2 text-sm text-gray-900">{{ $education['remarks'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                <p class="text-sm text-gray-500 italic">No entry</p>
            </div>
        @endif

        @if(!$loop->last)
            <hr class="my-8 border-gray-200">
        @endif
    @endforeach
</section>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h4 class="font-bold text-2xl text-gray-800 mb-1">Edit Education Details</h4>
            <p class="text-sm text-gray-500">Update your educational background and qualifications</p>
        </div>
        <div class="flex space-x-3 relative z-10">
            <button wire:click.prevent="cancel" type="button" class="relative z-10 inline-flex items-center px-6 py-3 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transform hover:scale-105 transition-all duration-200 shadow-sm">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </span>
            </button>
            <button wire:click.prevent="save" type="button" class="relative z-10 inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 border-0 rounded-xl hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L8.863 9.83A3.75 3.75 0 0 0 7.5 6.75v-.75m0 0a3.75 3.75 0 0 1 7.5 0v.75m-7.5 0H18A2.25 2.25 0 0 1 20.25 9v.75m-8.5 6.75h.008v.008h-.008v-.008Z" />
                    </svg>
                    Save Changes
                </span>
            </button>
        </div>
    </div>

    @foreach($this->typeLabels as $type => $title)
        <div class="mt-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border border-blue-100">
                <div class="flex justify-between items-center">
                    <h5 class="font-semibold text-lg text-gray-800 flex items-center">
                        <span class="w-1 h-6 bg-blue-600 rounded-full mr-3"></span>
                        {{ $title }}
                    </h5>
                    <button wire:click.prevent="addEntry('{{ $type }}')" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-100 border-0 rounded-lg hover:bg-blue-200 transform hover:scale-105 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1.5 h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Entry
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600">This section is optional. Fill in if applicable.</p>
            </div>

            <div class="space-y-4">
                @if(!empty(($entries[$type] ?? [])))
                    @foreach(($entries[$type] ?? []) as $index => $education)
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-6 shadow-sm hover:border-blue-300 transition-colors duration-200">
                        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                            <span class="text-sm font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded-full">Entry #{{ $index + 1 }} (Leave blank if not applicable)</span>
                            <button wire:click.prevent="removeEntry('{{ $type }}', {{ $index }})"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors duration-200"
                                title="Remove this entry">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1 h-3 w-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Remove
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block font-semibold text-sm text-gray-700 mb-2">School Name</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.school_name"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Enter school name">
                                @error("entries.$type.$index.school_name")
                                    <span class="text-red-600 text-xs mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Degree/Course</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.degree_course"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Enter degree or course">
                                @error("entries.$type.$index.degree_course")
                                    <span class="text-red-600 text-xs mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <!-- School Location Information -->
                            <div class="md:col-span-2">
                                <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    School Location
                                </h6>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-medium text-sm text-gray-700 mb-2">School Address</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.school_address"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Enter complete school address">
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">City</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.school_city"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="City">
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">Province/State</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.school_province"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Province or State">
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">Country</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.school_country"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Country">
                            </div>

                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Period From @if(in_array($type, ['elementary','secondary'], true))<span class="text-red-500">*</span>@endif</label>
                                <input type="number" wire:model="entries.{{ $type }}.{{ $index }}.period_from"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="e.g., 2010" min="1900" max="2100">
                                @error("entries.$type.$index.period_from")
                                    <span class="text-red-600 text-xs mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Period To @if(in_array($type, ['elementary','secondary'], true))<span class="text-red-500">*</span>@endif</label>
                                <input type="number" wire:model="entries.{{ $type }}.{{ $index }}.period_to"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="e.g., 2016" min="1900" max="2100">
                                @error("entries.$type.$index.period_to")
                                    <span class="text-red-600 text-xs mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Year Graduated @if(in_array($type, ['elementary','secondary'], true))<span class="text-red-500">*</span>@endif</label>
                                <input type="number" wire:model="entries.{{ $type }}.{{ $index }}.year_graduated"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="e.g., 2016" min="1900" max="2100">
                                @error("entries.$type.$index.year_graduated")
                                    <span class="text-red-600 text-xs mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Highest Level/Units</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.highest_level_units"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="If not graduated">
                                @error("entries.$type.$index.highest_level_units")
                                    <span class="text-red-600 text-xs mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Scholarship/Academic Honors</label>
                                <input type="text" wire:model="entries.{{ $type }}.{{ $index }}.scholarship_honors"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="e.g., Cum Laude, Dean's List">
                                @error("entries.$type.$index.scholarship_honors")
                                    <span class="text-red-600 text-xs mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                        <p class="text-sm text-gray-500 italic">No entry</p>
                        <p class="text-xs text-gray-400 mt-2">Click "Add Entry" above to add an education entry</p>
                    </div>
                @endif
            </div>
        </div>

        @if(!$loop->last)
            <hr class="my-8 border-gray-200">
        @endif
    @endforeach
</div>
@endif
</div>
