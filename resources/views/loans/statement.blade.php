<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Statement - {{ $loan->loan_number }} | FinCore</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; font-size: 13px; color: #0f172a; margin: 0; padding: 40px; }
        .header { display: flex; justify-content: space-between; border-b: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: 800; color: #1e3a8a; margin: 0; }
        .subtitle { font-size: 12px; color: #64748b; margin-top: 4px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; }
        .card h4 { margin: 0 0 10px 0; font-size: 11px; text-transform: uppercase; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-size: 11px; text-transform: uppercase; color: #475569; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .total-box { background: #1e293b; color: white; padding: 20px; border-radius: 8px; text-align: right; margin-top: 20px; }
        .no-print { margin-bottom: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Print Statement</button>
    </div>

    <div class="header">
        <div>
            <h1 class="title">FinCore</h1>
            <p class="subtitle">Administrative Financial & Loan Management System</p>
        </div>
        <div style="text-align: right;">
            <h3 style="margin: 0; color: #2563eb;">OFFICIAL LOAN STATEMENT</h3>
            <p class="subtitle">Date: {{ date('d F Y') }}</p>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <h4>Borrower Information</h4>
            <p style="margin: 0; font-size: 15px; font-weight: bold;">{{ $loan->customer->full_name }}</p>
            <p style="margin: 4px 0 0 0; color: #475569;">Customer #: {{ $loan->customer->customer_number }}</p>
            <p style="margin: 2px 0 0 0; color: #475569;">Phone: {{ $loan->customer->phone }}</p>
        </div>

        <div class="card">
            <h4>Lending Details</h4>
            <p style="margin: 0; font-size: 15px; font-weight: bold;">Loan #: {{ $loan->loan_number }}</p>
            <p style="margin: 4px 0 0 0; color: #475569;">Disbursed: {{ $loan->disbursement_date ? $loan->disbursement_date->format('d M Y') : 'N/A' }}</p>
            <p style="margin: 2px 0 0 0; color: #475569;">Due Date: {{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Calendar Month-End' }}</p>
        </div>
    </div>

    <h4>Financial Summary</h4>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount (GHS)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Principal Amount Disbursed</td>
                <td class="text-right font-bold">GHS {{ number_format($loan->principal_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Initial Interest (30%)</td>
                <td class="text-right font-bold">GHS {{ number_format($loan->interest_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Accumulated Month-End Compound Interest (30%)</td>
                <td class="text-right font-bold">GHS {{ number_format($loan->interestCycles->sum('interest_amount'), 2) }}</td>
            </tr>
            <tr>
                <td>Total Payments Collected</td>
                <td class="text-right font-bold" style="color: #059669;">- GHS {{ number_format($loan->amount_paid, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <p style="margin: 0; font-size: 12px; opacity: 0.8; text-transform: uppercase;">Current Balance Owed</p>
        <h2 style="margin: 5px 0 0 0; font-size: 28px;">GHS {{ number_format($loan->outstanding_balance, 2) }}</h2>
    </div>

</body>
</html>