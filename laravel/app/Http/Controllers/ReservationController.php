<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $query = Reservation::with(['user', 'venue', 'event']);

        if (auth()->check() && in_array(auth()->user()->role, ['admin','manager'])) {
            $reservations = $query->get();
        } else {
            $reservations = $query->where('user_id', auth()->id())->get();
        }
        
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
        
        return redirect()->route('reservations.success', $reservation->reservation_id);
    }

    public function success($id)
    {
        $reservation = Reservation::with(['venue','event','user'])->findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Reservation successful', 'reservation' => $reservation]);
        }
        
        return view('reservations.success', compact('reservation'));
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

    public function approve($id)
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, ['admin','manager'])) {
            abort(403);
        }
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'approved';
        $reservation->save();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Reservation approved', 'reservation' => $reservation]);
        }
        return back()->with('success', 'Reservation approved');
    }

    public function decline($id)
    {
        if (! auth()->check() || ! in_array(auth()->user()->role, ['admin','manager'])) {
            abort(403);
        }
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'cancelled';
        $reservation->save();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Reservation declined', 'reservation' => $reservation]);
        }
        return back()->with('success', 'Reservation declined');
    }
}
