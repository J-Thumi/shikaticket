
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Reset Your ShikaTicket Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:Arial, Helvetica, sans-serif; color:#172033;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background-color:#f1f5f9; padding:30px 12px;">
    <tr>
        <td align="center">

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden;">

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

                <tr>
                    <td style="padding:35px 32px 20px; text-align:center;">

                        <div style="font-size:38px; margin-bottom:12px;">
                            &#128274;
                        </div>

                        <h1 style="font-size:24px; color:#0f172a; margin:0 0 12px;">
                            Reset Your Password
                        </h1>

                        <p style="font-size:14px; line-height:1.8; color:#64748b; margin:0;">
                            Hello <strong style="color:#0f172a;">{{ $user->name }}</strong>,
                            we received a request to reset the password for your ShikaTicket account.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td style="padding:15px 32px 25px; text-align:center;">

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
                            <tr>
                                <td bgcolor="#047857" style="border-radius:7px;">
                                    <a href="{{ $resetUrl }}"
                                       style="display:inline-block; padding:15px 30px; color:#ffffff; font-size:14px; font-weight:bold; text-decoration:none;">
                                        Reset My Password
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="font-size:12px; line-height:1.8; color:#64748b; margin:20px 0 0;">
                            This password reset link expires in {{ $expiresIn }} minutes.
                            It can only be used once.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td style="padding:0 32px 28px;">

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                               style="background-color:#fffbeb; border:1px solid #fde68a; border-radius:9px;">
                            <tr>
                                <td style="padding:16px 18px;">

                                    <h3 style="font-size:13px; color:#92400e; margin:0 0 7px;">
                                        Security Reminder
                                    </h3>

                                    <p style="font-size:12px; line-height:1.8; color:#92400e; margin:0;">
                                        If you did not request a password reset, you can safely ignore this email.
                                        Your current password will remain unchanged.
                                    </p>

                                </td>
                            </tr>
                        </table>

                        <p style="font-size:11px; line-height:1.8; color:#94a3b8; margin:20px 0 0;">
                            If the button does not work, copy and paste this address into your browser:
                            <br>
                            <a href="{{ $resetUrl }}" style="color:#047857; word-break:break-all;">
                                {{ $resetUrl }}
                            </a>
                        </p>

                    </td>
                </tr>

                <tr>
                    <td style="padding:22px 25px; background-color:#0f172a; text-align:center;">

                        <div style="font-size:19px; font-weight:bold; color:#ffffff;">
                            Shika<span style="color:#34d399;">Ticket</span>
                        </div>

                        <p style="font-size:11px; color:#cbd5e1; margin:9px 0;">
                            Your events. Your experiences.
                        </p>

                        <p style="font-size:10px; color:#94a3b8; margin:0;">
                            Secure access to your ShikaTicket account.
                        </p>

                    </td>
                </tr>

            </table>

            <p style="font-size:10px; color:#94a3b8; text-align:center; margin:18px 0 0;">
                &copy; {{ date('Y') }} ShikaTicket. All rights reserved.
            </p>

        </td>
    </tr>
</table>

</body>
</html>
