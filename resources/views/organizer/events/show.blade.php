@extends('layouts.organizer')

@section('content')
<div class="space-y-6">
    <!-- Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-muted hover:text-ink transition">
                ← Back to Event Catalog
            </a>
            <div class="flex items-center gap-3">
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink leading-tight">
                    {{ $event->title }}
                </h1>
                <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
        </div>

        <!-- Action Controls -->
        <div class="flex items-center gap-2">
            <a href="{{ route('organizer.events.edit', $event->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-line bg-white hover:bg-soft text-ink text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Event</span>
            </a>

            <form action="{{ route('organizer.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel or delete this event?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition">
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Analytics Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Revenue --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Total Revenue
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            KES {{ number_format($event->orders->where('status', 'paid')->sum('total_amount'), 0) }}
        </p>

        <p class="text-[10px] text-muted">
            From paid orders
        </p>
    </div>


    {{-- Tickets Sold --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Tickets Sold
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            {{ $event->tickets_count ?? $event->ticketTypes->sum('sold_quantity') }}
        </p>

        <p class="text-[10px] text-muted">
            Confirmed admissions
        </p>
    </div>


    {{-- Total Capacity --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Total Capacity
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            {{ number_format($event->ticketTypes->sum('total_quantity')) }}
        </p>

        <p class="text-[10px] text-muted">
            Across all ticket types
        </p>
    </div>


    {{-- Ticket Tiers --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Ticket Tiers
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            {{ $event->ticketTypes->count() }}
        </p>

        <p class="text-[10px] text-muted">
            Categories available
        </p>
    </div>


    {{-- Total Orders --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Total Orders
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            {{ $event->orders->count() }}
        </p>

        <p class="text-[10px] text-muted">
            All order attempts
        </p>
    </div>


    {{-- Paid Orders --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Paid Orders
        </span>

        <p class="font-display text-2xl font-extrabold text-emerald-600">
            {{ $event->orders->where('status', 'paid')->count() }}
        </p>

        <p class="text-[10px] text-muted">
            Successfully completed
        </p>
    </div>


    {{-- Pending Orders --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Pending Orders
        </span>

        <p class="font-display text-2xl font-extrabold text-amber-600">
            {{ $event->orders->where('status', 'pending')->count() }}
        </p>

        <p class="text-[10px] text-muted">
            Awaiting payment
        </p>
    </div>


    {{-- Available Tickets --}}
    @php
        $totalCapacity = $event->ticketTypes->sum('total_quantity');
        $ticketsSold = $event->tickets_count ?? $event->ticketTypes->sum('sold_quantity');
        $ticketsRemaining = max(0, $totalCapacity - $ticketsSold);

        $soldPercentage = $totalCapacity > 0
            ? round(($ticketsSold / $totalCapacity) * 100)
            : 0;
    @endphp

    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Tickets Remaining
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            {{ number_format($ticketsRemaining) }}
        </p>

        <div class="flex items-center gap-2 pt-1">
            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div
                    class="h-full bg-accent rounded-full"
                    style="width: {{ min(100, $soldPercentage) }}%"
                ></div>
            </div>

            <span class="text-[9px] font-bold text-muted">
                {{ $soldPercentage }}% sold
            </span>
        </div>
    </div>


    {{-- Pending Revenue --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Pending Revenue
        </span>

        <p class="font-display text-2xl font-extrabold text-amber-600">
            KES {{ number_format($event->orders->where('status', 'pending')->sum('total_amount'), 0) }}
        </p>

        <p class="text-[10px] text-muted">
            Potential revenue
        </p>
    </div>


    {{-- Average Order Value --}}
    @php
        $paidOrders = $event->orders->where('status', 'paid');
        $paidOrderCount = $paidOrders->count();

        $averageOrderValue = $paidOrderCount > 0
            ? $paidOrders->sum('total_amount') / $paidOrderCount
            : 0;
    @endphp

    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Avg. Order Value
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            KES {{ number_format($averageOrderValue, 0) }}
        </p>

        <p class="text-[10px] text-muted">
            Per paid order
        </p>
    </div>


    {{-- Tickets Checked In --}}
    @php
        $checkedIn = $event->tickets->where('status', 'used')->count();
    @endphp

    <div class="bg-white border border-line rounded-2xl p-5 space-y-1 shadow-sm">
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
            Checked In
        </span>

        <p class="font-display text-2xl font-extrabold text-ink">
            {{ number_format($checkedIn) }}
        </p>

        <p class="text-[10px] text-muted">
            Tickets scanned at gate
        </p>
    </div>
    </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Overview Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-line rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                @if($event->banner_url)
                    <div class="rounded-xl overflow-hidden h-48 sm:h-64 bg-paper border border-line">
                        <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="space-y-2">
                    <h3 class="font-display text-base font-extrabold text-ink">About This Event</h3>
                    <p class="text-xs font-medium text-muted leading-relaxed whitespace-pre-line">
                        {{ $event->description }}
                    </p>
                </div>

                <!-- Schedule & Venue Information -->
                <div class="pt-6 border-t border-line grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-muted block">Venue & Location</span>
                        <p class="font-bold text-ink">{{ $event->venue_name }}</p>
                        <p class="text-muted font-medium">{{ $event->venue_address }}</p>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-muted block">Date & Schedule</span>
                        <p class="font-bold text-ink">Start: {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y @ H:i') }}</p>
                        <p class="text-muted font-medium">End: {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y @ H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Tiers Breakdown -->
        <div class="space-y-6">
            <div class="bg-white border border-line rounded-2xl p-6 space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-extrabold text-ink">Ticket Tiers</h3>
                    <span class="text-xs font-bold text-muted">{{ $event->ticketTypes->count() }} Tiers</span>
                </div>

                <div class="space-y-3">
                    @foreach($event->ticketTypes as $tier)
                        <div class="p-4 bg-paper border border-line rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-ink">{{ $tier->name }}</span>
                                <span class="text-xs font-extrabold text-accent">KES {{ number_format($tier->price, 0) }}</span>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[11px] font-medium text-muted">
                                    <span>Sales Progress</span>
                                    <span>{{ $tier->sold_quantity ?? 0 }} / {{ $tier->total_quantity }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-line rounded-full overflow-hidden">
                                    <div class="h-full bg-accent rounded-full" style="width: {{ $tier->total_quantity > 0 ? (($tier->sold_quantity ?? 0) / $tier->total_quantity) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection