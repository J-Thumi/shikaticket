
@extends('layouts.marketer')

@section('title', $event->title)

@section('content')

@php
    $referralUrl = route('events.show', [
        'event' => $event->slug,
        'ref' => $marketer->referral_code,
    ]);
@endphp

<div class="space-y-6">

    <a href="{{ route('marketer.events.index') }}"
       class="text-xs font-bold text-muted hover:text-ink">
        ← Back to My Events
    </a>

    {{-- Event header --}}
    <div class="bg-white border border-line rounded-2xl overflow-hidden">

        @if($event->banner_url)
            <img src="{{ $event->banner_url }}"
                 alt="{{ $event->title }}"
                 class="w-full h-48 sm:h-64 object-cover">
        @endif

        <div class="p-6 space-y-2">

            <span class="text-[10px] font-bold uppercase tracking-wider text-accent">
                Event Performance
            </span>

            <h1 class="font-display text-2xl font-extrabold">
                {{ $event->title }}
            </h1>

            <p class="text-xs text-muted">
                {{ $event->venue_name }}
                · {{ $event->venue_address }}
            </p>

            <p class="text-xs text-muted">
                {{ $event->start_date?->format('M d, Y · h:i A') }}
                – {{ $event->end_date?->format('M d, Y · h:i A') }}
            </p>

        </div>
    </div>

    {{-- Performance statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">Tickets Sold</p>
            <p class="text-2xl font-extrabold mt-2">
                {{ number_format($ticketsSold) }}
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">Orders</p>
            <p class="text-2xl font-extrabold mt-2">
                {{ number_format($allOrders->count()) }}
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">Revenue Generated</p>
            <p class="text-xl font-extrabold mt-2">
                KES {{ number_format($totalRevenue, 2) }}
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">Commission Earned</p>
            <p class="text-xl font-extrabold text-emerald-600 mt-2">
                KES {{ number_format($commissions['earned'], 2) }}
            </p>
        </div>

    </div>

    {{-- Commission breakdown --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-4">

        <h2 class="font-bold text-sm">Commission Breakdown</h2>

        <div class="flex justify-between text-xs">
            <span class="text-muted">Total earned</span>
            <strong>KES {{ number_format($commissions['earned'], 2) }}</strong>
        </div>

        <div class="flex justify-between text-xs">
            <span class="text-muted">Already paid</span>
            <strong class="text-emerald-600">
                KES {{ number_format($commissions['paid'], 2) }}
            </strong>
        </div>

        <div class="flex justify-between text-xs">
            <span class="text-muted">Pending</span>
            <strong class="text-amber-600">
                KES {{ number_format($commissions['pending'], 2) }}
            </strong>
        </div>

    </div>

    {{-- Referral link --}}
    <div class="bg-white border border-line rounded-2xl p-5 space-y-3">

        <div>
            <h2 class="font-bold text-sm">Your Referral Link</h2>
            <p class="text-xs text-muted mt-1">
                Share this link to promote the event and earn commissions.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">

            <input readonly
                   value="{{ $referralUrl }}"
                   id="referral-link"
                   class="flex-1 min-w-0 bg-paper border border-line rounded-xl px-4 py-3 text-xs">

            <button type="button"
                    onclick="navigator.clipboard.writeText(document.getElementById('referral-link').value).then(() => { this.innerText = 'Copied!'; setTimeout(() => this.innerText = 'Copy Link', 2000) })"
                    class="px-5 py-3 bg-accent text-white rounded-xl text-xs font-bold">
                Copy Link
            </button>

        </div>

        <p class="text-[10px] text-muted">
            Referral code: {{ $marketer->referral_code }}
        </p>

    </div>

    {{-- Sales history --}}
    <div class="bg-white border border-line rounded-2xl overflow-hidden">

        <div class="p-5 border-b border-line">
            <h2 class="font-bold text-sm">Event Sales</h2>
            <p class="text-xs text-muted mt-1">
                Orders attributed to your referral code.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-paper text-[10px] uppercase text-muted">
                    <tr>
                        <th class="p-4">Order</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Tickets</th>
                        <th class="p-4">Amount</th>
                        <th class="p-4">Commission</th>
                        <th class="p-4">Date</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line">

                    @forelse($orders as $order)

                        <tr class="text-xs">

                            <td class="p-4 font-bold">
                                #{{ $order->order_number }}
                            </td>

                            <td class="p-4">
                                {{ $order->customer_name }}
                            </td>

                            <td class="p-4">
                                {{ $order->tickets_count }}
                            </td>

                            <td class="p-4 font-bold">
                                KES {{ number_format($order->total_amount, 2) }}
                            </td>

                            <td class="p-4 font-bold text-emerald-600">
                                KES {{ number_format($marketer->calculateCommission((float) $order->total_amount), 2) }}
                            </td>

                            <td class="p-4 text-muted whitespace-nowrap">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted">
                                No sales recorded for this event yet.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="p-5">
            {{ $orders->links() }}
        </div>

    </div>

</div>

@endsection
