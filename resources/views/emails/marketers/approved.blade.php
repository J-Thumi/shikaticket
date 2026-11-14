
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Your ShikaTicket Marketer Application Has Been Approved</title>
</head>

<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:Arial, Helvetica, sans-serif; color:#172033;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background-color:#f1f5f9; padding:30px 12px;">
    <tr>
        <td align="center">

            <!-- EMAIL CONTAINER -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden;">

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

                <!-- APPROVAL BANNER -->
                <tr>
                    <td style="padding:32px 32px 20px; text-align:center;">

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
                            <tr>
                                <td align="center" valign="middle" width="58" height="58"
                                    style="width:58px; height:58px; background-color:#dcfce7; border-radius:50%; color:#047857; font-size:30px; font-weight:bold;">
                                    &#10003;
                                </td>
                            </tr>
                        </table>

                        <h1 style="font-size:25px; line-height:1.3; color:#0f172a; margin:20px 0 8px;">
                            You're Approved!
                        </h1>

                        <p style="font-size:14px; line-height:1.7; color:#64748b; margin:0;">
                            Your application to become a ShikaTicket marketer has been approved.
                            You're now ready to start promoting events and earning commissions.
                        </p>

                    </td>
                </tr>

                <!-- GREETING -->
                <tr>
                    <td style="padding:8px 32px 22px;">

                        <p style="font-size:15px; color:#334155; margin:0 0 12px;">
                            Hello <strong style="color:#0f172a;">{{ $user->name }}</strong>,
                        </p>

                        <p style="font-size:14px; line-height:1.8; color:#64748b; margin:0;">
                            Congratulations! Your application has been approved by
                            <strong style="color:#334155;">{{ $invitation->organizer->name }}</strong>.
                            You have been assigned to promote the event below.
                        </p>

                    </td>
                </tr>

                <!-- EVENT DETAILS -->
                <tr>
                    <td style="padding:0 32px 22px;">

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:9px;">

                            <tr>
                                <td style="padding:19px 20px 16px;">

                                    <div style="font-size:10px; font-weight:bold; color:#047857; letter-spacing:1.2px; text-transform:uppercase;">
                                        YOUR ASSIGNED EVENT
                                    </div>

                                    <h2 style="font-size:21px; line-height:1.4; color:#0f172a; margin:9px 0;">
                                        {{ $event->title }}
                                    </h2>

                                    @if($event->venue_name)
                                        <p style="font-size:13px; color:#64748b; margin:0;">
                                            {{ $event->venue_name }}
                                        </p>
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td style="padding:0 20px 18px;">

                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td width="50%" valign="top"
                                                style="padding:12px 5px 0 0; border-top:1px solid #e2e8f0;">

                                                <div style="font-size:10px; color:#94a3b8; font-weight:bold; text-transform:uppercase;">
                                                    EVENT START
                                                </div>

                                                <div style="font-size:13px; color:#172033; font-weight:bold; margin-top:6px;">
                                                    @if($event->start_date)
                                                        {{ \Carbon\Carbon::parse($event->start_date)->format('D, M d, Y') }}
                                                    @else
                                                        To be announced
                                                    @endif
                                                </div>

                                            </td>

                                            <td width="50%" valign="top"
                                                style="padding:12px 0 0 10px; border-top:1px solid #e2e8f0;">

                                                <div style="font-size:10px; color:#94a3b8; font-weight:bold; text-transform:uppercase;">
                                                    ORGANIZER
                                                </div>

                                                <div style="font-size:13px; color:#172033; font-weight:bold; margin-top:6px;">
                                                    {{ $invitation->organizer->name }}
                                                </div>

                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

                <!-- COMMISSION DETAILS -->
                <tr>
                    <td style="padding:0 32px 22px;">

                        <h3 style="font-size:15px; color:#0f172a; margin:0 0 12px;">
                            Your Commission Agreement
                        </h3>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="border:1px solid #e2e8f0; border-radius:8px;">

                            <tr>
                                <td style="padding:14px 15px; border-bottom:1px solid #e2e8f0;">
                                    <span style="font-size:12px; color:#64748b;">Commission structure</span>
                                </td>

                                <td align="right" style="padding:14px 15px; border-bottom:1px solid #e2e8f0;">
                                    <strong style="font-size:12px; color:#0f172a;">
                                        @if($marketer->commission_type === 'percentage')
                                            Percentage-based
                                        @else
                                            Fixed amount
                                        @endif
                                    </strong>
                                </td>
                            </tr>

                            @if($marketer->commission_type === 'percentage')
                                <tr>
                                    <td style="padding:14px 15px; border-bottom:1px solid #e2e8f0;">
                                        <span style="font-size:12px; color:#64748b;">Commission rate</span>
                                    </td>

                                    <td align="right" style="padding:14px 15px; border-bottom:1px solid #e2e8f0;">
                                        <strong style="font-size:14px; color:#047857;">
                                            {{ number_format((float) $marketer->commission_percent, 2) }}%
                                        </strong>
                                    </td>
                                </tr>
                            @elseif($marketer->commission_type === 'fixed')
                                <tr>
                                    <td style="padding:14px 15px; border-bottom:1px solid #e2e8f0;">
                                        <span style="font-size:12px; color:#64748b;">Commission per sale</span>
                                    </td>

                                    <td align="right" style="padding:14px 15px; border-bottom:1px solid #e2e8f0;">
                                        <strong style="font-size:14px; color:#047857;">
                                            KES {{ number_format((float) $marketer->fixed_commission, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="padding:14px 15px;">
                                    <span style="font-size:12px; color:#64748b;">Account status</span>
                                </td>

                                <td align="right" style="padding:14px 15px;">
                                    <strong style="font-size:12px; color:#047857;">
                                        Active
                                    </strong>
                                </td>
                            </tr>

                        </table>

                        <p style="font-size:11px; line-height:1.7; color:#94a3b8; margin:10px 0 0;">
                            Commissions are calculated on eligible successful sales attributed to your marketer account,
                            subject to the organizer's commission agreement.
                        </p>

                    </td>
                </tr>

                <!-- REFERRAL DETAILS -->
                <tr>
                    <td style="padding:0 32px 24px;">

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:9px;">

                            <tr>
                                <td style="padding:19px 20px;">

                                    <div style="font-size:10px; font-weight:bold; color:#047857; letter-spacing:1.2px; text-transform:uppercase;">
                                        YOUR REFERRAL DETAILS
                                    </div>

                                    <h3 style="font-size:17px; color:#065f46; margin:9px 0 6px;">
                                        Start sharing and earning
                                    </h3>

                                    <p style="font-size:12px; line-height:1.7; color:#166534; margin:0 0 16px;">
                                        Share your unique referral link with potential attendees.
                                        When eligible ticket purchases are made through your link,
                                        the sales can be attributed to your marketer account.
                                    </p>

                                    <!-- REFERRAL CODE -->
                                    <div style="font-size:10px; font-weight:bold; color:#047857; text-transform:uppercase; margin-bottom:7px;">
                                        YOUR REFERRAL CODE
                                    </div>

                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                           style="background-color:#ffffff; border:1px dashed #86efac; border-radius:7px;">
                                        <tr>
                                            <td align="center" style="padding:15px;">
                                                <div style="font-family:monospace; font-size:23px; letter-spacing:3px; font-weight:bold; color:#065f46;">
                                                    {{ $marketer->referral_code }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- REFERRAL LINK -->
                                    <div style="font-size:10px; font-weight:bold; color:#047857; text-transform:uppercase; margin:17px 0 7px;">
                                        YOUR PERSONAL REFERRAL LINK
                                    </div>

                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                           style="background-color:#ffffff; border:1px solid #a7f3d0; border-radius:7px;">
                                        <tr>
                                            <td style="padding:13px; word-break:break-all;">
                                                <a href="{{ $referralUrl }}"
                                                   style="font-size:12px; line-height:1.7; color:#047857; text-decoration:none;">
                                                    {{ $referralUrl }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <p style="font-size:11px; line-height:1.7; color:#166534; margin:12px 0 0;">
                                        Keep your referral code and link accessible. You can share the link on
                                        WhatsApp, social media, or directly with people interested in attending the event.
                                    </p>

                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

                <!-- DASHBOARD CTA -->
                <tr>
                    <td style="padding:0 32px 27px; text-align:center;">

                        <h3 style="font-size:17px; color:#0f172a; margin:0 0 8px;">
                            Your Marketer Dashboard
                        </h3>

                        <p style="font-size:13px; line-height:1.7; color:#64748b; margin:0 0 18px;">
                            Your dashboard is your workspace. Track your event performance,
                            monitor ticket sales, and keep an eye on your commissions.
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
                            <tr>
                                <td align="center" bgcolor="#047857" style="border-radius:7px;">
                                    <a href="{{ $dashboardUrl }}"
                                       style="display:inline-block; padding:14px 27px; font-size:13px; font-weight:bold; color:#ffffff; text-decoration:none; border-radius:7px;">
                                        Access Marketer Dashboard
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="font-size:11px; line-height:1.7; color:#94a3b8; margin:14px 0 0;">
                            Dashboard link:
                            <a href="{{ $dashboardUrl }}" style="color:#047857; text-decoration:none;">
                                {{ $dashboardUrl }}
                            </a>
                        </p>

                    </td>
                </tr>

                <!-- GETTING STARTED -->
                <tr>
                    <td style="padding:22px 32px 25px; background-color:#f8fafc; border-top:1px solid #e2e8f0;">

                        <h3 style="font-size:15px; color:#0f172a; margin:0 0 15px;">
                            Getting Started
                        </h3>

                        <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0 0 9px;">
                            <strong style="color:#334155;">1. Share your referral link.</strong>
                            Promote the assigned event and direct interested attendees to your link.
                        </p>

                        <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0 0 9px;">
                            <strong style="color:#334155;">2. Drive ticket sales.</strong>
                            Encourage people to purchase their tickets through your referral link.
                        </p>

                        <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0 0 9px;">
                            <strong style="color:#334155;">3. Monitor your performance.</strong>
                            Visit your dashboard to review attributed sales and commissions.
                        </p>

                        <p style="font-size:12px; line-height:1.8; color:#64748b; margin:0;">
                            <strong style="color:#334155;">4. Track your earnings.</strong>
                            Check your commissions dashboard for earned and pending commissions.
                        </p>

                    </td>
                </tr>

                <!-- ORGANIZER NOTE -->
                @if($organizerNote)
                    <tr>
                        <td style="padding:22px 32px;">

                            <h3 style="font-size:14px; color:#0f172a; margin:0 0 10px;">
                                A Message From Your Organizer
                            </h3>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="background-color:#fffbeb; border-left:3px solid #f59e0b;">
                                <tr>
                                    <td style="padding:15px;">
                                        <p style="font-size:13px; line-height:1.8; color:#78350f; margin:0;">
                                            {{ $organizerNote }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                @endif

                <!-- SUPPORT -->
                <tr>
                    <td style="padding:20px 32px; background-color:#f8fafc; border-top:1px solid #e2e8f0; text-align:center;">

                        <h3 style="font-size:14px; color:#0f172a; margin:0 0 8px;">
                            Need Assistance?
                        </h3>

                        <p style="font-size:12px; line-height:1.7; color:#64748b; margin:0;">
                            If you have questions about your marketer account, referral links,
                            or commissions, contact our support team.
                        </p>

                        <p style="font-size:12px; margin:12px 0 0;">
                            <a href="mailto:{{ config('services.shikaticket.support_email', config('mail.from.address')) }}"
                               style="color:#047857; font-weight:bold; text-decoration:none;">
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
                            Congratulations on becoming a ShikaTicket marketer.
                        </p>

                        <p style="font-size:10px; color:#64748b; margin:14px 0 0;">
                            This is an automated account notification.
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
