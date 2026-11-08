
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4;
            margin: 11mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #172033;
            background: #ffffff;
            font-size: 9pt;
            line-height: 1.45;
        }

        .green { color: #047857; }
        .muted { color: #64748b; }

        /* BRAND HEADER */
        .header {
            background: #0f172a;
            color: white;
            padding: 22px 24px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
        }

        .brand {
            font-size: 23pt;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: -1px;
        }

        .brand-accent {
            color: #34d399;
        }

        .subtitle {
            color: #cbd5e1;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-top: 4px;
        }

        .header-right {
            text-align: right;
        }

        .header-label {
            color: #94a3b8;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .order-number {
            font-family: monospace;
            color: #ffffff;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 4px;
        }

        .confirmation {
            margin-top: 14px;
            padding: 8px 11px;
            background: #064e3b;
            color: #a7f3d0;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }

        /* SECTION HEADINGS */
        .section-heading {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #047857;
            margin-bottom: 10px;
        }

        /* EVENT SUMMARY */
        .event-summary {
            border: 1px solid #dbe3ed;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 17px;
        }

        .event-title {
            font-size: 20pt;
            line-height: 1.2;
            font-weight: bold;
            color: #0f172a;
            margin: 4px 0 7px;
        }

        .event-description {
            color: #64748b;
            font-size: 8pt;
            margin-bottom: 12px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .details-table td {
            width: 50%;
            padding: 9px 8px;
            border-top: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .label {
            color: #94a3b8;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .value {
            color: #0f172a;
            font-size: 8.5pt;
            font-weight: bold;
            margin-top: 4px;
        }

        /* ORDER SUMMARY */
        .summary {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 7px;
            padding: 13px 16px;
            margin-bottom: 19px;
        }

        .summary-table {
            width: 100%;
        }

        .summary-number {
            font-size: 17pt;
            color: #047857;
            font-weight: bold;
        }

        .summary-label {
            font-size: 7pt;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        /* TICKET CARDS */
        .ticket-card {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .ticket-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ticket-main {
            width: 70%;
            padding: 17px;
            vertical-align: top;
        }

        .ticket-stub {
            width: 30%;
            padding: 13px 8px;
            vertical-align: middle;
            text-align: center;
            background: #f0fdf4;
            border-left: 2px dashed #10b981;
        }

        .ticket-top {
            margin-bottom: 14px;
        }

        .ticket-type {
            display: inline-block;
            background: #047857;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admit {
            float: right;
            color: #047857;
            font-size: 7pt;
            font-weight: bold;
        }

        .ticket-event-title {
            font-size: 17pt;
            font-weight: bold;
            line-height: 1.2;
            color: #0f172a;
            margin-top: 5px;
        }

        .ticket-venue {
            color: #64748b;
            font-size: 8pt;
            font-weight: bold;
            margin-top: 5px;
        }

        .ticket-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .ticket-info td {
            width: 50%;
            padding: 9px 5px;
            border-top: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .ticket-code {
            font-family: monospace;
            font-size: 8pt;
            font-weight: bold;
            color: #047857;
            word-wrap: break-word;
        }

        .security-note {
            background: #f8fafc;
            border-left: 3px solid #10b981;
            padding: 8px;
            margin-top: 13px;
            color: #64748b;
            font-size: 6.5pt;
        }

        /* QR SECTION */
        .stub-heading {
            color: #047857;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 1.2px;
            margin-bottom: 10px;
        }

        .qr-box {
            width: 125px;
            height: 125px;
            padding: 7px;
            margin: auto;
            background: #ffffff;
            border: 1px solid #a7f3d0;
            border-radius: 7px;
        }

        .qr-box img {
            width: 109px;
            height: 109px;
        }

        .scan-label {
            color: #047857;
            font-size: 7pt;
            font-weight: bold;
            margin-top: 10px;
        }

        .stub-code {
            color: #64748b;
            font-family: monospace;
            font-size: 6pt;
            margin-top: 5px;
            word-wrap: break-word;
        }

        /* ENTRY INFORMATION */
        .instructions {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 7px;
            padding: 13px 15px;
            margin-top: 17px;
            margin-bottom: 17px;
        }

        .instruction-title {
            color: #0f172a;
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .instruction {
            color: #475569;
            font-size: 7pt;
            margin-bottom: 5px;
        }

        /* FOOTER */
        .footer {
            border-top: 2px solid #047857;
            padding-top: 12px;
            margin-top: 20px;
            text-align: center;
        }

        .footer-brand {
            color: #047857;
            font-size: 11pt;
            font-weight: bold;
        }

        .footer-text {
            color: #64748b;
            font-size: 7pt;
            margin-top: 4px;
        }

        .footer-contact {
            color: #334155;
            font-size: 7pt;
            margin-top: 6px;
        }

        .small-note {
            font-size: 6pt;
            color: #94a3b8;
            margin-top: 9px;
        }
    </style>
</head>

<body>

    {{-- ==========================================
         BRAND HEADER
    =========================================== --}}

    <div class="header">

        <table class="header-table">
            <tr>
                <td>
                    <div class="brand">
                        Shika<span class="brand-accent">Ticket</span>
                    </div>

                    <div class="subtitle">
                        YOUR EVENTS. YOUR EXPERIENCES.
                    </div>
                </td>

                <td class="header-right">
                    <div class="header-label">
                        Order Reference
                    </div>

                    <div class="order-number">
                        #{{ $order->order_number }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="confirmation">
            &#10003; ORDER CONFIRMED &bull; YOUR ADMISSION TICKETS ARE READY
        </div>

    </div>


    {{-- ==========================================
         EVENT INFORMATION
    =========================================== --}}

    <div class="section-heading">
        Event Information
    </div>

    <div class="event-summary">

        <div class="label">YOU ARE ATTENDING</div>

        <div class="event-title">
            {{ $event->title }}
        </div>

        @if(data_get($event, 'description'))
            <div class="event-description">
                {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 220) }}
            </div>
        @endif

        <table class="details-table">
            <tr>
                <td>
                    <div class="label">Event Date</div>
                    <div class="value">
                        @if($event->start_date)
                            {{ \Carbon\Carbon::parse($event->start_date)->format('l, F d, Y') }}
                        @else
                            To be announced
                        @endif
                    </div>
                </td>

                <td>
                    <div class="label">Starting Time</div>
                    <div class="value">
                        @if($event->start_date)
                            {{ \Carbon\Carbon::parse($event->start_date)->format('h:i A') }}
                        @else
                            To be announced
                        @endif
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="label">Venue</div>
                    <div class="value">
                        {{ $event->venue_name ?: 'Venue to be announced' }}
                    </div>

                    @if(data_get($event, 'venue_address'))
                        <div class="muted" style="font-size:7pt;">
                            {{ $event->venue_address }}
                        </div>
                    @endif
                </td>

                <td>
                    <div class="label">Event Ends</div>
                    <div class="value">
                        @if($event->end_date)
                            {{ \Carbon\Carbon::parse($event->end_date)->format('D, M d, Y - h:i A') }}
                        @else
                            See event details
                        @endif
                    </div>
                </td>
            </tr>
        </table>

    </div>


    {{-- ==========================================
         ORDER SUMMARY
    =========================================== --}}

    <div class="summary">

        <table class="summary-table">
            <tr>
                <td>
                    <div class="summary-label">
                        Tickets Purchased
                    </div>

                    <div class="summary-number">
                        {{ $ticketCount }}
                    </div>
                </td>

                <td align="right">
                    <div class="summary-label">
                        Order Reference
                    </div>

                    <div style="
                        font-family:monospace;
                        font-weight:bold;
                        font-size:10pt;
                        color:#0f172a;
                        margin-top:8px;
                    ">
                        #{{ $order->order_number }}
                    </div>
                </td>
            </tr>
        </table>

    </div>


    {{-- ==========================================
         INDIVIDUAL TICKETS
    =========================================== --}}

    <div class="section-heading">
        Your Admission Tickets
    </div>

    @foreach($tickets as $index => $item)

        @php
            $ticket = $item['ticket'];
        @endphp

        <div class="ticket-card">

            <table class="ticket-table">
                <tr>

                    {{-- MAIN TICKET --}}
                    <td class="ticket-main">

                        <div class="ticket-top">

                            <span class="ticket-type">
                                {{ data_get($ticket, 'ticketType.name', 'General Admission') }}
                            </span>

                            <span class="admit">
                                TICKET {{ $index + 1 }} OF {{ $ticketCount }}
                            </span>

                        </div>

                        <div class="label">EVENT ADMISSION</div>

                        <div class="ticket-event-title">
                            {{ $event->title }}
                        </div>

                        <div class="ticket-venue">
                            {{ $event->venue_name }}
                        </div>


                        <table class="ticket-info">
                            <tr>
                                <td>
                                    <div class="label">Ticket Holder</div>

                                    <div class="value">
                                        {{ $order->customer_name }}
                                    </div>
                                </td>

                                <td>
                                    <div class="label">Ticket Code</div>

                                    <div class="ticket-code">
                                        {{ $ticket->ticket_code }}
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="label">Event Date</div>

                                    <div class="value">
                                        @if($event->start_date)
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                        @else
                                            TBA
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <div class="label">Admission</div>

                                    <div class="value">
                                        One Person
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <div class="security-note">
                            This ticket grants one admission only.
                            Keep your QR code private. Each ticket has
                            a unique code and can only be validated
                            according to the event's admission rules.
                        </div>

                    </td>


                    {{-- QR STUB --}}
                    <td class="ticket-stub">

                        <div class="stub-heading">
                            OFFICIAL ENTRY PASS
                        </div>

                        <div class="qr-box">
                            <img
                                src="data:image/svg+xml;base64,{{ $item['qrCodeSvg'] }}"
                                alt="Ticket QR"
                            >
                        </div>

                        <div class="scan-label">
                            SCAN AT EVENT ENTRANCE
                        </div>

                        <div class="stub-code">
                            {{ $ticket->ticket_code }}
                        </div>

                    </td>

                </tr>
            </table>

        </div>

    @endforeach


    {{-- ==========================================
         ENTRY INSTRUCTIONS
    =========================================== --}}

    <div class="instructions">

        <div class="instruction-title">
            Important Information Before You Attend
        </div>

        <div class="instruction">
            <strong>1.</strong> Bring this PDF or have your ticket QR code
            ready on your phone.
        </div>

        <div class="instruction">
            <strong>2.</strong> Present each ticket's unique QR code
            at the event entrance.
        </div>

        <div class="instruction">
            <strong>3.</strong> Each ticket permits one admission only.
        </div>

        <div class="instruction">
            <strong>4.</strong> Avoid sharing your QR codes publicly.
        </div>

        <div class="instruction">
            <strong>5.</strong> Arrive early and follow the event
            organizer's entry requirements.
        </div>

    </div>


    {{-- ==========================================
         CUSTOMER INFORMATION
    =========================================== --}}

    <div class="section-heading">
        Order Information
    </div>

    <table class="details-table">
        <tr>
            <td>
                <div class="label">Customer Name</div>

                <div class="value">
                    {{ $order->customer_name }}
                </div>
            </td>

            <td>
                <div class="label">Order Date</div>

                <div class="value">
                    {{ $order->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </div>
            </td>
        </tr>

        @if(data_get($order, 'customer_email') || data_get($order, 'customer_phone'))
            <tr>
                @if(data_get($order, 'customer_email'))
                    <td>
                        <div class="label">Email Address</div>

                        <div class="value">
                            {{ $order->customer_email }}
                        </div>
                    </td>
                @endif

                @if(data_get($order, 'customer_phone'))
                    <td>
                        <div class="label">Phone Number</div>

                        <div class="value">
                            {{ $order->customer_phone }}
                        </div>
                    </td>
                @endif
            </tr>
        @endif
    </table>


    {{-- ==========================================
         BRANDED FOOTER
    =========================================== --}}

    <div class="footer">

        <div class="footer-brand">
            ShikaTicket
        </div>

        <div class="footer-text">
            Making event experiences easier, one ticket at a time.
        </div>

        <div class="footer-contact">

            @if($supportEmail)
                Support: {{ $supportEmail }}
            @endif

            @if($supportPhone)
                &nbsp; | &nbsp; {{ $supportPhone }}
            @endif

        </div>

        @if($website)
            <div class="footer-contact">
                {{ $website }}
            </div>
        @endif

        <div class="small-note">
            This is an electronically generated admission document.
            Ticket validity is subject to successful verification
            at the event entrance.
        </div>

    </div>

</body>
</html>
