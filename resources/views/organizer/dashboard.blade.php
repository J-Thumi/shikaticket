@extends('layouts.organizer')

@section('content')

<div class="space-y-8">

    {{-- ============================================================
        HEADER
    ============================================================= --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink">
                Organizer Dashboard
            </h1>

            <p class="text-xs text-muted mt-1">
                Monitor sales, tickets, events, check-ins, and marketer commissions.
            </p>
        </div>

        <div class="flex items-center gap-2">

            <a
                href="{{ route('organizer.marketers.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-line bg-white hover:bg-soft text-ink text-xs font-bold transition"
            >
                Manage Marketers
            </a>

            <a
                href="{{ route('organizer.events.create') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-accent hover:bg-accent-dark text-white text-xs font-bold transition shadow-sm hover:-translate-y-0.5"
            >
                <span>+ Create New Event</span>
            </a>

        </div>

    </div>


    {{-- ============================================================
        PRIMARY KPI CARDS
    ============================================================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">


        {{-- Gross Revenue --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Gross Revenue
            </span>

            <div class="font-display text-2xl font-extrabold text-ink mt-2">
                KES {{ number_format($totalRevenue ?? 0, 0) }}
            </div>

            <p class="text-[11px] font-medium text-emerald-600 mt-1">
                Net: KES {{ number_format($netRevenue ?? ($totalRevenue ?? 0), 0) }}
            </p>

        </div>


        {{-- Tickets Sold --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <div class="flex justify-between items-center">

                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Tickets Sold
                </span>

                <span class="text-[10px] font-extrabold text-accent">
                    {{ $capacityPercentage ?? 0 }}% Sold
                </span>

            </div>

            <div class="font-display text-2xl font-extrabold text-ink mt-2">

                {{ number_format($totalTicketsSold ?? 0) }}

                <span class="text-xs font-normal text-muted">
                    /
                    {{ number_format($totalCapacity ?? ($totalTicketsSold ?? 0)) }}
                </span>

            </div>

            <div class="w-full bg-paper rounded-full h-1.5 overflow-hidden border border-line mt-2">

                <div
                    class="bg-accent h-1.5 rounded-full"
                    style="width: {{ min($capacityPercentage ?? 0, 100) }}%"
                ></div>

            </div>

        </div>


        {{-- Active Holds --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Active Cart Holds
            </span>

            <div class="font-display text-2xl font-extrabold text-amber-600 mt-2">

                {{ number_format($activeHoldsCount ?? 0) }}

                <span class="text-xs font-normal text-muted">
                    tickets
                </span>

            </div>

            <p class="text-[11px] text-muted font-medium mt-1">
                10-minute hold window
            </p>

        </div>


        {{-- Check-in Rate --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Check-In Progress
            </span>

            <div class="font-display text-2xl font-extrabold text-ink mt-2">
                {{ $checkInRate ?? 0 }}%
            </div>

            <p class="text-[11px] text-muted font-medium mt-1">
                {{ number_format($checkedInCount ?? 0) }} checked in
            </p>

        </div>

    </div>


    {{-- ============================================================
        FINANCIAL / COMMISSION OVERVIEW
    ============================================================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">


        {{-- Outstanding Commission --}}
        <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Unpaid Commissions
                </span>

                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-50 text-amber-600">
                    !
                </span>

            </div>

            <div class="font-display text-2xl font-extrabold text-amber-600 mt-2">
                KES {{ number_format($unpaidCommissionTotal ?? 0, 2) }}
            </div>

            <p class="text-[11px] text-muted mt-1">
                Outstanding marketer liability
            </p>

        </div>


        {{-- Paid Commission --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Commissions Paid
                </span>

                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600">
                    ✓
                </span>

            </div>

            <div class="font-display text-2xl font-extrabold text-emerald-600 mt-2">
                KES {{ number_format($paidCommissionTotal ?? 0, 2) }}
            </div>

            <p class="text-[11px] text-muted mt-1">
                Total commissions settled
            </p>

        </div>


        {{-- Marketers --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Active Marketers
            </span>

            <div class="font-display text-2xl font-extrabold text-ink mt-2">
                {{ number_format($activeMarketersCount ?? 0) }}
            </div>

            <p class="text-[11px] text-muted mt-1">
                {{ number_format($marketersWithUnpaidCommission ?? 0) }}
                with unpaid commissions
            </p>

        </div>


        {{-- Average Order --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Average Order Value
            </span>

            <div class="font-display text-2xl font-extrabold text-ink mt-2">
                KES {{ number_format($avgOrderValue ?? 0, 0) }}
            </div>

            <p class="text-[11px] text-muted mt-1">
                Average paid order
            </p>

        </div>

    </div>


    {{-- ============================================================
        SALES INSIGHTS + TOP EVENTS
    ============================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


        {{-- Sales Insights --}}
        <div class="bg-white border border-line rounded-2xl p-6 shadow-sm">

            <h3 class="font-display text-base font-extrabold text-ink">
                Sales Insights
            </h3>

            <div class="space-y-3 divide-y divide-line text-xs mt-3">

                <div class="flex justify-between items-center pt-2">

                    <span class="text-muted font-medium">
                        Average Order Value
                    </span>

                    <span class="font-bold text-ink">
                        KES {{ number_format($avgOrderValue ?? 0, 0) }}
                    </span>

                </div>


                <div class="flex justify-between items-center pt-3">

                    <span class="text-muted font-medium">
                        Published Events
                    </span>

                    <span class="font-bold text-ink">
                        {{ number_format($eventsCount ?? 0) }}
                    </span>

                </div>


                <div class="flex justify-between items-center pt-3">

                    <span class="text-muted font-medium">
                        Active Ticket Tiers
                    </span>

                    <span class="font-bold text-ink">
                        {{ number_format($activeTiersCount ?? 0) }}
                    </span>

                </div>


                <div class="flex justify-between items-center pt-3">

                    <span class="text-muted font-medium">
                        Total Orders
                    </span>

                    <span class="font-bold text-ink">
                        {{ number_format($totalOrders ?? 0) }}
                    </span>

                </div>


                <div class="flex justify-between items-center pt-3">

                    <span class="text-muted font-medium">
                        Total Customers
                    </span>

                    <span class="font-bold text-ink">
                        {{ number_format($totalCustomers ?? 0) }}
                    </span>

                </div>

            </div>

        </div>


        {{-- Top Events --}}
        <div class="lg:col-span-2 bg-white border border-line rounded-2xl p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="font-display text-base font-extrabold text-ink">
                        Top Performing Events
                    </h3>

                    <p class="text-[11px] text-muted mt-0.5">
                        Events generating the most ticket revenue.
                    </p>

                </div>

                <a
                    href="{{ route('organizer.events.index') }}"
                    class="text-xs font-bold text-accent hover:underline"
                >
                    View All →
                </a>

            </div>


            @if(empty($topEvents) || $topEvents->isEmpty())

                <p class="text-xs font-medium text-muted py-8 text-center">
                    No sales data available yet.
                </p>

            @else

                <div class="space-y-3 mt-5">

                    @foreach($topEvents as $topEvent)

                        <div class="p-3.5 bg-paper rounded-xl border border-line flex items-center justify-between gap-4 text-xs">

                            <div class="space-y-0.5 min-w-0">

                                <span class="font-bold text-ink block truncate">
                                    {{ $topEvent->title }}
                                </span>

                                <span class="text-[11px] text-muted">
                                    {{ number_format($topEvent->tickets_sold_count ?? 0) }}
                                    tickets sold
                                </span>

                            </div>

                            <span class="font-extrabold text-ink shrink-0">
                                KES {{ number_format($topEvent->total_revenue ?? 0, 0) }}
                            </span>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
        UNPAID MARKETER COMMISSIONS
    ============================================================= --}}
    <div class="bg-white border border-line rounded-2xl overflow-hidden shadow-sm">

        <div class="p-6 border-b border-line">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                <div>

                    <h2 class="font-display text-lg font-extrabold text-ink">
                        Unpaid Marketer Commissions
                    </h2>

                    <p class="text-xs text-muted mt-1">
                        Marketers who have earned commissions that have not yet been settled.
                    </p>

                </div>

                <div class="flex items-center gap-3">

                    <div class="text-right">

                        <p class="text-[10px] uppercase tracking-wider font-bold text-muted">
                            Outstanding
                        </p>

                        <p class="font-display text-lg font-extrabold text-amber-600">
                            KES {{ number_format($unpaidCommissionTotal ?? 0, 2) }}
                        </p>

                    </div>

                    <a
                        href="{{ route('organizer.marketers.index') }}"
                        class="inline-flex items-center px-3 py-2 rounded-lg border border-line bg-white hover:bg-soft text-[10px] font-bold text-ink transition"
                    >
                        All Marketers
                    </a>

                </div>

            </div>

        </div>


        @if(empty($unpaidMarketers) || $unpaidMarketers->isEmpty())

            <div class="p-12 text-center">

                <div class="mx-auto w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">

                    <svg
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </div>

                <p class="text-sm font-bold text-ink mt-4">
                    All commissions are settled
                </p>

                <p class="text-xs text-muted mt-1">
                    There are currently no outstanding marketer commissions.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-soft border-b border-line">

                        <tr class="text-left text-[10px] uppercase tracking-wider text-muted">

                            <th class="px-6 py-4 font-extrabold">
                                Marketer
                            </th>

                            <th class="px-6 py-4 font-extrabold">
                                Referral Code
                            </th>

                            <th class="px-6 py-4 font-extrabold">
                                Sales
                            </th>

                            <th class="px-6 py-4 font-extrabold">
                                Revenue
                            </th>

                            <th class="px-6 py-4 font-extrabold">
                                Commission
                            </th>

                            <th class="px-6 py-4 font-extrabold">
                                Status
                            </th>

                            <th class="px-6 py-4 font-extrabold text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-line">

                        @foreach($unpaidMarketers as $marketer)

                            <tr class="hover:bg-soft/40 transition">


                                {{-- Marketer --}}
                                <td class="px-6 py-4">

                                    <div>

                                        <p class="font-bold text-ink text-xs">
                                            {{ $marketer->name }}
                                        </p>

                                        <p class="text-[11px] text-muted mt-0.5">
                                            {{ $marketer->email ?: ($marketer->phone ?: 'No contact') }}
                                        </p>

                                    </div>

                                </td>


                                {{-- Referral --}}
                                <td class="px-6 py-4">

                                    <code class="px-2 py-1 rounded-lg bg-soft border border-line text-[10px] font-bold text-accent">
                                        {{ $marketer->referral_code }}
                                    </code>

                                </td>


                                {{-- Sales --}}
                                <td class="px-6 py-4">

                                    <span class="font-bold text-xs text-ink">
                                        {{ number_format($marketer->paid_orders_count ?? 0) }}
                                    </span>

                                    <span class="block text-[10px] text-muted">
                                        paid orders
                                    </span>

                                </td>


                                {{-- Revenue --}}
                                <td class="px-6 py-4">

                                    <span class="font-extrabold text-xs text-ink">
                                        KES {{ number_format($marketer->revenue_generated ?? 0, 0) }}
                                    </span>

                                </td>


                                {{-- Commission --}}
                                <td class="px-6 py-4">

                                    <span class="font-display font-extrabold text-sm text-amber-600">
                                        KES {{ number_format($marketer->unpaid_commission ?? 0, 2) }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 text-[10px] font-bold rounded-lg bg-amber-50 text-amber-800 border border-amber-200">

                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>

                                        Unpaid

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        <a
                                            href="{{ route('organizer.marketers.show', $marketer) }}"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-line bg-white hover:bg-soft text-[10px] font-bold text-ink transition"
                                        >
                                            View
                                        </a>


                                        @if(($marketer->unpaid_commission ?? 0) > 0)

                                            <a
                                                href="{{ route('organizer.marketers.show', $marketer) }}"
                                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-accent hover:bg-accent-dark text-white text-[10px] font-bold transition"
                                            >

                                                <svg
                                                    class="w-3 h-3"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                                    />
                                                </svg>

                                                Pay

                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- ============================================================
        RECENT SALES
    ============================================================= --}}
    <div class="bg-white border border-line rounded-2xl overflow-hidden shadow-sm">

        <div class="p-6 sm:p-8 border-b border-line">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-display text-lg font-bold text-ink">
                        Recent Ticket Orders
                    </h2>

                    <p class="text-xs text-muted mt-1">
                        Latest ticket purchases across your events.
                    </p>

                </div>

                <a
                    href="#"
                    class="text-xs font-bold text-accent hover:underline"
                >
                    View All →
                </a>

            </div>

        </div>


        @if($recentOrders->isEmpty())

            <div class="p-8 text-center bg-paper rounded-xl border border-line m-6">

                <p class="text-xs font-medium text-muted">
                    No transactions recorded yet.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-left text-xs text-ink">

                    <thead class="bg-paper text-muted text-[10px] font-bold uppercase tracking-wider border-b border-line">

                        <tr>

                            <th class="px-6 py-4">
                                Order Number
                            </th>

                            <th class="px-6 py-4">
                                Customer
                            </th>

                            <th class="px-6 py-4">
                                Event
                            </th>

                            <th class="px-6 py-4">
                                Amount
                            </th>

                            <th class="px-6 py-4">
                                Status
                            </th>

                            <th class="px-6 py-4">
                                Date
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-line">

                        @foreach($recentOrders as $order)

                            <tr class="hover:bg-paper/60 transition-colors">

                                <td class="px-6 py-4 font-mono font-bold text-ink">
                                    {{ $order->order_number }}
                                </td>

                                <td class="px-6 py-4 font-medium">
                                    {{ $order->customer_name }}
                                </td>

                                <td class="px-6 py-4 font-medium text-ink">
                                    {{ $order->event->title ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 font-bold text-ink">
                                    KES {{ number_format($order->total_amount, 0) }}
                                </td>

                                <td class="px-6 py-4">

                                    @if($order->status === 'paid')

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">

                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                                            PAID

                                        </span>

                                    @elseif($order->status === 'pending')

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg bg-amber-50 text-amber-800 border border-amber-200">

                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>

                                            PENDING

                                        </span>

                                    @elseif($order->status === 'failed')

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg bg-rose-50 text-rose-800 border border-rose-200">

                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>

                                            FAILED

                                        </span>

                                    @elseif($order->status === 'refunded')

                                        <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-lg bg-violet-50 text-violet-800 border border-violet-200">
                                            REFUNDED
                                        </span>

                                    @else

                                        <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-lg bg-gray-50 text-gray-700 border border-gray-200">
                                            {{ strtoupper($order->status) }}
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4 text-muted font-medium text-[11px]">

                                    {{ $order->created_at
                                        ? $order->created_at->format('M d, Y H:i')
                                        : 'N/A'
                                    }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection