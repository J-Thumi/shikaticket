@extends('layouts.organizer')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink">Organizer Dashboard</h1>
            <p class="text-xs text-muted mt-1">Manage live performance metrics, checkouts, and ticket inventories.</p>
        </div>
        <div>
            <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-accent hover:bg-accent-dark text-white text-xs font-bold transition shadow-sm hover:-translate-y-0.5">
                <span>+ Create New Event</span>
            </a>
        </div>
    </div>

    <!-- Primary Financial & Inventory KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Gross Revenue -->
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">Gross Revenue</span>
            <div class="font-display text-2xl font-extrabold text-ink">
                KES {{ number_format($totalRevenue, 0) }}
            </div>
            <p class="text-[11px] font-medium text-emerald-600">
                Net: KES {{ number_format($netRevenue ?? ($totalRevenue * 0.9), 0) }}
            </p>
        </div>

        <!-- Total Tickets & Capacity -->
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">Tickets Sold</span>
                <span class="text-[10px] font-extrabold text-accent">{{ $capacityPercentage ?? 0 }}% Sold</span>
            </div>
            <div class="font-display text-2xl font-extrabold text-ink">
                {{ number_format($totalTicketsSold) }} <span class="text-xs font-normal text-muted">/ {{ number_format($totalCapacity ?? $totalTicketsSold) }}</span>
            </div>
            <div class="w-full bg-paper rounded-full h-1.5 overflow-hidden border border-line">
                <div class="bg-accent h-1.5 rounded-full" style="width: {{ min($capacityPercentage ?? 0, 100) }}%"></div>
            </div>
        </div>

        <!-- Active Checkout Holds -->
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">Active Cart Holds</span>
            <div class="font-display text-2xl font-extrabold text-amber-600">
                {{ number_format($activeHoldsCount ?? 0) }} <span class="text-xs font-normal text-muted">tickets</span>
            </div>
            <p class="text-[11px] text-muted font-medium">10-minute hold window</p>
        </div>

        <!-- Gate Check-in Rate -->
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">Check-In Progress</span>
            <div class="font-display text-2xl font-extrabold text-ink">
                {{ $checkInRate ?? 0 }}%
            </div>
            <p class="text-[11px] text-muted font-medium">
                {{ number_format($checkedInCount ?? 0) }} checked in
            </p>
        </div>
    </div>

    <!-- Secondary Metrics & Top Performers Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Performance Highlights -->
        <div class="bg-white border border-line rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="font-display text-base font-extrabold text-ink">Sales Insights</h3>
            <div class="space-y-3 divide-y divide-line text-xs">
                <div class="flex justify-between items-center pt-2">
                    <span class="text-muted font-medium">Average Order Value</span>
                    <span class="font-bold text-ink">KES {{ number_format($avgOrderValue ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center pt-3">
                    <span class="text-muted font-medium">Published Events</span>
                    <span class="font-bold text-ink">{{ $eventsCount }} Events</span>
                </div>
                <div class="flex justify-between items-center pt-3">
                    <span class="text-muted font-medium">Active Ticket Tiers</span>
                    <span class="font-bold text-ink">{{ $activeTiersCount ?? 0 }} Tiers</span>
                </div>
            </div>
        </div>

        <!-- Top Performing Events -->
        <div class="lg:col-span-2 bg-white border border-line rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-display text-base font-extrabold text-ink">Top Performing Events</h3>
                <a href="{{ route('organizer.events.index') }}" class="text-xs font-bold text-accent hover:underline">View All →</a>
            </div>

            @if(empty($topEvents) || $topEvents->isEmpty())
                <p class="text-xs font-medium text-muted py-4">No sales data available yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($topEvents as $topEvent)
                        <div class="p-3.5 bg-paper rounded-xl border border-line flex items-center justify-between text-xs">
                            <div class="space-y-0.5">
                                <span class="font-bold text-ink block truncate max-w-xs">{{ $topEvent->title }}</span>
                                <span class="text-[11px] text-muted">{{ $topEvent->tickets_sold_count }} tickets sold</span>
                            </div>
                            <span class="font-extrabold text-ink">KES {{ number_format($topEvent->total_revenue, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="bg-white border border-line rounded-2xl p-6 sm:p-8 shadow-sm">
        <h2 class="font-display text-lg font-bold text-ink mb-4">Recent Ticket Orders</h2>
        @if($recentOrders->isEmpty())
            <div class="p-8 text-center bg-paper rounded-xl border border-line">
                <p class="text-xs font-medium text-muted">No transactions recorded yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-ink">
                    <thead class="bg-paper text-muted text-xs font-bold uppercase tracking-wider border-b border-line">
                        <tr>
                            <th class="px-4 py-3.5 rounded-l-lg">Order Number</th>
                            <th class="px-4 py-3.5">Customer</th>
                            <th class="px-4 py-3.5">Event</th>
                            <th class="px-4 py-3.5">Amount</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 rounded-r-lg">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-paper/60 transition-colors">
                                <td class="px-4 py-4 font-mono font-bold text-ink">{{ $order->order_number }}</td>
                                <td class="px-4 py-4 font-medium">{{ $order->customer_name }}</td>
                                <td class="px-4 py-4 font-medium text-ink">{{ $order->event->title }}</td>
                                <td class="px-4 py-4 font-bold text-ink">KES {{ number_format($order->total_amount, 0) }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-muted font-medium text-[11px]">{{ $order->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection