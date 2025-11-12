@props([
    'name' => '',
    'date' => null,
    'venue' => '',
    'url' => '#',
])
<a href="{{ $url }}" class="block rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="font-semibold text-slate-900 tracking-tight">{{ $name }}</h3>
            <p class="text-sm text-slate-600 mt-1 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M6.75 2.25A.75.75 0 0 0 6 3v.75H4.5A2.25 2.25 0 0 0 2.25 6v12A2.25 2.25 0 0 0 4.5 20.25h15a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 19.5 3.75H18V3a.75.75 0 0 0-1.5 0v.75h-9V3a.75.75 0 0 0-1.5 0v.75H6V3a.75.75 0 0 0-.75-.75Z"/><path d="M6 9h12v9H6z"/></svg>
                {{ $date ? (\Illuminate\Support\Carbon::parse($date)->format('M d, Y')) : 'TBD' }}
            </p>
            <p class="text-sm text-slate-600 mt-1 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.25a7.5 7.5 0 0 0-7.5 7.5c0 5.25 7.5 12 7.5 12s7.5-6.75 7.5-12a7.5 7.5 0 0 0-7.5-7.5Z"/><path d="M12 12.375a2.625 2.625 0 1 1 0-5.25 2.625 2.625 0 0 1 0 5.25Z"/></svg>
                {{ $venue ?: '—' }}
            </p>
        </div>
        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">Event</span>
    </div>
</a>
