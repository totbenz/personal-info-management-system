<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $events = Event::with('creator')
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->paginate(20);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable|date_format:H:i',
            'type' => 'required|in:' . implode(',', array_keys(Event::getTypes())),
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'boolean',
        ]);

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'start_time' => $request->is_all_day ? null : $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->is_all_day ? null : $request->end_time,
            'type' => $request->type,
            'location' => $request->location,
            'is_all_day' => $request->boolean('is_all_day'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable|date_format:H:i',
            'type' => 'required|in:' . implode(',', array_keys(Event::getTypes())),
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'boolean',
            'status' => 'required|in:' . implode(',', array_keys(Event::getStatuses())),
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'start_time' => $request->is_all_day ? null : $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->is_all_day ? null : $request->end_time,
            'type' => $request->type,
            'location' => $request->location,
            'is_all_day' => $request->boolean('is_all_day'),
            'status' => $request->status,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}
