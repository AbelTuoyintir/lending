@extends('layouts.app')

@section('title', 'Transactions Ledger | FinCore')
@section('page-title', 'Financial Transactions Ledger')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                General Financial Ledger
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Complete double-entry audit record of disbursements, payments, interest charges, and adjustments.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Ledger CSV
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">Total Disbursements Debited</p>
            <h3 class="text-xl font-bold text-slate-900 mt-1">GHS {{ number_format($totalDisbursements, 2) }}</h3>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">Total Repayments Credited</p>
            <h3 class="text-xl font-bold text-emerald-600 mt-1">GHS {{ number_format($totalCollections, 2) }}</h3>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">Total Interest Charges Credited</p>
            <h3 class="text-xl font-bold text-indigo-600 mt-1">GHS {{ number_format($totalInterestCharged, 2) }}</h3>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transaction ID, reference, customer name..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <select name="type" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">All Transaction Types</option>
                        <option value="loan_disbursement" @selected(request('type') === 'loan_disbursement')>Loan Disbursement</option>
                        <option value="loan_payment" @selected(request('type') === 'loan_payment')>Loan Payment</option>
                        <option value="interest_charge" @selected(request('type') === 'interest_charge')>Initial Interest Charge</option>
                        <option value="compound_interest" @selected(request('type') === 'compound_interest')>Compound Interest Charge</option>
                        <option value="adjustment" @selected(request('type') === 'adjustment')>Adjustment</option>
                        <option value="refund" @selected(request('type') === 'refund')>Refund</option>
                    </select>
                </div>

                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" title="Transaction Date From" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'type', 'date_from']))
                        <a href="{{ route('transactions.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Transaction ID</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5">Type</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Loan Number</th>
                        <th class="px-5 py-3.5 text-right">Debit</th>
                        <th class="px-5 py-3.5 text-right">Credit</th>
                        <th class="px-5 py-3.5 text-right">Balance After</th>
                        <th class="px-5 py-3.5">Reference</th>
                        <th class="px-5 py-3.5 text-center">Recorded By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $txn)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-slate-800">
                                {{ $txn->transaction_number }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 font-medium">
                                {{ $txn->transaction_date ? $txn->transaction_date->format('d M Y') : $txn->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $txn->type) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                {{ $txn->customer->full_name ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5 font-mono font-bold text-blue-600">
                                {{ $txn->loan->loan_number ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                {{ $txn->debit > 0 ? 'GHS ' . number_format($txn->debit, 2) : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">
                                {{ $txn->credit > 0 ? 'GHS ' . number_format($txn->credit, 2) : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-slate-700">
                                GHS {{ number_format($txn->balance_after, 2) }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">
                                {{ $txn->reference }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-500">
                                Administrator
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center text-slate-400">
                                <x-empty-state title="No Transactions Found" message="There are currently no transactions matching your search criteria." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>

@endsection