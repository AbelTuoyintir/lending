@extends('layouts.portal')

@section('title', 'Payment Receipt ' . $payment->payment_number . ' — FinCore')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('portal.payments.index') }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Payments
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Receipt
        </button>
    </div>

    {{-- Printable Receipt Document --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-8 print:border-0 print:shadow-none print:p-0">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-6 gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 text-white font-extrabold text-xl px-3 py-1 rounded-lg">FinCore</div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Lending Portal</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Official Electronic Payment Receipt</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ $payment->status === 'completed' ? 'Paid & Verified' : ucfirst($payment->status) }}
                </span>
                <p class="text-xs font-mono text-slate-400 mt-1">Receipt #: {{ $payment->payment_number }}</p>
                <p class="text-xs text-slate-500">Date: {{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : now()->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        {{-- Payer & Payment Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-100">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Customer Details</h4>
                <p class="text-base font-bold text-slate-900">{{ $payment->customer->full_name }}</p>
                <p class="text-xs text-slate-600">Customer #: {{ $payment->customer->customer_number }}</p>
                <p class="text-xs text-slate-600">Phone: {{ $payment->customer->phone }}</p>
                @if($payment->customer->email)
                    <p class="text-xs text-slate-600">Email: {{ $payment->customer->email }}</p>
                @endif
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Payment Details</h4>
                <p class="text-xs text-slate-600"><span class="font-semibold text-slate-700">Payment Method:</span> {{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</p>
                <p class="text-xs text-slate-600"><span class="font-semibold text-slate-700">Transaction Reference:</span> {{ $payment->reference }}</p>
                <p class="text-xs text-slate-600"><span class="font-semibold text-slate-700">Loan Number:</span> {{ $payment->loan->loan_number }}</p>
                <p class="text-xs text-slate-600"><span class="font-semibold text-slate-700">Loan Product:</span> {{ $payment->loan->loanProduct->name ?? 'Standard Loan' }}</p>
            </div>
        </div>

        {{-- Payment Summary Banner --}}
        <div class="bg-emerald-500/10 border border-emerald-200 rounded-xl p-6 text-center space-y-1">
            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Total Amount Paid</span>
            <div class="text-3xl font-black text-emerald-700">GHS {{ number_format($payment->amount, 2) }}</div>
            <p class="text-xs text-emerald-600 font-medium">Ghanaian Cedi (GHS)</p>
        </div>

        {{-- Payment & Loan Balance Breakdown Table --}}
        <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Payment Breakdown & Balance Impact</h4>
            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Financial Item</th>
                            <th class="px-4 py-3 text-right">Amount (GHS)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700">
                        @php
                            $previousBalance = (float) $payment->loan->outstanding_balance + (float) $payment->amount;
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium">Previous Balance</td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-900">GHS {{ number_format($previousBalance, 2) }}</td>
                        </tr>
                        <tr class="bg-emerald-50/40">
                            <td class="px-4 py-3 font-bold text-emerald-900">Payment Amount</td>
                            <td class="px-4 py-3 text-right font-black text-emerald-600">- GHS {{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        <tr class="bg-amber-50/50">
                            <td class="px-4 py-3 font-bold text-amber-900">New Remaining Balance</td>
                            <td class="px-4 py-3 text-right font-extrabold text-amber-700">GHS {{ number_format($payment->loan->outstanding_balance, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer & Verification Note --}}
        <div class="border-t border-slate-200 pt-6 text-center space-y-2">
            <p class="text-xs text-slate-500">Thank you for your prompt repayment!</p>
            <p class="text-[10px] text-slate-400 font-mono">This digital receipt is auto-generated by FinCore Customer Lending Portal and is legally valid without manual signature.</p>
        </div>

    </div>

</div>
@endsection
