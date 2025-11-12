<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        return response()->json(Venue::all());
    }

    public function show($id)
    {
        $venue = Venue::with('events')->findOrFail($id);
        return response()->json($venue);
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
        return response()->json($venue, 201);
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        $venue->update($request->all());
        return response()->json($venue);
    }

    public function destroy($id)
    {
        Venue::destroy($id);
        return response()->json(['message' => 'Venue deleted']);
    }
}
