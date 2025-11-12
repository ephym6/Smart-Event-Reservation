<x-layouts.app :title="$venue->venue_name">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-3">{{ $venue->venue_name }}</h1>
        <p class="text-slate-600 mb-4">{{ $venue->description ?? 'No description available.' }}</p>

        <ul class="space-y-1 text-slate-700 mb-6">
            <li><strong>Location:</strong> {{ $venue->location }}</li>
            <li><strong>Capacity:</strong> {{ $venue->capacity }}</li>
            <li><strong>Price:</strong> Ksh. {{ number_format($venue->price_per_hour, 2) }} / hour</li>
        </ul>

        <div class="flex gap-4">
            <a href="{{ route('venues.book', ['venue_id' => $venue->venue_id]) }}" 
               class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Book This Venue
            </a>
            <a href="{{ route('venues.list') }}" 
               class="inline-block text-blue-600 hover:underline">Back to List</a>
        </div>
    </div>
</x-layouts.app>
