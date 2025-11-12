<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,user_id',
            'venue_id' => 'nullable|exists:venues,venue_id',
            'event_id' => 'nullable|exists:events,event_id',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'status' => 'in:pending,approved,cancelled,completed',
            'total_cost' => 'nullable|numeric',
        ]);

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
