
@extends('layouts.marketer')

@section('title', 'My Sales')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="font-display text-2xl font-extrabold">My Sales</h1>
        <p class="text-xs text-muted mt-2">
            Track all successful orders attributed to your referrals.
        </p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">Orders</p>
            <p class="text-2xl font-extrabold mt-2">
                {{ $allOrders->count() }}
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">Revenue</p>
            <p class="text-xl font-extrabold mt-2">
                KES {{ number_format($allOrders->sum('total_amount'), 2) }}
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">Commission</p>
            <p class="text-xl font-extrabold text-emerald-600 mt-2">
                KES {{ number_format($commissions['earned'], 2) }}
            </p>
        </div>

    </div>

    {{-- Orders table --}}
    <div class="bg-white border border-line rounded-2xl overflow-hidden">

        <div class="p-5 border-b border-line">
            <h2 class="font-bold text-sm">Successful Orders</h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-paper text-[10px] uppercase text-muted">
                    <tr>
                        <th class="p-4">Order</th>
                        <th class="p-4">Event</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Tickets</th>
                        <th class="p-4">Revenue</th>
                        <th class="p-4">Commission</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line">

                    @forelse($orders as $order)

                        <tr class="text-xs">

                            <td class="p-4 font-bold">
                                #{{ $order->order_number }}
                            </td>

                            <td class="p-4">
                                {{ $order->event->title ?? 'Deleted event' }}
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
                                KES {{ number_format(
                                    $order->marketer->calculateCommission((float) $order->total_amount),
                                    2
                                ) }}
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold">
                                    Paid
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="p-10 text-center text-xs text-muted">
                                You haven't generated any sales yet.
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
