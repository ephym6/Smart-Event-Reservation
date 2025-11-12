<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::with('venue')->get());
    }

    public function show($id)
    {
        $event = Event::with('venue')->findOrFail($id);
        return response()->json($event);
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
        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        return response()->json(['message' => 'Event deleted']);
    }
}
