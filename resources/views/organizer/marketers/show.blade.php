@extends('layouts.organizer')

@section('content')
<div class="space-y-8" x-data="{ copiedUrl: null, copiedCode: false }">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">

        <div class="space-y-2">

            <a
                href="{{ route('organizer.marketers.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-muted hover:text-ink transition"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>

                Back to Marketers
            </a>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink leading-tight">
                    {{ $marketer->name }}
                </h1>

                @if($marketer->is_active)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg bg-rose-50 text-rose-800 border border-rose-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Inactive
                    </span>
                @endif

            </div>

            <p class="text-xs font-medium text-muted">
                {{ $marketer->email ?: 'No email' }}
                <span class="mx-1">•</span>
                {{ $marketer->phone ?: 'No phone' }}
            </p>

        </div>

        <div class="flex items-center gap-2">

            <a
                href="{{ route('organizer.marketers.edit', $marketer) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-line bg-white hover:bg-soft text-ink text-xs font-bold transition shadow-sm"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>

                Edit Details
            </a>

        </div>
    </div>


    {{-- Flash Message --}}
    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800">

            <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-100">
                ✓
            </div>

            {{ session('status') }}

        </div>
    @endif


    {{-- Performance Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

        {{-- Referral Code --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Referral Code
                </span>

                <span class="text-accent">
                    #
                </span>
            </div>

            <div class="flex items-center gap-2 mt-3">

                <code class="px-2.5 py-1.5 rounded-lg bg-soft border border-line text-xs font-extrabold text-accent">
                    {{ $marketer->referral_code }}
                </code>

                <button
                    type="button"
                    @click="
                        navigator.clipboard.writeText('{{ $marketer->referral_code }}');
                        copiedCode = true;
                        setTimeout(() => copiedCode = false, 2000)
                    "
                    class="p-1.5 rounded-lg border border-line bg-white hover:bg-soft transition"
                    title="Copy referral code"
                >
                    <template x-if="!copiedCode">
                        <svg class="w-3.5 h-3.5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V6a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2h-1M6 8H4a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-1"/>
                        </svg>
                    </template>

                    <template x-if="copiedCode">
                        <span class="text-emerald-600 text-[10px] font-bold">
                            ✓
                        </span>
                    </template>
                </button>

            </div>

            <p class="text-[10px] text-muted mt-2">
                Unique tracking identifier
            </p>

        </div>


        {{-- Revenue --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Revenue Generated
            </span>

            <p class="font-display text-2xl font-extrabold text-ink mt-2">
                KES {{ number_format($totalRevenue, 0) }}
            </p>

            <p class="text-[10px] text-muted mt-1">
                From paid orders
            </p>

        </div>


        {{-- Orders --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Paid Orders
            </span>

            <p class="font-display text-2xl font-extrabold text-emerald-600 mt-2">
                {{ number_format($paidOrdersCount) }}
            </p>

            <p class="text-[10px] text-muted mt-1">
                Successful conversions
            </p>

        </div>


        {{-- Tickets --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                Tickets Sold
            </span>

            <p class="font-display text-2xl font-extrabold text-ink mt-2">
                {{ number_format($ticketsSold) }}
            </p>

            <p class="text-[10px] text-muted mt-1">
                Individual admissions
            </p>

        </div>


        {{-- Commission --}}
        <div class="bg-white border border-line rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Commission Earned
                </span>

                @if($marketer->commission_type === 'fixed')
                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-soft text-muted">
                        Fixed
                    </span>
                @else
                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-soft text-muted">
                        {{ $marketer->commission_percent }}%
                    </span>
                @endif

            </div>

            <p class="font-display text-2xl font-extrabold text-accent mt-2">
                KES {{ number_format($commissionEarned, 2) }}
            </p>

            <p class="text-[10px] text-muted mt-1">
                Based on paid sales
            </p>

        </div>

    </div>


    {{-- Commission Configuration --}}
    <div class="bg-white border border-line rounded-2xl p-6 shadow-sm">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

            <div>
                <h2 class="font-display text-base font-extrabold text-ink">
                    Commission Configuration
                </h2>

                <p class="text-xs text-muted mt-1">
                    Current commission arrangement for this marketer.
                </p>
            </div>

            <a
                href="{{ route('organizer.marketers.edit', $marketer) }}"
                class="text-xs font-bold text-accent hover:text-accent-dark transition"
            >
                Change commission
            </a>

        </div>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="rounded-xl bg-paper border border-line p-4">

                <p class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Commission Type
                </p>

                <p class="text-sm font-extrabold text-ink mt-1">
                    {{ $marketer->commission_type === 'fixed' ? 'Fixed per ticket' : 'Percentage of sale' }}
                </p>

            </div>

            <div class="rounded-xl bg-paper border border-line p-4">

                <p class="text-[10px] font-bold uppercase tracking-wider text-muted">
                    Commission Rate
                </p>

                <p class="text-sm font-extrabold text-accent mt-1">

                    @if($marketer->commission_type === 'fixed')
                        KES {{ number_format($marketer->fixed_commission, 2) }}
                        <span class="text-[11px] text-muted font-medium">
                            / ticket
                        </span>
                    @else
                        {{ number_format($marketer->commission_percent, 2) }}%
                    @endif

                </p>

            </div>

        </div>

    </div>


    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Orders --}}
        <div class="lg:col-span-2">

            <div class="bg-white border border-line rounded-2xl overflow-hidden shadow-sm">

                <div class="p-6 border-b border-line">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <h2 class="font-display text-base font-extrabold text-ink">
                                Attributed Orders
                            </h2>

                            <p class="text-xs text-muted font-medium mt-1">
                                Purchases attributed to this marketer's referral link.
                            </p>
                        </div>

                        <span class="shrink-0 text-[10px] font-bold uppercase tracking-wider text-muted">
                            {{ $orders->total() }} Orders
                        </span>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-soft border-b border-line">

                            <tr class="text-left text-[10px] uppercase tracking-wider text-muted">

                                <th class="px-6 py-4 font-extrabold">
                                    Order
                                </th>

                                <th class="px-6 py-4 font-extrabold">
                                    Event
                                </th>

                                <th class="px-6 py-4 font-extrabold">
                                    Customer
                                </th>

                                <th class="px-6 py-4 font-extrabold">
                                    Amount
                                </th>

                                <th class="px-6 py-4 font-extrabold">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-line">

                            @forelse($orders as $order)

                                <tr class="hover:bg-soft/40 transition">

                                    <td class="px-6 py-4">

                                        <span class="font-mono font-bold text-xs text-ink">
                                            {{ $order->order_number }}
                                        </span>

                                        <span class="block text-[10px] text-muted mt-0.5">
                                            {{ $order->created_at->format('M d, Y · H:i') }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4">

                                        <p class="font-bold text-ink text-xs line-clamp-1">
                                            {{ $order->event->title ?? 'N/A' }}
                                        </p>

                                    </td>

                                    <td class="px-6 py-4">

                                        <p class="font-bold text-ink text-xs">
                                            {{ $order->customer_name }}
                                        </p>

                                        <p class="text-[11px] text-muted truncate max-w-[180px]">
                                            {{ $order->customer_email ?: 'No email' }}
                                        </p>

                                    </td>

                                    <td class="px-6 py-4">

                                        <span class="font-extrabold text-xs text-ink">
                                            {{ $order->currency }}
                                            {{ number_format($order->total_amount, 0) }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4">

                                        @if($order->status === 'paid')

                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Paid
                                            </span>

                                        @elseif($order->status === 'pending')

                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 text-[10px] font-bold rounded-lg bg-amber-50 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Pending
                                            </span>

                                        @elseif($order->status === 'failed')

                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 text-[10px] font-bold rounded-lg bg-rose-50 text-rose-800 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Failed
                                            </span>

                                        @elseif($order->status === 'refunded')

                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 text-[10px] font-bold rounded-lg bg-violet-50 text-violet-800 border border-violet-200">
                                                Refunded
                                            </span>

                                        @else

                                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold rounded-lg bg-gray-50 text-gray-700 border border-gray-200">
                                                {{ ucfirst($order->status) }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-14 text-center">

                                        <div class="flex flex-col items-center">

                                            <div class="w-10 h-10 rounded-xl bg-soft flex items-center justify-center text-muted mb-3">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                          d="M9 14l6-6m-6 0h6v6M5 5h14v14H5z"/>
                                                </svg>
                                            </div>

                                            <p class="text-xs font-bold text-ink">
                                                No attributed orders yet
                                            </p>

                                            <p class="text-[11px] text-muted mt-1">
                                                Sales made through this marketer's links will appear here.
                                            </p>

                                        </div>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if($orders->hasPages())

                    <div class="p-4 border-t border-line">
                        {{ $orders->links() }}
                    </div>

                @endif

            </div>

        </div>


        {{-- Events --}}
        <div>

            <div class="bg-white border border-line rounded-2xl p-6 shadow-sm">

                <div class="flex items-center justify-between pb-4 border-b border-line">

                    <div>
                        <h3 class="font-display text-base font-extrabold text-ink">
                            Assigned Events
                        </h3>

                        <p class="text-[11px] text-muted mt-0.5">
                            Referral links
                        </p>
                    </div>

                    <span class="text-xs font-bold text-muted">
                        {{ $marketer->events->count() }}
                    </span>

                </div>


                @if($marketer->events->isEmpty())

                    <div class="py-8 text-center">

                        <div class="inline-flex w-10 h-10 items-center justify-center rounded-xl bg-paper border border-line text-muted">
                            +
                        </div>

                        <p class="text-xs font-bold text-ink mt-3">
                            No assigned events
                        </p>

                        <p class="text-[11px] text-muted mt-1">
                            Assign an event to generate a referral link.
                        </p>

                    </div>

                @else

                    <div class="space-y-3 mt-4">

                        @foreach($marketer->events as $event)

                            @php
                                $referralUrl = $marketer->getReferralUrl($event);

                                $qrCodeSvg = base64_encode(
                                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                        ->size(300)
                                        ->margin(1)
                                        ->generate($referralUrl)
                                );

                                $qrDownloadData = 'data:image/svg+xml;base64,' . $qrCodeSvg;
                            @endphp

                            <div
                                class="p-4 bg-paper border border-line rounded-xl space-y-3"
                                x-data="{ showQrModal: false }"
                            >

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <p class="text-xs font-bold text-ink truncate">
                                            {{ $event->title }}
                                        </p>

                                        <p class="text-[10px] text-muted mt-1">
                                            {{ $event->marketer_paid_orders ?? 0 }}
                                            paid
                                            {{ ($event->marketer_paid_orders ?? 0) == 1 ? 'sale' : 'sales' }}
                                        </p>

                                    </div>

                                    <button
                                        type="button"
                                        @click="showQrModal = !showQrModal"
                                        class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg border border-line bg-white hover:bg-soft text-ink transition"
                                        title="View QR code"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </button>

                                </div>


                                {{-- Referral URL --}}
                                <div class="flex items-center gap-2">

                                    <input
                                        type="text"
                                        readonly
                                        value="{{ $referralUrl }}"
                                        class="min-w-0 flex-1 bg-white border border-line rounded-lg text-[10px] text-muted font-mono px-2.5 py-2 focus:ring-0 truncate"
                                    >

                                    <button
                                        type="button"
                                        @click="
                                            navigator.clipboard.writeText('{{ $referralUrl }}');
                                            copiedUrl = '{{ $event->id }}';
                                            setTimeout(() => copiedUrl = null, 2000)
                                        "
                                        class="shrink-0 px-2.5 py-2 text-[10px] font-bold rounded-lg border border-line bg-white hover:bg-soft text-ink transition"
                                    >
                                        <span x-show="copiedUrl !== '{{ $event->id }}'">
                                            Copy
                                        </span>

                                        <span
                                            x-show="copiedUrl === '{{ $event->id }}'"
                                            class="text-emerald-700"
                                        >
                                            Copied
                                        </span>
                                    </button>

                                </div>


                                {{-- QR --}}
                                <div
                                    x-show="showQrModal"
                                    x-collapse
                                    class="pt-3 border-t border-line"
                                >

                                    <div class="bg-white border border-line rounded-xl p-4 flex flex-col items-center">

                                        <img
                                            src="{{ $qrDownloadData }}"
                                            alt="QR code for {{ $event->title }}"
                                            class="w-36 h-36"
                                        >

                                        <p class="text-[10px] text-muted text-center mt-2">
                                            Scan to open this marketer's referral link.
                                        </p>

                                        <a
                                            href="{{ $qrDownloadData }}"
                                            download="QR-{{ Str::slug($event->title) }}-{{ $marketer->referral_code }}.svg"
                                            class="mt-3 inline-flex items-center justify-center gap-1.5 w-full px-3 py-2 rounded-lg bg-accent text-white text-[10px] font-bold hover:bg-accent-dark transition"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>

                                            Download QR Code
                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
@endsection