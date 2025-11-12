<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Venue;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Simple role gate
        if (! auth()->check() || ! in_array(auth()->user()->role, ['admin','manager'])) {
            return redirect()->route('home')->with('error', 'Not authorized.');
        }

        $venuesCount = Venue::count();
        $reservationsCount = Reservation::count();

        // Reserved venues for today (overlap with today)
        $start = now()->startOfDay();
        $end = now()->endOfDay();
        $reservedVenues = Venue::withCount([
            'reservations as active_reservations_count' => function ($q) use ($start, $end) {
                $q->whereIn('status', ['pending','approved'])
                  ->where(function ($q2) use ($start, $end) {
                      $q2->where('start_time', '<=', $end)
                         ->where('end_time', '>=', $start);
                  });
            }
        ])->having('active_reservations_count', '>', 0)->get();

        $recentReservations = Reservation::with(['user','venue','event'])
            ->orderByDesc('start_time')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'venuesCount', 'reservationsCount', 'reservedVenues', 'recentReservations'
        ));
    }
}
