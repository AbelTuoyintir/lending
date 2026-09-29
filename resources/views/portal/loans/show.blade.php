@extends('layouts.portal')

@section('title', 'Loan ' . $loan->loan_number . ' — FinCore Portal')

@section('content')
<div class="space-y-6" x-data="{ payAmount: '{{ $loan->outstanding_balance }}', payMode: 'full' }">

    {{-- Breadcrumb & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('portal.loans.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 mb-1 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to My Loans
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                Loan {{ $loan->loan_number }}
                @php
                    $badgeClasses = match($loan->status) {
                        'active', 'disbursed' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'partially_paid' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'fully_paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                        'defaulted', 'overdue' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                    };
                @endphp
                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full border {{ $badgeClasses }}">
                    {{ str_replace('_', ' ', $loan->status) }}
                </span>
            </h1>
            <p class="text-xs text-slate-500">Issued on {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('M d, Y') : '—' }} | {{ $loan->loanProduct->name ?? 'Standard Loan' }}</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('portal.loans.agreement', $loan) }}" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-3.5 py-2 rounded-xl transition border border-slate-200">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Loan Agreement
            </a>

            <a href="{{ route('portal.loans.statement', $loan) }}" class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs px-3.5 py-2 rounded-xl transition border border-blue-200">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Statement
            </a>

            @if($loan->outstanding_balance > 0 && in_array($loan->status, ['active', 'disbursed', 'partially_paid', 'overdue', 'defaulted']))
                <a href="#pay" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Pay via Paystack
                </a>
            @endif
        </div>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Principal Amount</span>
            <div class="text-xl font-black text-slate-900">GHS {{ number_format($loan->principal_amount, 2) }}</div>
            <p class="text-[11px] text-slate-500">Requested loan capital</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Payable</span>
            <div class="text-xl font-black text-slate-900">GHS {{ number_format($loan->total_payable, 2) }}</div>
            <p class="text-[11px] text-slate-500">Principal + Initial Interest</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Amount Paid</span>
            <div class="text-xl font-black text-emerald-600">GHS {{ number_format($loan->amount_paid, 2) }}</div>
            <p class="text-[11px] text-slate-500">Total repayments completed</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1 bg-amber-50/40 border-amber-200">
            <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">Outstanding Balance</span>
            <div class="text-2xl font-black text-amber-600">GHS {{ number_format($loan->outstanding_balance, 2) }}</div>
            <p class="text-[11px] text-amber-700 font-medium">Remaining balance due</p>
        </div>

    </div>

    {{-- Paystack Payment Section --}}
    @if($loan->outstanding_balance > 0 && in_array($loan->status, ['active', 'disbursed', 'partially_paid', 'overdue', 'defaulted']))
        <div id="pay" class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-2xl p-6 sm:p-8 shadow-xl space-y-6">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-emerald-500 text-white font-extrabold text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Make Loan Repayment via Paystack</h3>
                        <p class="text-xs text-slate-400">Pay securely with Mobile Money (MTN, Vodafone, Telecel) or Bank Card</p>
                    </div>
                </div>
                <span class="text-xs font-extrabold text-emerald-400 bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-800">
                    Paystack Gateway Enabled
                </span>
            </div>

            <form action="{{ route('portal.payments.paystack.initialize') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="loan_id" value="{{ $loan->id }}">

                {{-- Payment Option Selection --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="p-4 rounded-xl border cursor-pointer transition flex items-center justify-between"
                           :class="payMode === 'full' ? 'bg-indigo-900/60 border-emerald-500 ring-2 ring-emerald-500/50' : 'bg-slate-800/60 border-slate-700 hover:border-slate-600'"
                           @click="payMode = 'full'; payAmount = '{{ $loan->outstanding_balance }}'">
                        <div>
                            <span class="block text-xs font-bold uppercase text-slate-300">Full Payment</span>
                            <span class="text-lg font-black text-emerald-400">GHS {{ number_format($loan->outstanding_balance, 2) }}</span>
                        </div>
                        <input type="radio" name="pay_option" value="full" checked class="text-emerald-500 focus:ring-emerald-500">
                    </label>

                    <label class="p-4 rounded-xl border cursor-pointer transition flex items-center justify-between"
                           :class="payMode === 'partial' ? 'bg-indigo-900/60 border-emerald-500 ring-2 ring-emerald-500/50' : 'bg-slate-800/60 border-slate-700 hover:border-slate-600'"
                           @click="payMode = 'partial'; payAmount = '50'">
                        <div>
                            <span class="block text-xs font-bold uppercase text-slate-300">Partial Payment</span>
                            <span class="text-xs text-slate-400">Pay custom amount</span>
                        </div>
                        <input type="radio" name="pay_option" value="partial" class="text-emerald-500 focus:ring-emerald-500">
                    </label>
                </div>

                {{-- Custom Amount Input --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Payment Amount (GHS) *
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-bold text-sm">GHS</span>
                        <input type="number" step="0.01" min="1" max="{{ $loan->outstanding_balance }}" name="amount" x-model="payAmount" required
                               class="w-full text-base font-bold bg-slate-800 border border-slate-700 text-white rounded-xl pl-14 pr-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <p class="text-[11px] text-slate-400">Maximum payable balance: GHS {{ number_format($loan->outstanding_balance, 2) }}</p>
                </div>

                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold py-3.5 px-6 rounded-xl shadow-lg transition text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Proceed to Paystack Checkout &rarr;
                </button>
            </form>
        </div>
    @endif

    {{-- Interest / Balance Timeline --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Interest & Balance Timeline</h3>
            <p class="text-xs text-slate-500">Complete month-by-month transparency showing opening balance, compound interest, payments made, and remaining balance.</p>
        </div>

        <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">Month / Cycle</th>
                        <th class="px-4 py-3 text-right">Opening Balance</th>
                        <th class="px-4 py-3 text-right">Interest Charged (+30%)</th>
                        <th class="px-4 py-3 text-right">Payment Received (-)</th>
                        <th class="px-4 py-3 text-right">Remaining Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    {{-- First Month Initial Creation --}}
                    <tr>
                        <td class="px-4 py-3 font-bold text-slate-900">Initial Issue</td>
                        <td class="px-4 py-3 text-right">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-blue-600">+ GHS {{ number_format($loan->interest_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right text-slate-400">—</td>
                        <td class="px-4 py-3 text-right font-bold text-slate-900">GHS {{ number_format($loan->total_payable, 2) }}</td>
                    </tr>

                    @forelse($loan->interestCycles as $cycle)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold text-slate-800">
                                {{ $cycle->cycle_date ? \Carbon\Carbon::parse($cycle->cycle_date)->format('M Y') : 'Cycle' }}
                            </td>
                            <td class="px-4 py-3 text-right font-medium">GHS {{ number_format($cycle->opening_balance, 2) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-purple-600">+ GHS {{ number_format($cycle->interest_charged, 2) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-600">- GHS {{ number_format($cycle->payment_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right font-extrabold text-slate-900">GHS {{ number_format($cycle->closing_balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-slate-400 italic">
                                Initial interest applied. Month-end compound cycles will record as billing cycles progress.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Loan Details & Schedule --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-6 p-6">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Loan Terms & Details</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-xs text-slate-600">
            <div>
                <span class="text-slate-400 block font-semibold">Duration</span>
                <span class="text-sm font-bold text-slate-900">{{ $loan->duration }} Month(s)</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Interest Rate</span>
                <span class="text-sm font-bold text-blue-600">{{ $loan->interest_rate }}% {{ ucfirst($loan->interest_type) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Repayment Frequency</span>
                <span class="text-sm font-bold text-slate-900">{{ ucfirst($loan->repayment_frequency) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Disbursement Date</span>
                <span class="text-sm font-bold text-slate-900">{{ $loan->disbursement_date ? \Carbon\Carbon::parse($loan->disbursement_date)->format('M d, Y') : 'Not Disbursed' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Maturity Date</span>
                <span class="text-sm font-bold text-slate-900">{{ $loan->maturity_date ? \Carbon\Carbon::parse($loan->maturity_date)->format('M d, Y') : '—' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">First Payment Date</span>
                <span class="text-sm font-bold text-slate-900">{{ $loan->first_payment_date ? \Carbon\Carbon::parse($loan->first_payment_date)->format('M d, Y') : '—' }}</span>
            </div>
        </div>
    </div>

    {{-- Repayment History --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Loan Payment History</h3>
            <p class="text-xs text-slate-500">All payments recorded for this specific loan facility</p>
        </div>

        @if($loan->payments->isEmpty())
            <div class="p-6 text-center text-xs text-slate-400">
                No repayments recorded for this loan yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">Receipt #</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Method</th>
                            <th class="px-5 py-3">Reference</th>
                            <th class="px-5 py-3 text-right">Amount</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($loan->payments as $payment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900">{{ $payment->payment_number }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : '—' }}</td>
                                <td class="px-5 py-3.5 uppercase font-medium text-slate-700">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="px-5 py-3.5 font-mono text-slate-500">{{ $payment->reference }}</td>
                                <td class="px-5 py-3.5 text-right font-black text-emerald-600">GHS {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('portal.payments.receipt', $payment) }}" class="text-blue-600 font-bold hover:underline">
                                        Receipt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
