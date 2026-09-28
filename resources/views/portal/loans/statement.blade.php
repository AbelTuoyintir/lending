@extends('layouts.portal')

@section('title', 'Loan Statement ' . $loan->loan_number . ' — FinCore')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('portal.loans.show', $loan) }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Loan Details
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / Download Statement
        </button>
    </div>

    {{-- Printable Statement Document --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-8 print:border-0 print:shadow-none print:p-0">

        {{-- Header --}}
        <div class="flex justify-between items-start border-b border-slate-200 pb-6">
            <div>
                <div class="font-black text-2xl text-slate-900">FinCore Lending</div>
                <p class="text-xs text-slate-500">Official Comprehensive Loan Account Statement</p>
            </div>
            <div class="text-right text-xs space-y-0.5">
                <span class="font-bold text-slate-900">Statement Date: {{ now()->format('M d, Y') }}</span>
                <p class="font-mono text-slate-500">Loan #: {{ $loan->loan_number }}</p>
                <p class="font-bold text-blue-600 uppercase">{{ str_replace('_', ' ', $loan->status) }}</p>
            </div>
        </div>

        {{-- Customer & Summary --}}
        <div class="grid grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-100 text-xs">
            <div>
                <h4 class="font-bold text-slate-400 uppercase tracking-wider mb-2">Customer Info</h4>
                <p class="font-bold text-slate-900 text-sm">{{ $customer->full_name }}</p>
                <p class="text-slate-600">Customer ID: {{ $customer->customer_number }}</p>
                <p class="text-slate-600">Phone: {{ $customer->phone }}</p>
            </div>
            <div class="text-right space-y-1">
                <h4 class="font-bold text-slate-400 uppercase tracking-wider mb-2">Financial Balance</h4>
                <p class="text-xs text-slate-600">Original Principal: <span class="font-bold text-slate-900">GHS {{ number_format($loan->principal_amount, 2) }}</span></p>
                <p class="text-xs text-slate-600">Total Repaid: <span class="font-bold text-emerald-600">GHS {{ number_format($loan->amount_paid, 2) }}</span></p>
                <p class="text-sm font-extrabold text-amber-600">Current Outstanding: GHS {{ number_format($loan->outstanding_balance, 2) }}</p>
            </div>
        </div>

        {{-- Comprehensive Ledger Activity Table --}}
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Statement Transaction Ledger</h3>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Transaction Description</th>
                            <th class="px-4 py-3 text-right">Debit (+)</th>
                            <th class="px-4 py-3 text-right">Credit (-)</th>
                            <th class="px-4 py-3 text-right">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        {{-- Initial Loan Issue --}}
                        <tr>
                            <td class="px-4 py-3 text-slate-500">{{ $loan->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">Loan Facility Created ({{ $loan->loanProduct->name ?? 'Standard' }})</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-slate-500">{{ $loan->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-blue-700">Initial Interest Charge ({{ $loan->interest_rate }}%)</td>
                            <td class="px-4 py-3 text-right font-bold text-blue-600">GHS {{ number_format($loan->interest_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-slate-400">—</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">GHS {{ number_format($loan->total_payable, 2) }}</td>
                        </tr>

                        {{-- Interest Cycles --}}
                        @foreach($loan->interestCycles as $cycle)
                            <tr class="bg-purple-50/30">
                                <td class="px-4 py-3 text-slate-500">{{ $cycle->cycle_date ? \Carbon\Carbon::parse($cycle->cycle_date)->format('M d, Y') : '—' }}</td>
                                <td class="px-4 py-3 font-semibold text-purple-800">Monthly Compound Interest Charge</td>
                                <td class="px-4 py-3 text-right font-bold text-purple-700">+ GHS {{ number_format($cycle->interest_charged, 2) }}</td>
                                <td class="px-4 py-3 text-right text-slate-400">—</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">GHS {{ number_format($cycle->closing_balance, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- Payments --}}
                        @foreach($loan->payments as $payment)
                            <tr class="bg-emerald-50/30">
                                <td class="px-4 py-3 text-slate-500">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '—' }}</td>
                                <td class="px-4 py-3 font-semibold text-emerald-800">Payment Received ({{ $payment->payment_number }})</td>
                                <td class="px-4 py-3 text-right text-slate-400">—</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600">- GHS {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-3 text-right font-black text-slate-900">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-6 text-center text-xs text-slate-400">
            Official digital statement generated by FinCore Customer Lending Portal.
        </div>

    </div>

</div>
@endsection
