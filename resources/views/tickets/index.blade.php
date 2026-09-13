@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-3xl font-extrabold text-ink tracking-tight">My Ticket Wallet</h1>
            <p class="text-sm text-muted mt-1">Access your event passes, check entry status, and view QR codes.</p>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="bg-white border border-line rounded-3xl p-12 text-center max-w-md mx-auto shadow-sm">
            <div class="w-16 h-16 bg-soft border border-line rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                🎟️
            </div>
            <h3 class="font-display font-bold text-lg text-ink mb-1">No Tickets Found</h3>
            <p class="text-xs text-muted mb-6 leading-relaxed">You haven't purchased any event tickets yet. Explore upcoming events to get started.</p>
            <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold text-white bg-accent hover:bg-accent-dark rounded-xl transition shadow-sm">
                Explore Events
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tickets as $ticket)
                <div class="bg-white border border-line rounded-3xl p-6 flex flex-col justify-between hover:shadow-md transition-all duration-200">
                    <div>
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full border {{ $ticket->status === 'used' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                {{ $ticket->status === 'used' ? 'Used' : 'Valid Entry' }}
                            </span>
                            <span class="text-xs font-bold text-muted">
                                #{{ $ticket->ticket_code }}
                            </span>
                        </div>

                        <!-- Event Title & Info -->
                        <h2 class="font-display font-extrabold text-lg text-ink leading-snug line-clamp-2 mb-2">
                            {{ $ticket->order->event->title ?? 'Event Ticket' }}
                        </h2>
                        
                        <div class="space-y-1.5 text-xs text-muted mb-6">
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2 2 2 0 012 2 2 2 0 01-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 01-2-2 2 2 0 012-2V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <span class="font-bold text-ink">{{ $ticket->ticketType->name ?? 'Standard' }} Pass</span>
                            </p>
                            @if(optional($ticket->order->event)->start_date)
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-muted flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($ticket->order->event->start_date)->format('M d, Y @ h:i A') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('tickets.show', $ticket) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-soft hover:bg-line text-ink rounded-xl text-xs font-bold transition">
                        <span>View Entry Pass</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection