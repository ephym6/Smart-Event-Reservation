<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class VenueController extends Controller
{
    // Show dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)->with('venue')->get();
        return view('dashboard', compact('user', 'reservations'));
    }

    // List all venues
    public function index()
    {
        $venues = Venue::all();
        return view('venues.listing', compact('venues'));
    }

    // Show single venue details
    public function show($id)
    {
        $venue = Venue::find($id);
        if (!$venue) {
            abort(404, 'Venue not found');
        }
        return view('venues.details', compact('venue'));
    }

    // Show booking form
    public function book(Request $request)
    {
        $venues = Venue::all();
        $preselect = $request->query('venue_id', 0);
        return view('venues.book', compact('venues', 'preselect'));
    }

    // Handle booking form submission
    public function reserve(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'required|exists:venues,venue_id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $overlap = Reservation::where('venue_id', $validated['venue_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['error' => 'This venue is already booked for the selected time.'])->withInput();
        }

        Reservation::create([
            'venue_id' => $validated['venue_id'],
            'user_id' => Auth::id(),
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Venue successfully reserved!');
    }
}
