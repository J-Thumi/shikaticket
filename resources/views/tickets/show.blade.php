@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
    <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-muted hover:text-ink mb-6 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Back to Wallet</span>
    </a>

    <!-- Ticket Pass Card -->
    <div class="bg-white border border-line rounded-3xl overflow-hidden shadow-sm">
        <!-- Event Banner Header -->
        <div class="bg-dark text-white p-6 border-b border-line">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-accent bg-accent/10 px-2.5 py-1 rounded-full border border-accent/20 inline-block mb-3">
                {{ $ticket->ticketType->name ?? 'Standard Pass' }}
            </span>
            <h1 class="font-display text-2xl font-extrabold tracking-tight mb-2">
                {{ $ticket->order->event->title ?? 'Event Entry Pass' }}
            </h1>
            <p class="text-xs text-[#929298]">
                Order #{{ $ticket->order->order_number }}
            </p>
        </div>

        <!-- Ticket QR Viewport -->
        <div class="p-8 text-center bg-paper border-b border-line">
            <div class="bg-white p-6 rounded-2xl border border-line inline-block shadow-sm mb-4">
                <div id="qrcode" class="flex justify-center"></div>
            </div>
            
            <p class="font-mono text-xs font-extrabold tracking-wider text-ink mb-1">
                {{ $ticket->ticket_code }}
            </p>
            <p class="text-[11px] text-muted">
                Present this QR code at the gate scanner to verify entry.
            </p>
        </div>

        <!-- Attendee Details -->
        <div class="p-6 space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-4 pb-4 border-b border-line">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-muted block mb-0.5">Attendee</span>
                    <span class="font-bold text-ink">{{ $ticket->order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-muted block mb-0.5">Status</span>
                    <span class="font-bold {{ $ticket->status === 'used' ? 'text-rose-600' : 'text-emerald-600' }}">
                        {{ strtoupper($ticket->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-muted block mb-0.5">Email</span>
                    <span class="font-bold text-ink truncate block">{{ $ticket->order->customer_email }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-muted block mb-0.5">Phone</span>
                    <span class="font-bold text-ink">{{ $ticket->order->customer_phone }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EasyQRCode JS Engine -->
<script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.4.1/dist/easy.qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: JSON.stringify({!! $qrPayload !!}),
        width: 180,
        height: 180,
        colorDark: "#121214",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
@endsection