@extends('layouts.app')

@section('content')

    <!-- Hero / Header Banner -->
    <div class="bg-[#f1eee9] py-16 lg:py-20 border-b border-line relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl space-y-4">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 border border-accent/20 text-accent text-xs font-extrabold uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                    Events, Without The Fuss
                </span>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-ink tracking-tight leading-[1.08]">
                    Go where the <span class="text-accent underline decoration-accent/30 decoration-wavy decoration-2">good times</span> are.
                </h1>
                <p class="text-base sm:text-lg text-muted max-w-2xl leading-relaxed">
                    Discover live music, tech summits, sports, and exclusive local experiences. Secure your pass in seconds and show up.
                </p>
            </div>

            <!-- Search & Quick Filters Bar -->
            <!-- <div class="mt-10 max-w-4xl bg-white border border-line p-2 sm:p-3 rounded-2xl shadow-xl shadow-black/5 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" placeholder="Search events, venues, or organizers..." 
                           class="w-full pl-11 pr-4 py-3 bg-paper border-0 rounded-xl text-xs font-semibold text-ink placeholder-muted focus:ring-2 focus:ring-accent focus:bg-white transition">
                </div>
                <div class="flex items-center gap-2">
                    <select name="category" class="px-4 py-3 bg-paper border-0 rounded-xl text-xs font-bold text-ink focus:ring-2 focus:ring-accent cursor-pointer">
                        <option value="">All Categories</option>
                        <option value="music">Music & Concerts</option>
                        <option value="sports">Sports & Cycling</option>
                        <option value="tech">Tech & Business</option>
                        <option value="parties">Nightlife & Parties</option>
                    </select>
                    <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-dark text-white font-bold text-xs rounded-xl transition shadow-sm hover:shadow-md">
                        Explore
                    </button>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Main Events Grid Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">Upcoming Events</h2>
                <p class="text-xs sm:text-sm text-muted mt-1">Fresh picks and popular plans happening near you.</p>
            </div>
            
            <div class="flex items-center gap-2 bg-paper border border-line p-1 rounded-xl text-xs font-bold text-ink">
                <button class="px-3 py-1.5 rounded-lg bg-white shadow-sm border border-line">Grid View</button>
                <button class="px-3 py-1.5 rounded-lg text-muted hover:text-ink transition">This Weekend</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
                <div class="bg-white rounded-2xl border border-line overflow-hidden hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-black/10 transition-all duration-300 flex flex-col group">
                    
                    <!-- Event Banner Header -->
                    <div class="w-full bg-slate-900 relative overflow-hidden aspect-[16/9] flex items-center justify-center">
                        @if($event->banner_url)
                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-soft text-muted font-display font-bold text-sm">
                                ShikaTicket Event
                            </div>
                        @endif
                        
                        <!-- Overlay Gradients -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/10 opacity-80 group-hover:opacity-90 transition"></div>

                        <!-- Date Badge -->
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl text-center shadow-lg border border-white/20">
                            <span class="block text-[10px] font-black uppercase text-accent leading-none">{{ $event->start_date->format('M') }}</span>
                            <span class="block text-sm font-extrabold text-ink leading-tight mt-0.5">{{ $event->start_date->format('d') }}</span>
                        </div>

                        <!-- Venue Badge -->
                        <div class="absolute bottom-3 left-3 right-3 flex items-center gap-1.5 text-white/90 text-[11px] font-semibold truncate">
                            <svg class="w-3.5 h-3.5 text-accent shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="truncate">{{ $event->venue_name }}</span>
                        </div>
                    </div>

                    <!-- Event Content Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold text-accent uppercase tracking-wider truncate">
                                    {{ $event->organizer->name }}
                                </span>
                                <span class="text-[11px] font-medium text-muted shrink-0">
                                    {{ $event->start_date->format('h:i A') }}
                                </span>
                            </div>

                            <h3 class="font-display font-bold text-lg text-ink group-hover:text-accent transition duration-200 line-clamp-1 leading-snug">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <p class="text-xs text-muted line-clamp-2 leading-relaxed">
                                {{ $event->description }}
                            </p>
                        </div>

                        <!-- Footer Card Info -->
                        <div class="pt-4 border-t border-line flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-muted block tracking-wider">Tickets from</span>
                                <span class="text-base font-extrabold text-ink font-display">
                                    @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                    @if($minPrice == 0 || is_null($minPrice))
                                        Free
                                    @else
                                        KES {{ number_format($minPrice, 0) }}
                                    @endif
                                </span>
                            </div>

                            <a href="{{ route('events.show', $event->slug) }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-accent hover:bg-accent-dark text-white text-xs font-bold rounded-xl transition duration-150 shadow-sm group-hover:shadow-md">
                                <span>Get Tickets</span>
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-line p-8 max-w-xl mx-auto space-y-4">
                    <div class="w-16 h-16 bg-accent/10 text-accent rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                        🎟️
                    </div>
                    <div>
                        <h3 class="font-display font-extrabold text-2xl text-ink">No events found</h3>
                        <p class="text-muted text-xs sm:text-sm mt-1 max-w-sm mx-auto leading-relaxed">
                            We couldn't find any upcoming events matching your selection. Try resetting your search filters or check back later!
                        </p>
                    </div>
                    <a href="{{ route('events.index') }}" class="inline-block px-5 py-2.5 bg-paper border border-line hover:bg-soft text-ink font-bold text-xs rounded-xl transition">
                        Clear Filters & Reload
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="mt-14 pt-8 border-t border-line">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection