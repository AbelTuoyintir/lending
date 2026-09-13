@extends('layouts.app')

@section('title', 'Loan Statement ' . $loan->loan_number . ' | FinCore')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('loans.show', $loan) }}" class="text-xs font-semibold text-blue-600 hover:underline">
            &larr; Back to Loan Details
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-md hover:bg-slate-800 transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Statement
        </button>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-lg space-y-8 print:shadow-none print:border-none print:p-0">

        {{-- Header --}}
        <div class="flex items-start justify-between border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">FinCore Financial Services</h1>
                <p class="text-xs text-slate-500 mt-1">Accra, Ghana &bull; Phone: +233 24 000 0000 &bull; Email: support@fincore.com</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 font-bold text-xs rounded-full uppercase tracking-wider mb-1">
                    Official Loan Statement
                </span>
                <p class="text-xs text-slate-400">Statement Date: <span class="font-semibold text-slate-800">{{ now()->format('d F Y') }}</span></p>
            </div>
        </div>

        {{-- Details Grid --}}
        <div class="grid grid-cols-2 gap-6 p-5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
            <div>
                <p class="text-slate-400 font-semibold uppercase tracking-wider mb-1">Borrower Information</p>
                <p class="text-sm font-bold text-slate-900">{{ $loan->customer->full_name ?? 'N/A' }}</p>
                <p class="text-slate-500 mt-0.5">Customer #: {{ $loan->customer->customer_number ?? 'N/A' }}</p>
                <p class="text-slate-500">Phone: {{ $loan->customer->phone ?? 'N/A' }}</p>
            </div>

            <div class="text-right">
                <p class="text-slate-400 font-semibold uppercase tracking-wider mb-1">Loan Account Parameters</p>
                <p class="text-sm font-bold text-blue-600">{{ $loan->loan_number }}</p>
                <p class="text-slate-500 mt-0.5">Loan Date: {{ $loan->loan_date ? $loan->loan_date->format('d M Y') : 'N/A' }}</p>
                <p class="text-slate-500">Due Date: {{ $loan->due_date ? $loan->due_date->format('d M Y') : 'End of Month' }}</p>
            </div>
        </div>

        {{-- Financial Summary --}}
        <div class="grid grid-cols-4 gap-4 p-4 rounded-xl border border-slate-200 text-center text-xs">
            <div>
                <span class="text-slate-400 block mb-0.5">Principal Amount</span>
                <span class="font-bold text-slate-900 text-sm">GHS {{ number_format($loan->principal_amount, 2) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Initial 30% Interest</span>
                <span class="font-bold text-indigo-600 text-sm">GHS {{ number_format($loan->interest_amount, 2) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Total Repaid</span>
                <span class="font-bold text-emerald-600 text-sm">GHS {{ number_format($loan->total_paid, 2) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Current Balance Owed</span>
                <span class="font-extrabold text-red-600 text-sm">GHS {{ number_format($loan->outstanding_balance, 2) }}</span>
            </div>
        </div>

        {{-- Repayment Ledger --}}
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Repayment History Ledger</h3>

            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="p-3">Payment #</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Method</th>
                        <th class="p-3">Reference</th>
                        <th class="p-3 text-right">Amount Paid</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($loan->payments as $payment)
                        <tr>
                            <td class="p-3 font-bold text-slate-900">{{ $payment->payment_number }}</td>
                            <td class="p-3 text-slate-500">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : 'N/A' }}</td>
                            <td class="p-3 uppercase font-semibold text-slate-600">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                            <td class="p-3 font-mono text-slate-500">{{ $payment->reference }}</td>
                            <td class="p-3 text-right font-bold text-emerald-600">+ GHS {{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-slate-400">No repayment transactions recorded for this statement period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Current Outstanding Box --}}
        <div class="p-5 rounded-2xl bg-slate-900 text-white flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase text-amber-400 tracking-wider">Current Amount Owed</p>
                <p class="text-[11px] text-slate-400">Calculated inclusive of 30% initial interest & accrued monthly compound interest</p>
            </div>
            <p class="text-3xl font-extrabold text-white">
                GHS {{ number_format($loan->outstanding_balance, 2) }}
            </p>
        </div>

    </div>

</div>

@endsection