@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Events</h1>
    <a href="{{ route('events.create') }}" class="btn btn-primary">Add New Event</a>
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
                    <th>Event Name</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr>
                    <td>{{ $event->event_id }}</td>
                    <td>{{ $event->event_name }}</td>
                    <td>{{ $event->venue->venue_name ?? 'No venue' }}</td>
                    <td>{{ $event->event_date ? $event->event_date->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $event->start_time ?? 'N/A' }}</td>
                    <td>{{ $event->end_time ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No events found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
