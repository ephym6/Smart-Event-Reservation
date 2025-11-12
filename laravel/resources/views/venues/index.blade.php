@extends('layouts.app')

@section('title', 'Venues')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Venues</h1>
    <a href="{{ route('venues.create') }}" class="btn btn-primary">Add New Venue</a>
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
                    <th>Name</th>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Price/Hour</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($venues as $venue)
                <tr>
                    <td>{{ $venue->venue_id }}</td>
                    <td>{{ $venue->venue_name }}</td>
                    <td>{{ $venue->location ?? 'N/A' }}</td>
                    <td>{{ $venue->capacity ?? 'N/A' }}</td>
                    <td>${{ number_format($venue->price_per_hour ?? 0, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $venue->status === 'available' ? 'success' : ($venue->status === 'maintenance' ? 'warning' : 'danger') }}">
                            {{ ucfirst($venue->status ?? 'available') }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('venues.edit', $venue->venue_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('venues.destroy', $venue->venue_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No venues found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
