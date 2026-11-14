
@extends('layouts.marketer')

@section('title', 'Overview')

@section('content')

<div class="space-y-7">

    {{-- Welcome --}}
    <div>
        <h1 class="font-display text-2xl sm:text-3xl font-extrabold">
            Welcome back, {{ auth()->user()->name }}
        </h1>

        <p class="text-xs text-muted mt-2">
            Here's how your event promotions are performing.
        </p>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Total Revenue
            </p>

            <p class="font-display text-2xl font-extrabold mt-3">
                KES {{ number_format($totalRevenue, 2) }}
            </p>

            <p class="text-[10px] text-muted mt-2">
                From your paid ticket sales
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Tickets Sold
            </p>

            <p class="font-display text-2xl font-extrabold mt-3">
                {{ number_format($ticketsSold) }}
            </p>

            <p class="text-[10px] text-muted mt-2">
                Confirmed ticket admissions
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Total Commission
            </p>

            <p class="font-display text-2xl font-extrabold text-emerald-600 mt-3">
                KES {{ number_format($commissions['earned'], 2) }}
            </p>

            <p class="text-[10px] text-muted mt-2">
                Commission from paid orders
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Pending Commission
            </p>

            <p class="font-display text-2xl font-extrabold text-amber-600 mt-3">
                KES {{ number_format($commissions['pending'], 2) }}
            </p>

            <p class="text-[10px] text-muted mt-2">
                Awaiting payment
            </p>
        </div>

    </div>

    {{-- Commission breakdown --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="bg-white border border-line rounded-2xl p-5 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-muted">Paid Commissions</p>
                <p class="text-xl font-extrabold text-emerald-600 mt-2">
                    KES {{ number_format($commissions['paid'], 2) }}
                </p>
            </div>

            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                ✓
            </div>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-muted">Active Events</p>
                <p class="text-xl font-extrabold mt-2">
                    {{ $activeEvents->count() }}
                </p>
            </div>

            <div class="w-10 h-10 rounded-xl bg-soft flex items-center justify-center">
                ◫
            </div>
        </div>

    </div>

    {{-- Active Events --}}
    <section class="space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-base font-extrabold">
                    My Active Events
                </h2>

                <p class="text-xs text-muted mt-1">
                    Track the events you're currently promoting.
                </p>
            </div>

            <a href="{{ route('marketer.events.index') }}"
               class="text-xs font-bold text-accent hover:underline">
                View all →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @forelse($activeEvents->take(4) as $event)

                <div class="bg-white border border-line rounded-2xl overflow-hidden">

                    @if($event->banner_url)
                        <img src="{{ $event->banner_url }}"
                             alt="{{ $event->title }}"
                             class="w-full h-36 object-cover">
                    @else
                        <div class="h-24 bg-soft"></div>
                    @endif

                    <div class="p-5 space-y-4">

                        <div>
                            <h3 class="font-bold text-sm">
                                {{ $event->title }}
                            </h3>

                            <p class="text-[11px] text-muted mt-1">
                                {{ $event->venue_name }}
                                ·
                                {{ $event->start_date?->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-3 gap-2">

                            <div>
                                <p class="text-[10px] text-muted">Tickets</p>
                                <p class="font-extrabold text-sm">
                                    {{ $event->tickets_sold }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[10px] text-muted">Revenue</p>
                                <p class="font-extrabold text-sm">
                                    {{ number_format($event->revenue, 0) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[10px] text-muted">Commission</p>
                                <p class="font-extrabold text-sm text-emerald-600">
                                    {{ number_format($event->commission_earned, 0) }}
                                </p>
                            </div>

                        </div>

                        <a href="{{ route('marketer.events.show', $event) }}"
                           class="block text-center py-2.5 rounded-xl bg-soft hover:bg-line text-xs font-bold transition">
                            View Performance →
                        </a>

                    </div>
                </div>

            @empty

                <div class="col-span-full bg-white border border-line rounded-2xl p-10 text-center">

                    <h3 class="font-bold text-sm">
                        No active events yet
                    </h3>

                    <p class="text-xs text-muted mt-2">
                        Once an organizer approves your application, your assigned events will appear here.
                    </p>

                    <a href="/events"
                       class="inline-block mt-4 px-4 py-2 bg-accent text-white rounded-xl text-xs font-bold">
                        Explore Events
                    </a>

                </div>

            @endforelse

        </div>

    </section>

    {{-- Recent Sales --}}
    <section class="bg-white border border-line rounded-2xl overflow-hidden">

        <div class="p-5 border-b border-line flex items-center justify-between">
            <div>
                <h2 class="font-display font-extrabold text-sm">
                    Recent Sales
                </h2>

                <p class="text-xs text-muted mt-1">
                    Your latest successful ticket sales.
                </p>
            </div>

            <a href="{{ route('marketer.sales.index') }}"
               class="text-xs font-bold text-accent">
                View all →
            </a>
        </div>

        @forelse($recentOrders as $order)

            <div class="p-5 border-b border-line last:border-0 flex items-center justify-between gap-3">

                <div class="min-w-0">
                    <p class="text-xs font-bold truncate">
                        {{ $order->event->title ?? 'Event' }}
                    </p>

                    <p class="text-[10px] text-muted mt-1">
                        Order #{{ $order->order_number }}
                        · {{ $order->created_at->format('M d, Y') }}
                    </p>
                </div>

                <div class="text-right shrink-0">
                    <p class="text-xs font-extrabold">
                        KES {{ number_format($order->total_amount, 2) }}
                    </p>

                    <span class="text-[10px] font-bold text-emerald-600">
                        Paid
                    </span>
                </div>

            </div>

        @empty

            <div class="p-8 text-center text-xs text-muted">
                Your sales will appear here.
            </div>

        @endforelse

    </section>

</div>

@endsection
