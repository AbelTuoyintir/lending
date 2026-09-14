@extends('layouts.app')

@section('title', 'Loan ' . $loan->loan_number . ' | FinCore')
@section('page-title', 'Loan Details')

@section('content')
<div class="space-y-8">

    {{-- Top Action Header --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('loans.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Loans</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-md">{{ $loan->loan_number }}</span>
                <x-status-badge :status="$loan->status" />
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-2">
                Borrower: {{ $loan->customer->full_name }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Loan Issued: <span class="font-semibold text-slate-700">{{ $loan->loan_date ? $loan->loan_date->format('d M Y') : 'N/A' }}</span> •
                Repayment Due Date: <span class="font-bold text-slate-900">{{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Calendar Month-End' }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($loan->status === 'pending')
                <form action="{{ route('loans.approve', $loan) }}" method="POST" onsubmit="return confirm('Approve this loan application?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-md transition">
                        ✓ Approve Loan
                    </button>
                </form>
            @endif

            @if($loan->status === 'approved')
                <button type="button" @click="Swal.fire({
                    title: 'Disburse Loan',
                    text: 'Confirm disbursement of GHS {{ number_format($loan->principal_amount, 2) }} to customer?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Disburse Funds',
                    confirmButtonColor: '#2563eb'
                }).then((r) => { if (r.isConfirmed) document.getElementById('disburseForm').submit(); })" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-md transition">
                    ⚡ Disburse Funds
                </button>
                <form id="disburseForm" action="{{ route('loans.disburse', $loan) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="financial_account_id" value="1">
                    <input type="hidden" name="disbursement_date" value="{{ date('Y-m-d') }}">
                </form>
            @endif

            @if(in_array($loan->status, ['disbursed', 'active', 'partially_paid', 'overdue']))
                <a href="{{ route('payments.create', $loan) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/20 transition">
                    + Record Payment
                </a>
            @endif

            @if(in_array($loan->status, ['pending', 'approved']))
                <form action="{{ route('loans.cancel', $loan) }}" method="POST" onsubmit="return confirm('Cancel this loan?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Cancel Loan
                    </button>
                </form>
            @endif

            <a href="{{ route('loans.statement', $loan) }}" target="_blank" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Statement
            </a>
        </div>
    </div>

    {{-- Financial Summary Cards Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Principal</p>
            <p class="text-lg font-extrabold text-slate-900 mt-1">GHS {{ number_format($loan->principal_amount, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Initial Interest</p>
            <p class="text-lg font-extrabold text-blue-600 mt-1">GHS {{ number_format($loan->interest_amount, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Payable</p>
            <p class="text-lg font-extrabold text-slate-900 mt-1">GHS {{ number_format($loan->total_payable, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Amount Paid</p>
            <p class="text-lg font-extrabold text-emerald-600 mt-1">GHS {{ number_format($loan->amount_paid, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Outstanding</p>
            <p class="text-lg font-extrabold text-amber-600 mt-1">GHS {{ number_format($loan->outstanding_balance, 2) }}</p>
        </div>

        @php
            $accumulatedCompound = $loan->interestCycles->sum('interest_charged');
        @endphp
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Compound Interest</p>
            <p class="text-lg font-extrabold text-purple-600 mt-1">GHS {{ number_format($accumulatedCompound, 2) }}</p>
        </div>
    </div>

    {{-- Monthly Compound Interest Timeline --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Monthly Compound Interest Calculation Timeline</h3>
                <p class="text-xs text-slate-500 mt-0.5">30% compound interest applied to remaining balance at month-end rollover.</p>
            </div>
            <span class="text-xs bg-slate-100 font-mono text-slate-600 px-3 py-1 rounded-full font-semibold">Rule: 30% of Remaining Balance</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Calculation Period</th>
                        <th class="px-4 py-3 text-right">Opening Balance</th>
                        <th class="px-4 py-3 text-center">Interest Rate</th>
                        <th class="px-4 py-3 text-right">Interest Charged</th>
                        <th class="px-4 py-3 text-right">Payments</th>
                        <th class="px-4 py-3 text-right">Closing Balance</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- Initial Month --}}
                    <tr class="bg-blue-50/30 font-medium">
                        <td class="px-4 py-3.5 font-bold text-slate-900">
                            {{ $loan->loan_date ? $loan->loan_date->format('F Y') : 'Initial Period' }} (Origination)
                        </td>
                        <td class="px-4 py-3.5 text-right">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                        <td class="px-4 py-3.5 text-center font-bold text-blue-600">30%</td>
                        <td class="px-4 py-3.5 text-right font-bold text-blue-600">GHS {{ number_format($loan->interest_amount, 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-bold text-emerald-600">GHS {{ number_format($loan->amount_paid, 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-bold text-slate-900">GHS {{ number_format($loan->total_payable - $loan->amount_paid, 2) }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold">INITIAL</span>
                        </td>
                    </tr>

                    @forelse($loan->interestCycles as $cycle)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $cycle->year_month }}</td>
                            <td class="px-4 py-3.5 text-right text-slate-700">GHS {{ number_format($cycle->opening_balance, 2) }}</td>
                            <td class="px-4 py-3.5 text-center font-semibold text-purple-600">{{ number_format($cycle->interest_rate, 0) }}%</td>
                            <td class="px-4 py-3.5 text-right font-bold text-purple-600">+ GHS {{ number_format($cycle->interest_charged, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-emerald-600">- GHS {{ number_format($cycle->payment_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-amber-600">GHS {{ number_format($cycle->closing_balance, 2) }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold uppercase">{{ $cycle->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-slate-400 text-xs italic">
                                No month-end compound interest cycles recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Loan Payment History --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Recorded Payment History</h3>
            @if(in_array($loan->status, ['disbursed', 'active', 'partially_paid', 'overdue']))
                <a href="{{ route('payments.create', $loan) }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                    + Record Payment
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Payment #</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3 text-right">Amount Paid</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loan->payments as $payment)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-mono font-bold text-blue-600">
                                <a href="{{ route('payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 uppercase text-[10px] font-semibold text-slate-500">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                            <td class="px-4 py-3.5 font-mono text-slate-600">{{ $payment->reference }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-emerald-600">+ GHS {{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('payments.show', $payment) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">Receipt</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">No payments have been recorded for this loan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection