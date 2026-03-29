<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Preorder Message</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f8; padding:20px 0;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.08);">

                <tr>
                    <td style="background:linear-gradient(90deg, #1e293b, #334155); padding:20px 30px; text-align:left;">
                        <h1 style="color:#ffffff; margin:0; font-size:22px; letter-spacing:0.5px;">
                            Iron Industries
                        </h1>
                        <p style="color:#cbd5f5; margin:5px 0 0; font-size:13px;">
                            New Preorder Inquiry
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px; color:#333333;">

                        <h2 style="margin-top:0; font-size:18px; color:#1e293b;">
                            You’ve received a new preorder
                        </h2>

                        <p style="font-size:14px; color:#555;">
                            A user submitted a preorder request through your website.
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:20px; border-collapse:collapse;">
                            <tr>
                                <td style="padding:10px; background:#f1f5f9; font-weight:bold; width:30%;">Full Name</td>
                                <td style="padding:10px;">{{ $preorder->full_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px; background:#f8fafc; font-weight:bold;">Phone</td>
                                <td style="padding:10px;">{{ $preorder->phone_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px; background:#f1f5f9; font-weight:bold;">Email</td>
                                <td style="padding:10px;">{{ $preorder->email ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px; background:#f8fafc; font-weight:bold;">File</td>
                                <td style="padding:10px;">
                                    {{ $preorder->file_path ?: 'No file uploaded' }}
                                </td>
                            </tr>
                        </table>

                        <div style="margin-top:25px;">
                            <h3 style="margin-bottom:10px; color:#1e293b;">Calculating Information</h3>
                            <div style="background:#f8fafc; padding:15px; border-radius:6px; border:1px solid #e2e8f0; font-size:14px; line-height:1.6;">
                                {!! nl2br(e($preorder->calculating_information)) !!}
                            </div>
                        </div>

                        @if(!empty($preorder->comment))
                            <div style="margin-top:25px;">
                                <h3 style="margin-bottom:10px; color:#1e293b;">Comment</h3>
                                <div style="background:#f8fafc; padding:15px; border-radius:6px; border:1px solid #e2e8f0; font-size:14px; line-height:1.6;">
                                    {!! nl2br(e($preorder->comment)) !!}
                                </div>
                            </div>
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="background:#f1f5f9; padding:20px 30px; text-align:center;">
                        <p style="margin:0; font-size:12px; color:#64748b;">
                            © {{ date('Y') }} Iron Industries. All rights reserved.
                        </p>
                        <p style="margin:5px 0 0; font-size:12px; color:#94a3b8;">
                            This email was generated automatically from your website preorder form.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
