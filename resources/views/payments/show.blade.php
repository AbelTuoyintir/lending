@extends('layouts.app')

@section('title', 'Receipt ' . $payment->payment_number . ' | FinCore')
@section('page-title', 'Payment Receipt Details')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Top Action Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('payments.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Payments</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Official Repayment Receipt</h1>
            <p class="text-xs text-slate-500">Receipt #: <span class="font-mono font-bold text-slate-800">{{ $payment->payment_number }}</span></p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Receipt
            </button>
        </div>
    </div>

    {{-- Professional Receipt Card --}}
    <div id="printableReceipt" class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm space-y-6 border-t-8 border-t-blue-600">

        {{-- Header Logo & Details --}}
        <div class="flex items-start justify-between border-b border-slate-100 pb-6">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-sm flex items-center justify-center">F</div>
                    <span class="font-extrabold text-xl text-slate-900 tracking-tight">FinCore</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Administrative Financial Services</p>
                <p class="text-[11px] text-slate-400">Accra, Ghana • Tel: +233 (0) 30 200 0000</p>
            </div>

            <div class="text-right">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-extrabold text-xs rounded-full uppercase">COMPLETED</span>
                <p class="text-xs text-slate-400 mt-2">Receipt No:</p>
                <p class="font-mono font-bold text-slate-900 text-sm">{{ $payment->payment_number }}</p>
            </div>
        </div>

        {{-- Customer & Loan Info --}}
        <div class="grid grid-cols-2 gap-6 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <p class="text-slate-400 uppercase font-bold text-[10px]">Received From Borrower</p>
                <p class="font-bold text-slate-900 text-sm mt-1">{{ $payment->customer->full_name }}</p>
                <p class="text-slate-600">Phone: {{ $payment->customer->phone }}</p>
                <p class="text-slate-600">ID: {{ $payment->customer->customer_number }}</p>
                <div class="mt-2">
                    <a href="{{ route('customers.show', $payment->customer) }}" class="text-blue-600 hover:underline font-semibold no-print">View Customer Profile →</a>
                </div>
            </div>

            <div class="text-right">
                <p class="text-slate-400 uppercase font-bold text-[10px]">Applied To Loan</p>
                <p class="font-mono font-bold text-blue-600 text-sm mt-1">{{ $payment->loan->loan_number }}</p>
                <p class="text-slate-600">Loan Date: {{ $payment->loan->loan_date ? $payment->loan->loan_date->format('d M Y') : 'N/A' }}</p>
                <p class="text-slate-600">Recorded By: Administrator</p>
                <div class="mt-2">
                    <a href="{{ route('loans.show', $payment->loan) }}" class="text-blue-600 hover:underline font-semibold no-print">View Loan Details →</a>
                </div>
            </div>
        </div>

        {{-- Payment Breakdown Table --}}
        <div class="space-y-2">
            <p class="font-bold text-xs uppercase tracking-wider text-slate-500">Payment Breakdown & Allocation</p>
            <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-2.5">Allocation Component</th>
                        <th class="px-4 py-2.5 text-right">Amount Allocated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-2.5 text-slate-700">Principal Portion</td>
                        <td class="px-4 py-2.5 text-right font-semibold text-slate-900">GHS {{ number_format($principalPortion, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2.5 text-slate-700">Initial Interest Portion</td>
                        <td class="px-4 py-2.5 text-right font-semibold text-blue-600">GHS {{ number_format($interestPortion, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2.5 text-slate-700">Compound Interest Portion</td>
                        <td class="px-4 py-2.5 text-right font-semibold text-purple-600">GHS {{ number_format($compoundInterestPortion, 2) }}</td>
                    </tr>
                    <tr class="bg-emerald-50/50 font-bold border-t border-slate-200 text-sm">
                        <td class="px-4 py-3 text-slate-900">Total Amount Paid</td>
                        <td class="px-4 py-3 text-right text-emerald-600">GHS {{ number_format($payment->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Financial Status Summary --}}
        <div class="grid grid-cols-2 gap-4 text-xs pt-2">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                <p class="text-slate-400 font-medium">Payment Method</p>
                <p class="font-bold text-slate-900 uppercase mt-0.5">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                <p class="text-slate-500 font-mono text-[11px]">Ref: {{ $payment->reference }}</p>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-right">
                <p class="text-slate-400 font-medium">Remaining Outstanding Balance</p>
                <p class="font-extrabold text-amber-600 text-sm mt-0.5">GHS {{ number_format($payment->loan->outstanding_balance, 2) }}</p>
                <p class="text-slate-500 text-[11px]">Due: {{ $payment->loan->maturity_date ? $payment->loan->maturity_date->format('d M Y') : 'Month-End' }}</p>
            </div>
        </div>

        {{-- Notes --}}
        @if($payment->notes)
            <div class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 italic">
                <strong>Remarks:</strong> {{ $payment->notes }}
            </div>
        @endif

        {{-- Signature Footer --}}
        <div class="border-t border-slate-200 pt-6 flex items-center justify-between text-[11px] text-slate-400">
            <div>
                <p>Payment Date: <span class="font-semibold text-slate-700">{{ $payment->payment_date->format('d F Y') }}</span></p>
                <p class="mt-0.5">Authorized Administrator Signature: _______________________</p>
            </div>
            <div class="text-right">
                <p>Thank you for your repayment.</p>
                <p class="font-mono text-[10px] text-slate-300">CONFIDENTIAL FINANCIAL RECEIPT</p>
            </div>
        </div>

    </div>

</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printableReceipt, #printableReceipt * { visibility: visible; }
    #printableReceipt { position: absolute; left: 0; top: 0; width: 100%; border: none; shadow: none; }
    .no-print { display: none !important; }
}
</style>
@endsection