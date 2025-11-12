<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'venue', 'event'])->get();
        
        if (request()->wantsJson()) {
            return response()->json($reservations);
        }
        
        return view('reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = Reservation::with(['user', 'venue', 'event', 'reservationItems'])->findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($reservation);
        }
        
        return view('reservations.show', compact('reservation'));
    }

    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'));
        }

        $data = $request->validate([
            'venue_id' => 'required|exists:venues,venue_id',
            'event_id' => 'nullable|exists:events,event_id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'nullable|in:pending,approved,cancelled,completed',
            'total_cost' => 'nullable|numeric',
        ]);

        // Prevent overlapping reservations for same venue (pending/approved)
        $overlapExists = Reservation::where('venue_id', $data['venue_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function($q) use ($data) {
                $q->where('start_time', '<=', $data['end_time'])
                  ->where('end_time', '>=', $data['start_time']);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['venue_id' => 'This venue is already reserved for the selected time.'])->withInput();
        }

        $data['user_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 'pending';

        $reservation = Reservation::create($data);
        
        if (request()->wantsJson()) {
            return response()->json($reservation, 201);
        }
        
        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully');
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());
        
        if (request()->wantsJson()) {
            return response()->json($reservation);
        }
        
        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully');
    }

    public function destroy($id)
    {
        Reservation::destroy($id);
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Reservation deleted']);
        }
        
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully');
    }
}
