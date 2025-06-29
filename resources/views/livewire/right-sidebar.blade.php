<div class="w-80 bg-white shadow-lg border-l border-gray-200 h-screen overflow-y-auto scrollbar-hide ">
    <!-- Calendar Section -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">
                {{ $currentDate->format('F Y') }}
            </h3>
            <div class="flex space-x-1">
                <button wire:click="previousMonth" class="p-1 rounded-md hover:bg-gray-100 text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button wire:click="nextMonth" class="p-1 rounded-md hover:bg-gray-100 text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-gray-500 mb-2">
            <div class="py-1">Sun</div>
            <div class="py-1">Mon</div>
            <div class="py-1">Tue</div>
            <div class="py-1">Wed</div>
            <div class="py-1">Thu</div>
            <div class="py-1">Fri</div>
            <div class="py-1">Sat</div>
        </div>

        <div class="grid grid-cols-7 gap-1">
            @foreach($calendarDays as $day)
                <div class="relative group">
                    <button 
                        wire:click="selectDate('{{ $day['date'] }}')"
                        class="
                            h-8 w-8 rounded-md text-sm flex items-center justify-center transition-colors duration-200 relative
                            {{ $day['isCurrentMonth'] ? 'text-gray-900 hover:bg-blue-50' : 'text-gray-400' }}
                            {{ $day['isToday'] ? 'bg-blue-600 text-white font-semibold' : '' }}
                            {{ $day['isSelected'] && !$day['isToday'] ? 'bg-blue-100 text-blue-600 font-semibold' : '' }}
                        "
                    >
                        {{ $day['day'] }}
                        @if($day['hasEvents'])
                            <div class="absolute bottom-0 right-0 w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                        @endif
                    </button>
                    @if($day['isCurrentMonth'])
                    <button 
                        wire:click="addEventForDate('{{ $day['date'] }}')"
                        class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 text-white rounded-full text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center z-10"
                        title="Add event"
                    >
                        +
                    </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Events Section -->
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Events</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</span>
                <button 
                    wire:click="addEventForDate('{{ $selectedDate }}')"
                    class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                    title="Add event for this date"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Events for Selected Date -->
        @if($filteredEvents->count() > 0)
            <div class="space-y-3 mb-6">
                @foreach($filteredEvents as $event)
                    <div class="p-3 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2 bg-{{ $event->type_color }}-500"></div>
                            <div class="flex-1">
                                <h4 class="text-base font-medium text-gray-900">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $event->formatted_time }}</p>
                                @if($event->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $event->description }}</p>
                                @endif
                                @if($event->location)
                                    <p class="text-sm text-gray-500 mt-1">📍 {{ $event->location }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-base text-gray-500 mt-2">No events for this date</p>
            </div>
        @endif

        <!-- Upcoming Events -->
        <div class="border-t border-gray-200 pt-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Events</h4>
            
            @if($upcomingEvents->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingEvents as $event)
                        <div class="flex items-start space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-1.5 h-1.5 rounded-full mt-2 bg-{{ $event->type_color }}-500"></div>
                            <div class="flex-1">
                                <h5 class="text-sm font-medium text-gray-900">{{ $event->title }}</h5>
                                <p class="text-sm text-gray-500">
                                    {{ $event->start_date->format('M d') }} {{ $event->formatted_time ? 'at ' . $event->formatted_time : '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No upcoming events</p>
            @endif
        </div>

        <!-- Event Legend -->
        <div class="border-t border-gray-200 pt-6 mt-6 mb-10">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Event Types</h4>
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <span class="text-sm text-gray-600">Meetings</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <span class="text-sm text-gray-600">Training</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                    <span class="text-sm text-gray-600">Inspections</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                    <span class="text-sm text-gray-600">Ceremonies</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Creation Modal -->
    @if($showEventModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click="closeEventModal">
        <div class="bg-white rounded-lg shadow-xl w-96 max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">Add Event</h3>
                    <button wire:click="closeEventModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}</p>
            </div>

            <form wire:submit.prevent="createEvent" class="p-6 space-y-4">
                <!-- Event Title -->
                <div>
                    <label for="eventTitle" class="block text-sm font-medium text-gray-700 mb-1">Event Title *</label>
                    <input 
                        type="text" 
                        id="eventTitle"
                        wire:model="eventTitle" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter event title"
                        required
                    >
                    @error('eventTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Event Type -->
                <div>
                    <label for="eventType" class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                    <select 
                        id="eventType"
                        wire:model="eventType" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="meeting">Meeting</option>
                        <option value="training">Training</option>
                        <option value="inspection">Inspection</option>
                        <option value="ceremony">Ceremony</option>
                        <option value="deadline">Deadline</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- All Day Toggle -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="isAllDay"
                        wire:model="isAllDay" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="isAllDay" class="ml-2 block text-sm text-gray-700">All Day Event</label>
                </div>

                <!-- Time Fields (shown only if not all day) -->
                @if(!$isAllDay)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="eventStartTime" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                        <input 
                            type="time" 
                            id="eventStartTime"
                            wire:model="eventStartTime" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                    <div>
                        <label for="eventEndTime" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                        <input 
                            type="time" 
                            id="eventEndTime"
                            wire:model="eventEndTime" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                </div>
                @endif

                <!-- Location -->
                <div>
                    <label for="eventLocation" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input 
                        type="text" 
                        id="eventLocation"
                        wire:model="eventLocation" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter location (optional)"
                    >
                </div>

                <!-- Description -->
                <div>
                    <label for="eventDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea 
                        id="eventDescription"
                        wire:model="eventDescription" 
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter event description (optional)"
                    ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button 
                        type="button" 
                        wire:click="closeEventModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                    >
                        <span wire:loading.remove>Create Event</span>
                        <span wire:loading>Creating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Success Message -->
    <div x-data="{ show: false }" 
         x-on:event-created.window="show = true; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg"
         style="display: none;">
        <p class="text-base">Event created successfully! 🎉</p>
    </div>
</div>
