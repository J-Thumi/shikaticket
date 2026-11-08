 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Your ShikaTicket Tickets</title>
</head>

<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:Arial, Helvetica, sans-serif; color:#172033;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f1f5f9; padding:30px 12px;">
        <tr>
            <td align="center">

                <!-- EMAIL CONTAINER -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden;">

                    <!-- BRAND HEADER -->
                    <tr>
                        <td style="background-color:#0f172a; padding:27px 32px; text-align:center;">

                            <div style="font-size:27px; font-weight:bold; letter-spacing:-1px; color:#ffffff;">
                                Shika<span style="color:#34d399;">Ticket</span>
                            </div>

                            <div style="font-size:10px; color:#cbd5e1; letter-spacing:2px; font-weight:bold; margin-top:7px;">
                                YOUR EVENTS. YOUR EXPERIENCES.
                            </div>

                        </td>
                    </tr>

                    <!-- SUCCESS BANNER -->
                    <tr>
                        <td style="padding:32px 32px 20px; text-align:center;">

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
                                <tr>
                                    <td align="center" valign="middle" width="58" height="58" style="width:58px; height:58px; background-color:#dcfce7; border-radius:50%; color:#047857; font-size:30px; font-weight:bold;">
                                        &#10003;
                                    </td>
                                </tr>
                            </table>

                            <h1 style="font-size:25px; line-height:1.3; color:#0f172a; margin:20px 0 8px;">
                                Payment Successful!
                            </h1>

                            <p style="font-size:14px; line-height:1.7; color:#64748b; margin:0;">
                                Your tickets are confirmed and ready.
                                Get ready for an amazing experience!
                            </p>

                        </td>
                    </tr>

                    <!-- GREETING -->
                    <tr>
                        <td style="padding:8px 32px 20px;">

                            <p style="font-size:15px; color:#334155; margin:0 0 12px;">
                                Hello <strong style="color:#0f172a;">{{ $order->customer_name }}</strong>,
                            </p>

                            <p style="font-size:14px; line-height:1.8; color:#64748b; margin:0;">
                                Thank you for choosing ShikaTicket. We've successfully confirmed your payment and prepared your event admission tickets.
                            </p>

                        </td>
                    </tr>

                    <!-- EVENT CARD -->
                    <tr>
                        <td style="padding:0 32px 22px;">

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:9px;">

                                <tr>
                                    <td style="padding:19px 20px 10px;">

                                        <div style="font-size:10px; font-weight:bold; color:#047857; letter-spacing:1.2px; text-transform:uppercase;">
                                            YOUR UPCOMING EVENT
                                        </div>

                                        <h2 style="font-size:21px; line-height:1.4; color:#0f172a; margin:9px 0;">
                                            {{ $order->event->title }}
                                        </h2>

                                        <p style="font-size:13px; color:#64748b; margin:0;">
                                            {{ $order->event->venue_name }}
                                        </p>

                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 20px 18px;">

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="50%" valign="top" style="padding:10px 5px 0 0; border-top:1px solid #e2e8f0;">

                                                    <div style="font-size:10px; color:#94a3b8; font-weight:bold; text-transform:uppercase;">
                                                        EVENT DATE
                                                    </div>

                                                    <div style="font-size:13px; color:#172033; font-weight:bold; margin-top:6px;">
                                                        @if($order->event->start_date)
                                                            {{ \Carbon\Carbon::parse($order->event->start_date)->format('D, M d, Y') }}
                                                        @else
                                                            To be announced
                                                        @endif
                                                    </div>

                                                </td>

                                                <td width="50%" valign="top" style="padding:10px 0 0 10px; border-top:1px solid #e2e8f0;">

                                                    <div style="font-size:10px; color:#94a3b8; font-weight:bold; text-transform:uppercase;">
                                                        START TIME
                                                    </div>

                                                    <div style="font-size:13px; color:#172033; font-weight:bold; margin-top:6px;">
                                                        @if($order->event->start_date)
                                                            {{ \Carbon\Carbon::parse($order->event->start_date)->format('h:i A') }}
                                                        @else
                                                            To be announced
                                                        @endif
                                                    </div>

                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- ORDER SUMMARY -->
                    <tr>
                        <td style="padding:0 32px 22px;">

                            <h3 style="font-size:15px; color:#0f172a; margin:0 0 13px;">
                                Order Summary
                            </h3>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0; border-radius:8px;">

                                <tr>
                                    <td style="padding:13px 15px; border-bottom:1px solid #e2e8f0;">
                                        <span style="font-size:12px; color:#64748b;">Order Number</span>
                                    </td>

                                    <td align="right" style="padding:13px 15px; border-bottom:1px solid #e2e8f0;">
                                        <strong style="font-size:12px; color:#0f172a; font-family:monospace;">
                                            #{{ $order->order_number }}
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:13px 15px; border-bottom:1px solid #e2e8f0;">
                                        <span style="font-size:12px; color:#64748b;">Tickets Purchased</span>
                                    </td>

                                    <td align="right" style="padding:13px 15px; border-bottom:1px solid #e2e8f0;">
                                        <strong style="font-size:12px; color:#0f172a;">
                                            {{ $order->tickets->count() }}
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:15px;">
                                        <strong style="font-size:13px; color:#0f172a;">Total Paid</strong>
                                    </td>

                                    <td align="right" style="padding:15px;">
                                        <strong style="font-size:17px; color:#047857;">
                                            {{ $order->currency }} {{ number_format($order->total_amount, 2) }}
                                        </strong>
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- PDF ATTACHMENT NOTICE -->
                    <tr>
                        <td style="padding:0 32px 24px;">

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:9px;">

                                <tr>
                                    <td style="padding:18px;">

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="42" valign="top">
                                                    <div style="font-size:27px; color:#047857;">
                                                        &#128196;
                                                    </div>
                                                </td>

                                                <td valign="top">

                                                    <h3 style="font-size:14px; color:#065f46; margin:0 0 6px;">
                                                        Your Tickets Are Attached!
                                                    </h3>

                                                    <p style="font-size:12px; line-height:1.7; color:#166534; margin:0;">
                                                        We've attached one PDF document containing all {{ $order->tickets->count() }} of your tickets.
                                                        Each ticket has its own unique QR code for admission.
                                                    </p>

                                                    <p style="font-size:11px; font-weight:bold; color:#047857; margin:10px 0 0;">
                                                        Attachment: ShikaTicket-{{ $order->order_number }}.pdf
                                                    </p>

                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- ENTRY INSTRUCTIONS -->
                    <tr>
                        <td style="padding:0 32px 25px;">

                            <h3 style="font-size:15px; color:#0f172a; margin:0 0 12px;">
                                Before You Head Out
                            </h3>

                            <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0 0 7px;">
                                <strong style="color:#334155;">1.</strong>
                                Download and save your attached PDF.
                            </p>

                            <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0 0 7px;">
                                <strong style="color:#334155;">2.</strong>
                                Present the individual QR code for each ticket at the entrance.
                            </p>

                            <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0 0 7px;">
                                <strong style="color:#334155;">3.</strong>
                                Keep your ticket codes private and avoid sharing them publicly.
                            </p>

                            <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0;">
                                <strong style="color:#334155;">4.</strong>
                                Arrive on time and follow the event organizer's entry instructions.
                            </p>

                        </td>
                    </tr>

                    <!-- SUPPORT -->
                    <tr>
                        <td style="padding:20px 32px; background-color:#f8fafc; border-top:1px solid #e2e8f0; text-align:center;">

                            <h3 style="font-size:14px; color:#0f172a; margin:0 0 8px;">
                                Need Assistance?
                            </h3>

                            <p style="font-size:12px; line-height:1.7; color:#64748b; margin:0;">
                                If you have any questions about your order or tickets, our support team is here to help.
                            </p>

                            <p style="font-size:12px; margin:12px 0 0;">
                                <a href="mailto:{{ config('services.shikaticket.support_email', config('mail.from.address')) }}" style="color:#047857; font-weight:bold; text-decoration:none;">
                                    {{ config('services.shikaticket.support_email', config('mail.from.address')) }}
                                </a>
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding:22px 25px; background-color:#0f172a; text-align:center;">

                            <div style="font-size:19px; font-weight:bold; color:#ffffff;">
                                Shika<span style="color:#34d399;">Ticket</span>
                            </div>

                            <p style="font-size:11px; color:#cbd5e1; margin:9px 0;">
                                Your events. Your experiences.
                            </p>

                            <p style="font-size:10px; color:#94a3b8; margin:0;">
                                Thank you for choosing ShikaTicket.
                            </p>

                            <p style="font-size:10px; color:#64748b; margin:14px 0 0;">
                                This is an automated purchase confirmation.
                                Please keep this email for your records.
                            </p>

                        </td>
                    </tr>

                </table>

                <!-- COPYRIGHT -->
                <p style="font-size:10px; color:#94a3b8; text-align:center; margin:18px 0 0;">
                    &copy; {{ date('Y') }} ShikaTicket. All rights reserved.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>