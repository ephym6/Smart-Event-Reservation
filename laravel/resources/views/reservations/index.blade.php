@extends('layouts.app')

@section('title', 'Reservations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Reservations</h1>
    <a href="{{ route('reservations.create') }}" class="btn btn-primary">Add New Reservation</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Venue</th>
                    <th>Event</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Total Cost</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->reservation_id }}</td>
                    <td>{{ $reservation->user->name ?? 'N/A' }}</td>
                    <td>{{ $reservation->venue->venue_name ?? 'N/A' }}</td>
                    <td>{{ $reservation->event->event_name ?? 'N/A' }}</td>
                    <td>{{ $reservation->start_time ? $reservation->start_time->format('M d, Y H:i') : 'N/A' }}</td>
                    <td>{{ $reservation->end_time ? $reservation->end_time->format('M d, Y H:i') : 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $reservation->status === 'approved' ? 'success' : ($reservation->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($reservation->status ?? 'pending') }}
                        </span>
                    </td>
                    <td>${{ number_format($reservation->total_cost ?? 0, 2) }}</td>
                    <td>
                        <a href="{{ route('reservations.edit', $reservation->reservation_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('reservations.destroy', $reservation->reservation_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No reservations found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
