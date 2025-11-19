<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }

        .content {
            padding: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eaeaea;
        }

        .button {
            display: inline-block;
            background-color: #16a34a;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #15803d;
        }

        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }

        h1 {
            color: #16a34a;
            margin-top: 0;
        }

        h2 {
            color: #166534;
            font-size: 18px;
        }

        ul {
            padding-left: 20px;
        }

        .reference {
            font-weight: bold;
            color: #16a34a;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="ACICS Logo" class="logo">
            <h1>Password Reset Request</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>We received a request to reset the password for your ACICS account.</p>

            <div class="info-box">
                <h2>Reset Information</h2>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Request Time:</strong> {{ date('F j, Y, g:i a') }}</p>
            </div>

            <p>To reset your password, please click the button below:</p>
            <div style="text-align: center;">
                <a href="{{ $resetLink }}" class="button">Reset Your Password</a>
            </div>
            <p><small>If the button doesn't work, copy and paste this link into your browser: {{ $resetLink }}</small></p>

            <p>This link will expire in 24 hours for security reasons.</p>

            <div class="info-box">
                <h2>Security Note</h2>
                <p>If you did not request a password reset, please ignore this email or contact support if you have
                    concerns about your account security.</p>
            </div>

            <p>If you have any questions or need assistance, please don't hesitate to contact our support team at <a
                    href="mailto:support@acics.org">support@acics.org</a> or call us at +234-XXX-XXX-XXXX.</p>

            <p>Best regards,<br> The ACICS Support Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ACICS Cooperative Society. All rights reserved.</p>
            <p>This email was sent to {{ $email }}.</p>
        </div>
    </div>
</body>

</html>