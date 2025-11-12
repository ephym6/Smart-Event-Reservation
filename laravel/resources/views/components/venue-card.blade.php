@props(['name', 'location', 'capacity', 'rating'])

<div class="rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition p-4">
    <img src="https://source.unsplash.com/600x400/?event,{{ urlencode($location) }}" class="rounded-lg mb-3" alt="{{ $name }}">
    <h3 class="text-lg font-bold">{{ $name }}</h3>
    <p class="text-slate-500">{{ $location }}</p>
    <p class="text-slate-600 text-sm mt-1">Capacity: {{ $capacity }} guests</p>
    <div class="mt-2 flex items-center gap-1 text-yellow-500">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= floor($rating))
                ★
            @else
                ☆
            @endif
        @endfor
        <span class="text-slate-600 text-sm ml-1">{{ $rating }}</span>
    </div>
</div>
