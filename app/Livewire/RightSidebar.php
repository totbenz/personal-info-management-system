<?php

namespace App\Livewire;

use App\Models\Event;
use Carbon\Carbon;
use Livewire\Component;

class RightSidebar extends Component
{
    public $currentDate;
    public $calendarDays = [];
    public $selectedDate;
    
    // Event creation properties
    public $showEventModal = false;
    public $eventTitle = '';
    public $eventDescription = '';
    public $eventStartTime = '';
    public $eventEndTime = '';
    public $eventType = 'meeting';
    public $eventLocation = '';
    public $isAllDay = false;

    protected $rules = [
        'eventTitle' => 'required|string|max:255',
        'eventDescription' => 'nullable|string',
        'eventStartTime' => 'nullable|date_format:H:i',
        'eventEndTime' => 'nullable|date_format:H:i',
        'eventType' => 'required|in:meeting,training,inspection,ceremony,deadline,other',
        'eventLocation' => 'nullable|string|max:255',
        'isAllDay' => 'boolean',
    ];

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->selectedDate = $this->currentDate->format('Y-m-d');
        $this->generateCalendar();
    }

    public function generateCalendar()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        $startOfWeek = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        // Get events for the month to show indicators
        $monthEvents = Event::whereBetween('start_date', [$startOfWeek, $endOfWeek])
            ->where('status', 'active')
            ->get()
            ->groupBy('start_date');

        $this->calendarDays = [];
        $currentDay = $startOfWeek->copy();

        while ($currentDay <= $endOfWeek) {
            $dateString = $currentDay->format('Y-m-d');
            $this->calendarDays[] = [
                'date' => $dateString,
                'day' => $currentDay->day,
                'isCurrentMonth' => $currentDay->month === $this->currentDate->month,
                'isToday' => $currentDay->isToday(),
                'isSelected' => $dateString === $this->selectedDate,
                'hasEvents' => isset($monthEvents[$dateString]) && $monthEvents[$dateString]->count() > 0,
                'eventCount' => isset($monthEvents[$dateString]) ? $monthEvents[$dateString]->count() : 0,
            ];
            $currentDay->addDay();
        }
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->generateCalendar();
    }

    public function addEventForDate($date)
    {
        $this->selectedDate = $date;
        $this->generateCalendar();
        $this->openEventModal();
    }

    public function openEventModal()
    {
        $this->showEventModal = true;
        $this->resetEventForm();
    }

    public function closeEventModal()
    {
        $this->showEventModal = false;
        $this->resetEventForm();
    }

    public function resetEventForm()
    {
        $this->eventTitle = '';
        $this->eventDescription = '';
        $this->eventStartTime = '';
        $this->eventEndTime = '';
        $this->eventType = 'meeting';
        $this->eventLocation = '';
        $this->isAllDay = false;
        $this->resetErrorBag();
    }

    public function createEvent()
    {
        $this->validate();

        Event::create([
            'title' => $this->eventTitle,
            'description' => $this->eventDescription,
            'start_date' => $this->selectedDate,
            'start_time' => $this->isAllDay ? null : $this->eventStartTime,
            'end_time' => $this->isAllDay ? null : $this->eventEndTime,
            'type' => $this->eventType,
            'location' => $this->eventLocation,
            'is_all_day' => $this->isAllDay,
            'created_by' => auth()->id(),
        ]);

        $this->closeEventModal();
        $this->generateCalendar(); // Refresh calendar to show new event indicator
        $this->dispatch('event-created', 'Event created successfully!');
    }

    public function previousMonth()
    {
        $this->currentDate = $this->currentDate->subMonth();
        $this->generateCalendar();
    }

    public function nextMonth()
    {
        $this->currentDate = $this->currentDate->addMonth();
        $this->generateCalendar();
    }

    public function getFilteredEvents()
    {
        return Event::forDate($this->selectedDate)->get();
    }

    public function getUpcomingEvents()
    {
        return Event::upcoming()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.right-sidebar', [
            'filteredEvents' => $this->getFilteredEvents(),
            'upcomingEvents' => $this->getUpcomingEvents(),
        ]);
    }
}
