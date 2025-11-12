<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return response()->json(
            Reservation::with(['user', 'venue', 'event', 'items'])->get()
        );
    }

    public function show($id)
    {
        $reservation = Reservation::with(['user', 'venue', 'event', 'items'])->findOrFail($id);
        return response()->json($reservation);
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
        return response()->json($reservation, 201);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());
        return response()->json($reservation);
    }

    public function destroy($id)
    {
        Reservation::destroy($id);
        return response()->json(['message' => 'Reservation deleted']);
    }
}
