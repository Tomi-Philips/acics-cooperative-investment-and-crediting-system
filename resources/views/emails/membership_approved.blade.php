<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Application Approved</title>
    <style>
        /* CSS styles for email formatting */
        body { /* ... */ }
        .container { /* ... */ }
        .header { /* ... */ }
        .logo { /* ... */ }
        .content { /* ... */ }
        .footer { /* ... */ }
        .button { /* ... */ }
        .button:hover { /* ... */ }
        .info-box { /* ... */ }
        h1 { /* ... */ }
        h2 { /* ... */ }
        ul { /* ... */ }
        .reference { /* ... */ }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header Section --}}
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="ACICS Logo" class="logo">
            <h1>Membership Application Approved!</h1>
        </div>

        {{-- Content Section --}}
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>We are pleased to inform you that your membership application to ACICS Cooperative Society has been <strong>approved</strong>!</p>

            {{-- Application Details Info Box --}}
            <div class="info-box">
                <h2>Application Details</h2>
                <p><strong>Reference Number:</strong> <span class="reference">{{ $reference }}</span></p>
                <p><strong>Approval Date:</strong> {{ $approval_date }}</p>
            </div>

            <p>To complete your registration and gain full access to your account, please set your password by clicking the button below:</p>

            {{-- Set Password Button --}}
            <div style="text-align: center;">
                <a href="{{ $password_link }}" class="button">Set Your Password</a>
            </div>
            <p><small>If the button doesn't work, copy and paste this link into your browser: {{ $password_link }}</small></p>
            <p>This link will expire in 24 hours for security reasons.</p>

            {{-- Next Steps Info Box --}}
            <div class="info-box">
                <h2>Next Steps</h2>
                <ul>
                    <li>Set your password using the link above</li>
                    <li>Log in to your account</li>
                    <li>Update your profile information if needed</li>
                    <li>Explore the member benefits and services</li>
                </ul>
            </div>

            <p>If you have any questions or need assistance, please don't hesitate to contact our support team at <a href="mailto:support@acics.org">support@acics.org</a> or call us at +234-XXX-XXX-XXXX.</p>
            <p>Welcome to the ACICS Cooperative Society family!</p>
            <p>Best regards,<br> The ACICS Membership Team</p>
        </div>

        {{-- Footer Section --}}
        <div class="footer">
            <p>&copy; {{ date('Y') }} ACICS Cooperative Society. All rights reserved.</p>
            <p>This email was sent to {{ $email }}. If you did not apply for membership, please disregard this email.</p>
        </div>
    </div>
</body>
</html>