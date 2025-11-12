<x-layouts.app :title="'Available Venues'">
    <h1 class="text-2xl font-semibold mb-6">Available Venues</h1>

    @if($venues->isEmpty())
        <p class="text-slate-600">No venues available at the moment.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($venues as $venue)
                <x-venue-card :venue="$venue" />
            @endforeach
        </div>
    @endif

    <div class="mt-10">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>
</x-layouts.app>
