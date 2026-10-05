<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to FinCore</title>
    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 24px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 16px; margin-bottom: 24px; text-align: center; }
        .logo { font-size: 24px; font-weight: 800; color: #1e3a8a; letter-spacing: -0.5px; }
        .subtitle { font-size: 14px; color: #64748b; margin-top: 4px; }
        .credentials-box { background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
        .credentials-title { font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .details-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .details-table td { padding: 8px 0; border-bottom: 1px dashed #cbd5e1; }
        .details-table td:last-child { border-bottom: none; }
        .details-table td.label { color: #64748b; font-weight: 500; width: 40%; }
        .details-table td.value { font-weight: 700; color: #0f172a; font-family: monospace; font-size: 15px; }
        .btn-container { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; background-color: #2563eb; color: #ffffff !important; font-weight: 600; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">FinCore Portal</div>
            <div class="subtitle">Your Customer Account Access Details</div>
        </div>

        <p style="font-size: 15px; color: #334155;">
            Dear <strong>{{ $user->name }}</strong>,
        </p>
        <p style="font-size: 14px; color: #475569; line-height: 1.5;">
            An account has been created for you on the FinCore Customer Portal. You can log in using the credentials below to manage your loans, view payment history, and make repayments online.
        </p>

        <div class="credentials-box">
            <div class="credentials-title">Login Credentials</div>
            <table class="details-table">
                <tr>
                    <td class="label">Email Address:</td>
                    <td class="value">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="label">Temporary Password:</td>
                    <td class="value">{{ $plainTextPassword }}</td>
                </tr>
            </table>
        </div>

        <div class="btn-container">
            <a href="{{ route('login') }}" class="btn">Log In to Customer Portal</a>
        </div>

        <p style="font-size: 13px; color: #64748b; line-height: 1.4; text-align: center;">
            For security reasons, we recommend changing your password after your initial login from your profile page.
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} FinCore Lending System. All rights reserved.<br>
            This is an automated security message. Please do not reply directly to this email.
        </div>
    </div>
</body>
</html>
