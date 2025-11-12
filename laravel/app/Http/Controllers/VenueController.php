<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VenueController extends Controller
{
    public function index()
    {
        $date = request('date');
        $location = request('location');
        $guests = request('guests');

        $start = $date ? Carbon::parse($date)->startOfDay() : now()->startOfDay();
        $end = $date ? Carbon::parse($date)->endOfDay() : now()->endOfDay();

        $query = Venue::query();

        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->where('location', 'like', "%{$location}%")
                  ->orWhere('venue_name', 'like', "%{$location}%");
            });
        }

        if ($guests) {
            $query->where('capacity', '>=', (int) $guests);
        }

        // Count reservations overlapping the selected day (pending/approved)
        $venues = $query->withCount([
            'reservations as active_reservations_count' => function ($q) use ($start, $end) {
                $q->whereIn('status', ['pending', 'approved'])
                  ->where(function ($q2) use ($start, $end) {
                      $q2->where('start_time', '<=', $end)
                         ->where('end_time', '>=', $start);
                  });
            }
        ])->get();
        
        if (request()->wantsJson()) {
            return response()->json($venues);
        }
        
        return view('venues.index', compact('venues', 'date', 'location', 'guests'));
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
        if (! auth()->check() || ! in_array(auth()->user()->role, ['admin','manager'])) {
            abort(403);
        }

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
        if (! auth()->check() || ! in_array(auth()->user()->role, ['admin','manager'])) {
            abort(403);
        }
        $venue = Venue::findOrFail($id);
        $venue->update($request->all());
        
        if (request()->wantsJson()) {
            return response()->json($venue);
        }
        
        return redirect()->route('venues.index')->with('success', 'Venue updated successfully');
    }

    public function destroy($id)
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, ['admin','manager'])) {
            abort(403);
        }
        Venue::destroy($id);
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Venue deleted']);
        }
        
        return redirect()->route('venues.index')->with('success', 'Venue deleted successfully');
    }
}
