<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 24px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 16px; margin-bottom: 24px; text-align: center; }
        .logo { font-size: 24px; font-weight: 800; color: #1e3a8a; letter-spacing: -0.5px; }
        .subtitle { font-size: 14px; color: #64748b; margin-top: 4px; }
        .amount-box { background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px; text-align: center; margin-bottom: 24px; }
        .amount-label { font-size: 12px; text-transform: uppercase; color: #166534; font-weight: 700; letter-spacing: 0.5px; }
        .amount-value { font-size: 28px; font-weight: 800; color: #15803d; margin-top: 4px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 14px; }
        .details-table td { padding: 10px 0; border-bottom: 1px dashed #e2e8f0; }
        .details-table td.label { color: #64748b; font-weight: 500; }
        .details-table td.value { text-align: right; font-weight: 700; color: #0f172a; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">De ferg money Lending</div>
            <div class="subtitle">Official Payment Receipt</div>
        </div>

        <p style="font-size: 15px; color: #334155;">
            Dear <strong>{{ $payment->customer->full_name }}</strong>,
        </p>
        <p style="font-size: 14px; color: #475569; line-height: 1.5;">
            Thank you for your payment. We have successfully processed your payment for loan <strong>{{ $payment->loan->loan_number }}</strong>.
        </p>

        <div class="amount-box">
            <div class="amount-label">Amount Paid</div>
            <div class="amount-value">GHS {{ number_format($payment->amount, 2) }}</div>
        </div>

        <table class="details-table">
            <tr>
                <td class="label">Receipt / Payment Number</td>
                <td class="value">{{ $payment->payment_number }}</td>
            </tr>
            <tr>
                <td class="label">Payment Date</td>
                <td class="value">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : now()->format('M d, Y h:i A') }}</td>
            </tr>
            <tr>
                <td class="label">Payment Method</td>
                <td class="value">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</td>
            </tr>
            <tr>
                <td class="label">Payment Reference</td>
                <td class="value">{{ $payment->reference }}</td>
            </tr>
            <tr>
                <td class="label">Loan Number</td>
                <td class="value">{{ $payment->loan->loan_number }}</td>
            </tr>
            <tr>
                <td class="label">Remaining Loan Balance</td>
                <td class="value" style="color: #d97706;">GHS {{ number_format($payment->loan->outstanding_balance, 2) }}</td>
            </tr>
        </table>

        <p style="font-size: 13px; color: #64748b; line-height: 1.4; text-align: center;">
            You can view your complete financial history and current loan status anytime by logging into your De ferg money User Portal.
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} De ferg money Lending System. All rights reserved.<br>
            This is an automated receipt generated upon payment processing.
        </div>
    </div>
</body>
</html>
