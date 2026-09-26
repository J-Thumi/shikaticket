<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket Pass - {{ $ticket->ticket_code }}</title>
    <style>
        @page {
            margin: 0px;
            size: letter portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 40px;
        }
        .ticket-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        /* ---------- Header ---------- */
        .header {
            background-color: #14141f;
            color: #ffffff;
            padding: 30px 30px 26px 30px;
            position: relative;
        }
        .badge {
            background-color: rgba(249, 115, 22, 0.12);
            border: 1px solid rgba(249, 115, 22, 0.35);
            color: #fb7a3c;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 16px;
        }
        .order-code {
            color: #8b8b93;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .event-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 8px 0;
            line-height: 1.2;
            color: #ffffff;
        }
        .event-info {
            color: #a8a8b3;
            font-size: 12px;
            font-weight: 500;
            margin: 0 0 4px 0;
        }
        .event-info .pin {
            margin-right: 4px;
        }

        /* ---------- Gate verification strip ---------- */
        .strip-wrap {
            position: relative;
            background-color: #f8fafc;
            border-top: 1px dashed #d8dde5;
            border-bottom: 1px dashed #d8dde5;
        }
        .strip-label {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #64748b;
            padding: 12px 0;
        }
        .notch {
            position: absolute;
            top: 50%;
            margin-top: -12px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #f8fafc;
            border: 1px solid #d8dde5;
        }
        .notch-left {
            left: -12px;
        }
        .notch-right {
            right: -12px;
        }

        /* ---------- QR section ---------- */
        .qr-section {
            background-color: #f8fafc;
            text-align: center;
            padding: 34px 30px;
        }
        .qr-box {
            background: #ffffff;
            padding: 18px;
            display: inline-block;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }
        .qr-caption {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            margin-top: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .qr-subcaption {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ---------- Details grid ---------- */
        .details-section {
            padding: 26px 30px 30px 30px;
            background: #ffffff;
        }
        .section-heading {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
        .table-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .table-grid td {
            padding: 8px 0;
            vertical-align: top;
        }
        .row-divider td {
            border-bottom: 1px solid #eef1f5;
            padding-bottom: 16px;
        }
        .row-spacer td {
            padding-top: 16px;
        }
        .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        .value {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }
        .value-sm {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .status-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            margin-right: 5px;
        }

        /* ---------- Security / terms strip ---------- */
        .security-strip {
            background-color: #fff7ed;
            border-top: 1px dashed #fed7aa;
            padding: 14px 30px;
            font-size: 10px;
            color: #9a3412;
            text-align: center;
            line-height: 1.5;
        }

        .footer-note {
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            margin-top: 18px;
            line-height: 1.6;
        }
        .footer-meta {
            text-align: center;
            font-size: 9px;
            color: #cbd5e1;
            margin-top: 6px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<div class="ticket-container">
    <!-- Header -->
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: top;">
                    <div class="badge">{{ strtoupper($ticket->ticketType->name ?? 'Standard Pass') }}</div>
                </td>
                <td style="vertical-align: top; text-align: right;">
                    <span class="order-code">#{{ $ticket->ticket_code }}</span>
                </td>
            </tr>
        </table>

        <h1 class="event-title">{{ $ticket->order->event->title ?? 'Event Entry Pass' }}</h1>

        <p class="event-info">
            <span style="color: #fb7a3c; font-weight: bold;">&#128205;</span>{{ $ticket->order->event->venue_name ?? 'Location details on ticket' }}
        </p>

        @if(!empty($ticket->order->event->starts_at))
        <p class="event-info">
            <span class="pin">&#128197;</span>{{ \Carbon\Carbon::parse($ticket->order->event->starts_at)->format('l, d M Y') }}
            &nbsp;&bull;&nbsp;
            <span class="pin">&#128336;</span>{{ \Carbon\Carbon::parse($ticket->order->event->starts_at)->format('g:i A') }}
        </p>
        @endif
    </div>

    <!-- Gate Verification Strip -->
    <div class="strip-wrap">
        <div class="notch notch-left"></div>
        <div class="strip-label">Gate Verification Pass</div>
        <div class="notch notch-right"></div>
    </div>

    <!-- QR Code Section -->
    <div class="qr-section">
        <div class="qr-box">
            <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" width="160" height="160" />
        </div>
        <p class="qr-caption">Scan for Entry Authorization</p>
        <p class="qr-subcaption">Ticket ID: {{ $ticket->id ?? $ticket->ticket_code }}</p>
    </div>

    <!-- Details Grid -->
    <div class="details-section">
        <p class="section-heading">Attendee Details</p>
        <table class="table-grid">
            <tr class="row-divider">
                <td width="50%">
                    <span class="label">Attendee Name</span>
                    <span class="value">{{ $ticket->order->customer_name }}</span>
                </td>
                <td width="50%">
                    <span class="label">Pass Status</span>
                    <span class="value" style="color: {{ $ticket->status === 'used' ? '#e11d48' : '#059669' }};">
                        <span class="status-dot" style="background-color: {{ $ticket->status === 'used' ? '#e11d48' : '#059669' }};"></span>{{ strtoupper($ticket->status) }}
                    </span>
                </td>
            </tr>
            <tr class="row-spacer">
                <td width="50%">
                    <span class="label">Email</span>
                    <span class="value-sm">{{ $ticket->order->customer_email }}</span>
                </td>
                <td width="50%">
                    <span class="label">Phone Number</span>
                    <span class="value-sm">{{ $ticket->order->customer_phone ?? 'N/A' }}</span>
                </td>
            </tr>
        </table>

        <p class="section-heading" style="margin-top: 20px;">Order &amp; Ticket Info</p>
        <table class="table-grid">
            <tr class="row-divider">
                <td width="50%">
                    <span class="label">Order Reference</span>
                    <span class="value-sm">{{ $ticket->order->order_number }}</span>
                </td>
                <td width="50%">
                    <span class="label">Ticket Type</span>
                    <span class="value-sm">{{ $ticket->ticketType->name ?? 'Standard Pass' }}</span>
                </td>
            </tr>
            <tr class="row-spacer">
                @if(!empty($ticket->seat_number) || !empty($ticket->ticketType->price))
                <td width="50%">
                    <span class="label">Seat / Section</span>
                    <span class="value-sm">{{ $ticket->seat_number ?? 'General Admission' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Ticket Price</span>
                    <span class="value-sm">{{ isset($ticket->ticketType->price) ? number_format($ticket->ticketType->price, 2) : 'N/A' }}</span>
                </td>
                @else
                <td width="50%">
                    <span class="label">Purchase Date</span>
                    <span class="value-sm">{{ optional($ticket->order->created_at)->format('d M Y') ?? 'N/A' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Gate / Entrance</span>
                    <span class="value-sm">{{ $ticket->gate ?? 'Main Entrance' }}</span>
                </td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Security / Terms Strip -->
    <div class="security-strip">
        This ticket is valid for single entry only and is non-transferable once scanned.
        Please carry a valid ID matching the attendee name above.
    </div>
</div>

<p class="footer-note">
    This is an official entry ticket. Unauthorized duplication or resale is strictly prohibited.<br>
    For support, contact the event organizer via the details provided at checkout.
</p>
<p class="footer-meta">
    Issued on {{ now()->format('d M Y, g:i A') }} &nbsp;|&nbsp; Document Ref: {{ $ticket->ticket_code }}
</p>

</body>
</html>