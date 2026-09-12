@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
       SCREEN
    ========================================================== */

    .ticket-card {
        position: relative;
        overflow: hidden;
    }

    /* =========================================================
       PRINT / PDF
    ========================================================== */

    @page {
        size: A4;
        margin: 12mm;
    }

    @media print {

        html,
        body {
            width: 210mm;
            min-height: 297mm;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            color: #111827 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide application chrome */
        nav,
        header,
        footer,
        .no-print {
            display: none !important;
        }

        .print-container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Print header */
        .print-header {
            display: block !important;
            margin-bottom: 8mm !important;
            padding-bottom: 5mm !important;
            border-bottom: 2px solid #111827 !important;
        }

        /* Each ticket gets its own clean block */
        .ticket-card {
            width: 100% !important;
            min-height: 82mm;
            box-sizing: border-box;

            display: flex !important;
            flex-direction: row !important;
            align-items: stretch !important;

            margin: 0 0 10mm 0 !important;
            padding: 0 !important;

            border: 1.5px solid #111827 !important;
            border-radius: 5mm !important;

            background: #fff !important;
            box-shadow: none !important;

            overflow: hidden !important;

            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /*
         * Keep every ticket together.
         * If there are multiple tickets, let the browser naturally
         * continue onto the next page when necessary.
         */
        .ticket-card + .ticket-card {
            margin-top: 0 !important;
        }

        /* Left ticket information */
        .ticket-main {
            flex: 1 !important;
            padding: 8mm !important;
            min-width: 0 !important;
        }

        /* Right QR stub */
        .ticket-stub {
            width: 52mm !important;
            flex-shrink: 0 !important;

            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;

            padding: 6mm !important;

            border-left: 1.5px dashed #6b7280 !important;
            background: #f9fafb !important;
        }

        /* Perforation circles */
        .ticket-stub::before,
        .ticket-stub::after {
            content: "";
            position: absolute;
            left: calc(100% - 52mm);
            width: 7mm;
            height: 7mm;
            margin-left: -3.5mm;

            border: 1.5px solid #111827;
            border-radius: 50%;
            background: #fff;

            z-index: 5;
        }

        .ticket-stub::before {
            top: -3.5mm;
        }

        .ticket-stub::after {
            bottom: -3.5mm;
        }

        /* QR */
        .qr-box {
            width: 39mm !important;
            height: 39mm !important;

            padding: 3mm !important;

            display: flex !important;
            align-items: center !important;
            justify-content: center !important;

            border: 1px solid #d1d5db !important;
            border-radius: 3mm !important;
            background: #fff !important;

            box-sizing: border-box;
        }

        .qr-box img {
            width: 33mm !important;
            height: 33mm !important;

            max-width: none !important;
            max-height: none !important;

            display: block !important;
            mix-blend-mode: normal !important;
        }

        /* Don't print rounded web UI unnecessarily */
        .print-pill {
            border-radius: 2mm !important;
        }

        /* Hide mobile/screen-only content */
        .screen-only {
            display: none !important;
        }

        /* Avoid text clipping */
        .truncate {
            overflow: visible !important;
            text-overflow: clip !important;
            white-space: normal !important;
        }

        /* Remove Tailwind shadows */
        .shadow,
        .shadow-sm,
        .shadow-md,
        .shadow-lg,
        .shadow-xl {
            box-shadow: none !important;
        }

        /* Print footer */
        .print-footer {
            display: block !important;
            margin-top: 4mm !important;
            padding-top: 3mm !important;
            border-top: 1px solid #d1d5db !important;

            text-align: center !important;
            font-size: 8pt !important;
            color: #6b7280 !important;
        }
    }

    /* Hidden by default */
    .print-header,
    .print-footer {
        display: none;
    }
</style>

<div class="max-w-3xl mx-auto px-4 py-12 print-container">

```
{{-- =========================================================
     SCREEN HEADER
========================================================== --}}

<div class="text-center space-y-3 no-print mb-8">

    <div class="inline-flex items-center justify-center w-16 h-16
                bg-emerald-50 text-emerald-600 rounded-full
                border border-emerald-200 shadow-sm
                text-2xl font-bold">
        ✓
    </div>

    <div>
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">
            Payment Confirmed
        </span>

        <h1 class="font-display text-3xl font-extrabold text-ink mt-1">
            You're All Set!
        </h1>

        <p class="text-xs text-muted mt-1">
            Order
            <span class="font-mono font-bold text-ink">
                #{{ $order->order_number }}
            </span>
            • Present your QR ticket at the venue entry.
        </p>
    </div>

</div>


{{-- =========================================================
     PRINT HEADER
========================================================== --}}

<div class="print-header">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-end;
        gap:20px;
    ">

        <div>
            <div style="
                font-size:20pt;
                font-weight:900;
                letter-spacing:-0.5px;
                color:#111827;
            ">
                ShikaTicket
            </div>

            <div style="
                margin-top:1mm;
                font-size:8pt;
                color:#6b7280;
                text-transform:uppercase;
                letter-spacing:1.2px;
                font-weight:700;
            ">
                Official Event Admission Pass
            </div>
        </div>

        <div style="text-align:right;">

            <div style="
                font-size:7pt;
                color:#6b7280;
                text-transform:uppercase;
                letter-spacing:1px;
                font-weight:700;
            ">
                Order Reference
            </div>

            <div style="
                margin-top:1mm;
                font-size:11pt;
                font-family:monospace;
                font-weight:800;
                color:#111827;
            ">
                #{{ $order->order_number }}
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     TICKETS
========================================================== --}}

<div class="space-y-6">

    @foreach($order->tickets as $ticket)

        <div class="ticket-card">

            {{-- =================================================
                 MAIN TICKET AREA
            ================================================== --}}

            <div class="ticket-main">

                {{-- Ticket type + admission --}}
                <div style="
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    gap:10px;
                    margin-bottom:6mm;
                ">

                    <span
                        class="print-pill"
                        style="
                            display:inline-block;
                            background:#111827;
                            color:#fff;
                            padding:2mm 3mm;
                            border-radius:2mm;
                            font-size:7pt;
                            font-weight:800;
                            text-transform:uppercase;
                            letter-spacing:1px;
                        "
                    >
                        {{ $ticket->ticketType->name }}
                    </span>

                    <span style="
                        font-size:7pt;
                        font-weight:800;
                        color:#6b7280;
                        text-transform:uppercase;
                        letter-spacing:1px;
                    ">
                        ADMIT 1
                    </span>

                </div>


                {{-- Event title --}}
                <div style="margin-bottom:6mm;">

                    <div style="
                        font-size:7pt;
                        color:#6b7280;
                        font-weight:800;
                        text-transform:uppercase;
                        letter-spacing:1px;
                        margin-bottom:1.5mm;
                    ">
                        Event
                    </div>

                    <h2 style="
                        margin:0;
                        font-size:19pt;
                        line-height:1.15;
                        font-weight:900;
                        letter-spacing:-0.4px;
                        color:#111827;
                    ">
                        {{ $order->event->title }}
                    </h2>

                    <div style="
                        margin-top:2mm;
                        font-size:8pt;
                        font-weight:600;
                        color:#4b5563;
                    ">
                        {{ $order->event->venue_name }}
                    </div>

                </div>


                {{-- Event information --}}
                <div style="
                    display:grid;
                    grid-template-columns:1.2fr 1fr;
                    gap:5mm 8mm;
                    padding:5mm 0;
                    border-top:1px solid #e5e7eb;
                    border-bottom:1px solid #e5e7eb;
                ">

                    <div>

                        <div style="
                            font-size:6.5pt;
                            font-weight:800;
                            color:#9ca3af;
                            text-transform:uppercase;
                            letter-spacing:1px;
                        ">
                            Date & Time
                        </div>

                        <div style="
                            margin-top:1mm;
                            font-size:8.5pt;
                            font-weight:800;
                            color:#111827;
                        ">
                            {{ \Carbon\Carbon::parse($order->event->start_date)->format('D, M d, Y') }}
                        </div>

                        <div style="
                            margin-top:0.5mm;
                            font-size:7.5pt;
                            color:#4b5563;
                            font-weight:600;
                        ">
                            {{ \Carbon\Carbon::parse($order->event->start_date)->format('H:i') }}
                            @if($order->event->end_date)
                                —
                                {{ \Carbon\Carbon::parse($order->event->end_date)->format('H:i') }}
                            @endif
                        </div>

                    </div>


                    <div>

                        <div style="
                            font-size:6.5pt;
                            font-weight:800;
                            color:#9ca3af;
                            text-transform:uppercase;
                            letter-spacing:1px;
                        ">
                            Venue
                        </div>

                        <div style="
                            margin-top:1mm;
                            font-size:8.5pt;
                            font-weight:800;
                            color:#111827;
                        ">
                            {{ $order->event->venue_name }}
                        </div>

                    </div>

                </div>


                {{-- Holder / Ticket details --}}
                <div style="
                    display:grid;
                    grid-template-columns:1fr 1fr;
                    gap:5mm 8mm;
                    margin-top:5mm;
                ">

                    <div>

                        <div style="
                            font-size:6.5pt;
                            font-weight:800;
                            color:#9ca3af;
                            text-transform:uppercase;
                            letter-spacing:1px;
                        ">
                            Ticket Holder
                        </div>

                        <div style="
                            margin-top:1mm;
                            font-size:8pt;
                            font-weight:800;
                            color:#111827;
                        ">
                            {{ $order->customer_name }}
                        </div>

                    </div>


                    <div>

                        <div style="
                            font-size:6.5pt;
                            font-weight:800;
                            color:#9ca3af;
                            text-transform:uppercase;
                            letter-spacing:1px;
                        ">
                            Ticket Code
                        </div>

                        <div style="
                            margin-top:1mm;
                            font-size:7.5pt;
                            font-family:monospace;
                            font-weight:800;
                            color:#111827;
                            word-break:break-all;
                        ">
                            {{ $ticket->ticket_code }}
                        </div>

                    </div>

                </div>


                {{-- Small security note --}}
                <div style="
                    margin-top:6mm;
                    font-size:6.5pt;
                    color:#6b7280;
                    line-height:1.5;
                ">
                    This ticket is valid for one admission only.
                    Keep this QR code secure and present it at the venue entrance.
                </div>

            </div>


            {{-- =================================================
                 QR STUB
            ================================================== --}}

            <div class="ticket-stub">

                <div style="
                    font-size:6.5pt;
                    font-weight:900;
                    color:#6b7280;
                    text-transform:uppercase;
                    letter-spacing:1.2px;
                    margin-bottom:3mm;
                    text-align:center;
                ">
                    Entry Pass
                </div>


                <div class="qr-box">

                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(json_encode([
                            'code' => $ticket->ticket_code,
                            'hash' => $ticket->qr_code_hash
                        ])) }}"
                        alt="Ticket QR Code"
                    >

                </div>


                <div style="
                    margin-top:3mm;
                    font-size:7pt;
                    font-weight:900;
                    color:#111827;
                    text-transform:uppercase;
                    letter-spacing:1px;
                    text-align:center;
                ">
                    Scan at Gate
                </div>


                <div style="
                    margin-top:1.5mm;
                    font-family:monospace;
                    font-size:6.5pt;
                    color:#6b7280;
                    text-align:center;
                    word-break:break-all;
                ">
                    {{ $ticket->ticket_code }}
                </div>

            </div>

        </div>

    @endforeach

</div>


{{-- =========================================================
     PRINT FOOTER
========================================================== --}}

<div class="print-footer">

    <strong>ShikaTicket</strong>
    &nbsp;•&nbsp;
    Official Event Admission Pass
    &nbsp;•&nbsp;
    Order #{{ $order->order_number }}

</div>


{{-- =========================================================
     SCREEN ACTIONS
========================================================== --}}

<div class="mt-8 flex flex-col sm:flex-row gap-3 no-print">

    <button
        onclick="window.print()"
        class="flex-1 py-3 px-4 rounded-xl border border-line bg-white text-ink font-bold text-xs hover:bg-soft transition text-center shadow-sm"
    >
        🖨️ Print Tickets / Save as PDF
    </button>

    <a
        href="{{ route('events.index') }}"
        class="flex-1 py-3 px-4 rounded-xl bg-accent hover:bg-accent-dark text-white font-bold text-xs transition text-center shadow-sm hover:-translate-y-0.5"
    >
        Browse More Events →
    </a>

</div>
```

</div>

@endsection
