<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('venue')->get();
        
        if (request()->wantsJson()) {
            return response()->json($events);
        }
        
        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::with('venue')->findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($event);
        }
        
        return view('events.show', compact('event'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_name' => 'required|string',
            'venue_id' => 'nullable|exists:venues,venue_id',
            'event_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'description' => 'nullable|string',
        ]);

        $event = Event::create($data);
        
        if (request()->wantsJson()) {
            return response()->json($event, 201);
        }
        
        return redirect()->route('events.index')->with('success', 'Event created successfully');
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        
        if (request()->wantsJson()) {
            return response()->json($event);
        }
        
        return redirect()->route('events.index')->with('success', 'Event updated successfully');
    }

    public function destroy($id)
    {
        Event::destroy($id);
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Event deleted']);
        }
        
        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }
}
