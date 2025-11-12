<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::all();
        
        if (request()->wantsJson()) {
            return response()->json($venues);
        }
        
        return view('venues.index', compact('venues'));
    }

    public function show($id)
    {
        $venue = Venue::with('events')->findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($venue);
        }
        
        return view('venues.show', compact('venue'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_name' => 'required|string',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer',
            'location' => 'nullable|string',
            'price_per_hour' => 'nullable|numeric',
            'status' => 'in:available,maintenance,unavailable',
        ]);

        $venue = Venue::create($data);
        
        if (request()->wantsJson()) {
            return response()->json($venue, 201);
        }
        
        return redirect()->route('venues.index')->with('success', 'Venue created successfully');
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        $venue->update($request->all());
        
        if (request()->wantsJson()) {
            return response()->json($venue);
        }
        
        return redirect()->route('venues.index')->with('success', 'Venue updated successfully');
    }

    public function destroy($id)
    {
        Venue::destroy($id);
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Venue deleted']);
        }
        
        return redirect()->route('venues.index')->with('success', 'Venue deleted successfully');
    }
}
