
@extends('layouts.marketer')

@section('title', 'My Commissions')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="font-display text-2xl font-extrabold">
            My Commissions
        </h1>

        <p class="text-xs text-muted mt-2">
            Monitor commissions generated from your successful ticket sales.
        </p>
    </div>

    {{-- Commission summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">
                Total Earned
            </p>

            <p class="text-xl font-extrabold mt-3">
                KES {{ number_format($totals['earned'], 2) }}
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">
                Pending Payment
            </p>

            <p class="text-xl font-extrabold text-amber-600 mt-3">
                KES {{ number_format($totals['pending'], 2) }}
            </p>
        </div>

        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-[10px] uppercase font-bold text-muted">
                Already Paid
            </p>

            <p class="text-xl font-extrabold text-emerald-600 mt-3">
                KES {{ number_format($totals['paid'], 2) }}
            </p>
        </div>

    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2">

        @foreach(['all' => 'All', 'pending' => 'Pending', 'paid' => 'Paid'] as $key => $label)

            <a href="{{ route('marketer.commissions.index', ['status' => $key]) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold border transition
               {{ $status === $key ? 'bg-accent text-white border-accent' : 'bg-white border-line text-muted hover:bg-soft' }}">

                {{ $label }}

            </a>

        @endforeach

    </div>

    {{-- Commission table --}}
    <div class="bg-white border border-line rounded-2xl overflow-hidden">

        <div class="p-5 border-b border-line">
            <h2 class="font-bold text-sm">
                Commission Transactions
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-paper text-[10px] uppercase text-muted">
                    <tr>
                        <th class="p-4">Order</th>
                        <th class="p-4">Event</th>
                        <th class="p-4">Order Value</th>
                        <th class="p-4">Commission</th>
                        <th class="p-4">Status</th>
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
                                {{ $order->event->title ?? 'Deleted event' }}
                            </td>

                            <td class="p-4">
                                KES {{ number_format($order->total_amount, 2) }}
                            </td>

                            <td class="p-4 font-bold text-emerald-600">
                                KES {{ number_format($order->commission_amount, 2) }}
                            </td>

                            <td class="p-4">

                                @if($order->is_commission_paid)
                                    <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold">
                                        Paid
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-bold">
                                        Pending
                                    </span>
                                @endif

                            </td>

                            <td class="p-4 text-muted whitespace-nowrap">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="p-10 text-center text-xs text-muted">
                                No commission transactions found.
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
