@extends('layouts.app')

@section('content')

    <!-- Header / Hero Banner -->
    <div class="bg-[#f1eee9] py-16 border-b border-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <span class="text-xs font-extrabold tracking-widest text-accent uppercase mb-3 block">EVENTS, WITHOUT THE FUSS</span>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-ink tracking-tight leading-none">
                    Go where the good times are.
                </h1>
                <p class="mt-4 text-base sm:text-lg text-muted">
                    Discover concerts, tech summits, parties, and exclusive experiences. Find your ticket, make a plan, show up.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Grid Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">What's happening</h2>
                <p class="text-sm text-muted mt-1">Fresh picks and popular plans for your calendar.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
                <div class="bg-white rounded-2xl border border-line overflow-hidden hover:-translate-y-1 hover:shadow-xl transition duration-200 flex flex-col group">
                    <!-- Event Banner & Date Badge -->
                    <div class="h-52 w-full bg-soft relative overflow-hidden">
                        @if($event->banner_url)
                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-muted font-display font-bold text-lg">ShikaTicket Event</div>
                        @endif
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur px-3 py-1 rounded-md text-[11px] font-extrabold uppercase tracking-wider text-ink shadow-sm">
                            {{ $event->start_date->format('M d, Y') }}
                        </div>
                    </div>

                    <!-- Event Content -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-accent uppercase tracking-wider">{{ $event->organizer->name }}</span>
                            <h3 class="font-display font-bold text-xl text-ink group-hover:text-accent transition mt-1 leading-snug">{{ $event->title }}</h3>
                            <p class="mt-2 text-xs text-muted line-clamp-2 leading-relaxed">{{ $event->description }}</p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-line flex items-end justify-between">
                            <div>
                                <span class="text-[11px] text-muted block">Tickets from</span>
                                <span class="text-base font-extrabold text-ink font-display">
                                    KES {{ number_format($event->ticketTypes->min('price') ?? 0, 0) }}
                                </span>
                            </div>
                            <a href="{{ route('events.show', $event->slug) }}" class="inline-flex items-center justify-center px-4 py-2 bg-accent hover:bg-accent-dark text-white text-xs font-bold rounded-xl transition">
                                Get tickets →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-line">
                    <h3 class="font-display font-bold text-xl text-ink">No events found</h3>
                    <p class="text-muted text-sm mt-1">No upcoming events scheduled right now. Check back soon!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $events->links() }}
        </div>
    </div>
@endsection