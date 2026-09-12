@extends('layouts.organizer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink">Event Catalog</h1>
            <p class="text-xs text-muted mt-1">Manage and view all your active and upcoming event listings.</p>
        </div>
        <div>
            <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-accent hover:bg-accent-dark text-white text-xs font-bold transition shadow-sm hover:-translate-y-0.5">
                <span>+ Add New Event</span>
            </a>
        </div>
    </div>

    <!-- Cards Grid -->
    @if($events->isEmpty())
        <div class="p-12 text-center bg-white rounded-2xl border border-line shadow-sm">
            <p class="text-xs font-medium text-muted">No events found. Click "+ Add New Event" to launch your first event.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white border border-line rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-muted/30 transition-all">
                    <!-- Card Top Content -->
                    <div class="p-6 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ ucfirst($event->status) }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 text-[11px] rounded-lg bg-paper border border-line font-bold text-ink">
                                {{ $event->ticket_types_count }} Active Tier(s)
                            </span>
                        </div>

                        <div>
                            <a href="{{ route('organizer.events.show', $event->id) }}" class="group">
                                <h2 class="font-display text-lg font-bold text-ink leading-snug line-clamp-1 group-hover:text-accent transition">
                                    {{ $event->title }}
                                </h2>
                            </a>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-line text-xs font-medium text-muted">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-muted shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">{{ $event->venue_name }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-muted shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y @ H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Action Footer -->
                    <div class="px-6 py-3.5 bg-paper border-t border-line flex items-center justify-between text-xs font-bold text-ink">
                        <a href="{{ route('organizer.events.show', $event->id) }}" class="text-accent hover:underline">
                            View Details →
                        </a>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('organizer.events.edit', $event->id) }}" class="text-muted hover:text-ink transition">
                                Edit
                            </a>

                            <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Pagination -->
    <div class="pt-2">
        {{ $events->links() }}
    </div>
</div>
@endsection