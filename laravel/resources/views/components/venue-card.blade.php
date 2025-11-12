@props([
    // Accept either a full $venue model/array or individual props
    'venue' => null,
    'name' => 'Grand Ballroom',
    'location' => 'Nairobi, Kenya',
    'capacity' => 200,
    'rating' => 4.5,
    'image' => null,
    'price' => null,
    // URLs (string) - passed from listing/details
    'detailsUrl' => null,
    'bookUrl' => null,
])

@php
    // If a venue object/array is provided, map common fields to component variables
    if ($venue) {
        // model properties can be objects (->) or arrays
        $get = function ($keys) use ($venue) {
            foreach ((array) $keys as $k) {
                if (is_object($venue) && isset($venue->{$k})) return $venue->{$k};
                if (is_array($venue) && isset($venue[$k])) return $venue[$k];
            }
            return null;
        };

        $name = $get(['venue_name','name']) ?? $name;
        $location = $get(['location']) ?? $location;
        $capacity = $get(['capacity']) ?? $capacity;
        $rating = $get(['rating']) ?? $rating;
        $image = $get(['image','photo']) ?? $image;
        $price = $get(['price_per_hour','price']) ?? $price;

        // Build reasonable defaults for URLs if not provided
        if (empty($detailsUrl)) {
            $id = $get(['venue_id','id']);
            if ($id) $detailsUrl = route('venues.details', ['id' => $id]);
        }
        if (empty($bookUrl)) {
            $id = $get(['venue_id','id']);
            if ($id) $bookUrl = route('venues.book', ['venue_id' => $id]);
        }
    }
    // final defaults
    $detailsUrl = $detailsUrl ?? '#';
    $bookUrl = $bookUrl ?? '#';
@endphp

<div class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow">
    @if ($image)
        <img src="{{ $image }}" alt="{{ $name }}" class="h-40 w-full object-cover"/>
    @endif
    <div class="p-4">
        <h3 class="text-base font-semibold text-slate-900">{{ $name }}</h3>
        @if(!is_null($price))
            <p class="mt-1 text-sm font-medium">Ksh.{{ number_format($price, 2) }} / hr</p>
        @endif
        <div class="mt-1 flex items-center gap-3 text-sm text-slate-600">
            <span class="inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.992 2.25c-4.694 0-8.5 3.797-8.5 8.481 0 5.909 7.786 10.537 8.117 10.73a.75.75 0 0 0 .766 0c.33-.193 8.117-4.821 8.117-10.73 0-4.684-3.806-8.48-8.5-8.48Zm0 11.48a3 3 0 1 1 0-5.999 3 3 0 0 1 0 5.999Z"/></svg>
                <span>{{ $location }}</span>
            </span>
            <span class="inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 7a4 4 0 1 1 8 0v2h1.75A2.25 2.25 0 0 1 20 11.25v7.5A2.25 2.25 0 0 1 17.75 21H6.25A2.25 2.25 0 0 1 4 18.75v-7.5A2.25 2.25 0 0 1 6.25 9H8V7Zm6.5 2V7a2.5 2.5 0 0 0-5 0v2h5Z"/></svg>
                <span>{{ $capacity }} guests</span>
            </span>
        </div>
            <div class="mt-2 flex items-center justify-between">
            <div class="flex items-center gap-1 text-amber-500">
                @for ($i = 1; $i <= 5; $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4" fill="{{ $i <= floor($rating) ? 'currentColor' : 'none' }}" stroke="currentColor"><path d="M11.48 3.5a.75.75 0 0 1 1.04 0l2.36 2.4a.75.75 0 0 0 .42.21l3.29.47a.75.75 0 0 1 .42 1.28l-2.38 2.32a.75.75 0 0 0-.22.66l.56 3.27a.75.75 0 0 1-1.09.79l-2.94-1.54a.75.75 0 0 0-.7 0l-2.94 1.54a.75.75 0 0 1-1.09-.79l.56-3.27a.75.75 0 0 0-.22-.66L5 8.86a.75.75 0 0 1 .42-1.28l3.29-.47a.75.75 0 0 0 .42-.21L11.48 3.5Z"/></svg>
                @endfor
                <span class="ml-1 text-xs text-slate-600">{{ number_format($rating,1) }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ $detailsUrl }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">View Details</a>
                <a href="{{ $bookUrl }}" class="inline-flex items-center rounded-md border border-blue-600 px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50">Book</a>
            </div>
        </div>
    </div>
</div>
