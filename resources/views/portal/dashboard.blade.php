@extends('layouts.portal')

@section('title', 'Customer Dashboard — De ferg money Lending Portal')

@section('content')
<div class="space-y-8">

    {{-- Welcome Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-blue-900 to-indigo-800 text-white rounded-2xl p-6 sm:p-8 shadow-md">
        <div class="space-y-1">
            <span class="text-xs font-bold text-blue-200 uppercase tracking-widest">Customer Portal</span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Good afternoon, {{ $customer->first_name }}</h1>
            <p class="text-xs text-blue-100 max-w-xl">
                Track your active loans, apply for new lending facilities, view your complete financial transaction history, and pay off your balance securely via Paystack.
            </p>
        </div>
        <div class="shrink-0 flex gap-2">
            <a href="{{ route('portal.loans.create') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-5 py-3 rounded-xl shadow transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Apply for Loan
            </a>
        </div>
    </div>

    {{-- Overview Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Outstanding Balance Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-amber-500">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Outstanding Balance</span>
            <div class="text-2xl font-black text-amber-600">GHS {{ number_format($totalOutstanding, 2) }}</div>
            <p class="text-[11px] text-slate-500">Remaining total payable balance</p>
        </div>

        {{-- Total Borrowed Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-blue-600">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Amount Borrowed</span>
            <div class="text-2xl font-black text-slate-900">GHS {{ number_format($totalBorrowed, 2) }}</div>
            <p class="text-[11px] text-slate-500">Total principal capital requested</p>
        </div>

        {{-- Total Paid Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-emerald-500">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Amount Paid</span>
            <div class="text-2xl font-black text-emerald-600">GHS {{ number_format($totalRepaid, 2) }}</div>
            <p class="text-[11px] text-slate-500">Cumulative repayments completed</p>
        </div>

        {{-- Current Interest Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-purple-500">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Current Interest</span>
            <div class="text-2xl font-black text-purple-600">GHS {{ number_format($totalInterest, 2) }}</div>
            <p class="text-[11px] text-slate-500">Total interest charges accrued</p>
        </div>

        {{-- Next Due Date Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-blue-500">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Next Due Date</span>
            <div class="text-xl font-extrabold text-slate-900">
                {{ $nextDueDate ? \Carbon\Carbon::parse($nextDueDate)->format('d M Y') : 'No Active Due Date' }}
            </div>
            <p class="text-[11px] text-slate-500">Upcoming repayment deadline</p>
        </div>

        {{-- Active Loans Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 border-l-4 border-l-indigo-500">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Number of Active Loans</span>
            <div class="text-2xl font-black text-slate-900">{{ $activeLoansCount }}</div>
            <p class="text-[11px] text-slate-500">Active borrowing facilities</p>
        </div>

    </div>

    {{-- Active Loans Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Your Active & Submitted Loans</h3>
                <p class="text-xs text-slate-500">Manage repayments, view statement details, or apply partial/full payment</p>
            </div>
            <a href="{{ route('portal.loans.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View All Loans &rarr;</a>
        </div>

        @if($loans->isEmpty())
            <div class="p-8 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">No loan records found.</p>
                <p class="text-xs text-slate-500">Ready to get started? Apply for a quick loan facility today.</p>
                <a href="{{ route('portal.loans.create') }}" class="inline-block bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl">Apply Now</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Loan Number</th>
                            <th class="px-5 py-3.5">Product</th>
                            <th class="px-5 py-3.5 text-right">Principal</th>
                            <th class="px-5 py-3.5 text-right">Total Payable</th>
                            <th class="px-5 py-3.5 text-right">Outstanding Balance</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($loans as $loan)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    <a href="{{ route('portal.loans.show', $loan) }}" class="hover:text-blue-600 hover:underline">
                                        {{ $loan->loan_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-700">
                                    {{ $loan->loanProduct->name ?? 'Standard Loan' }}
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                    GHS {{ number_format($loan->principal_amount, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                    GHS {{ number_format($loan->total_payable, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right font-extrabold text-amber-600">
                                    GHS {{ number_format($loan->outstanding_balance, 2) }}
                                </td>
                                <td class="px-5 py-4 text-center">
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
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border {{ $badgeClasses }}">
                                        {{ str_replace('_', ' ', $loan->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right space-x-2">
                                    <a href="{{ route('portal.loans.show', $loan) }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition">
                                        Details
                                    </a>
                                    @if($loan->outstanding_balance > 0 && in_array($loan->status, ['active', 'disbursed', 'partially_paid', 'overdue', 'defaulted']))
                                        <a href="{{ route('portal.loans.show', $loan) }}#pay" class="inline-block px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs shadow-sm transition">
                                            Pay via Paystack
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Recent Payments Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Recent Completed Repayments</h3>
                <p class="text-xs text-slate-500">Your recent payment transactions and instant receipt links</p>
            </div>
            <a href="{{ route('portal.payments.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View All Payments &rarr;</a>
        </div>

        @if($payments->isEmpty())
            <div class="p-6 text-center text-xs text-slate-400">
                No repayments recorded yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">Receipt #</th>
                            <th class="px-5 py-3">Loan #</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Method</th>
                            <th class="px-5 py-3 text-right">Amount Paid</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3 font-mono font-bold text-slate-900">{{ $payment->payment_number }}</td>
                                <td class="px-5 py-3 font-mono text-slate-600">{{ $payment->loan->loan_number }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : '—' }}</td>
                                <td class="px-5 py-3 uppercase font-medium text-slate-700">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="px-5 py-3 text-right font-black text-emerald-600">GHS {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('portal.payments.receipt', $payment) }}" class="text-blue-600 hover:underline font-bold">
                                        View Receipt
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
