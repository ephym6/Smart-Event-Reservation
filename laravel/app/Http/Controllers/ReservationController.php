<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'nullable|date|after:start_time',
            'duration_hours' => 'nullable|numeric|min:0.5|max:48',
            'status' => 'nullable|in:pending,approved,cancelled,completed',
            // total_cost will be computed server-side
        ]);

        // Require either end_time or duration
        if (empty($data['end_time']) && empty($data['duration_hours'])) {
            return back()->withErrors(['end_time' => 'Provide an end time or duration in hours.'])->withInput();
        }

        // If duration provided but no end_time, compute it
        if (empty($data['end_time']) && !empty($data['duration_hours'])) {
            $data['end_time'] = Carbon::parse($data['start_time'])->addMinutes((int) round($data['duration_hours'] * 60));
        }

        // Normalize to Carbon instances
        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);
        if ($end->lessThanOrEqualTo($start)) {
            return back()->withErrors(['end_time' => 'End time must be after start time.'])->withInput();
        }

        // Prevent overlapping reservations for same venue (pending/approved)
        $overlapExists = Reservation::where('venue_id', $data['venue_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function($q) use ($start, $end) {
                $q->where('start_time', '<=', $end)
                  ->where('end_time', '>=', $start);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['venue_id' => 'This venue is already reserved for the selected time.'])->withInput();
        }

        // Compute total cost from venue price_per_hour and duration
        $venue = Venue::findOrFail($data['venue_id']);
        $minutes = $start->diffInMinutes($end);
        $hours = $minutes / 60.0;
        $rate = (float) ($venue->price_per_hour ?? 0);
        $data['total_cost'] = round($rate * $hours, 2);

        $data['user_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 'pending';

        $reservation = Reservation::create([
            'user_id' => $data['user_id'],
            'venue_id' => $data['venue_id'],
            'event_id' => $data['event_id'] ?? null,
            'start_time' => $start,
            'end_time' => $end,
            'status' => $data['status'],
            'total_cost' => $data['total_cost'],
        ]);
        
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
