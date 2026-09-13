@extends('layouts.app')

@section('title', 'Financial Transactions | FinCore')

@section('content')

<div class="space-y-6">

    <x-page-header
        title="Financial Transactions Ledger"
        subtitle="Complete double-entry financial transaction history across disbursements, payments, and interest charges."
    />

    {{-- Transaction Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-stat-card title="Total Disbursements" value="GHS {{ number_format($totalDisbursements, 2) }}" subtitle="Debited principal" color="blue" />
        <x-stat-card title="Total Repayment Collections" value="GHS {{ number_format($totalCollections, 2) }}" subtitle="Credited repayments" color="emerald" />
        <x-stat-card title="Total Interest Debited" value="GHS {{ number_format($totalInterestCharged, 2) }}" subtitle="Initial + Compound interest" color="indigo" />
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search transaction #, reference, or description..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition placeholder-slate-400">
                </div>

                <div>
                    <select name="type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition bg-white text-slate-700 font-medium">
                        <option value="">All Transaction Types</option>
                        <option value="disbursement" @selected(request('type') === 'disbursement')>Disbursement</option>
                        <option value="repayment" @selected(request('type') === 'repayment')>Repayment</option>
                        <option value="interest_charge" @selected(request('type') === 'interest_charge')>Interest Charge</option>
                        <option value="fee_charge" @selected(request('type') === 'fee_charge')>Fee Charge</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-900 text-white font-semibold rounded-xl text-sm hover:bg-slate-800 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'type']))
                        <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-semibold rounded-xl text-sm hover:bg-slate-200 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 text-right">Debit</th>
                        <th class="px-6 py-4 text-right">Credit</th>
                        <th class="px-6 py-4">Reference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-bold text-slate-900 font-mono">
                                {{ $trx->transaction_number }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $trx->transaction_date ? $trx->transaction_date->format('d M Y, H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $trx->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-800">
                                {{ $trx->description ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-rose-600">
                                {{ $trx->debit_credit === 'debit' ? 'GHS ' . number_format($trx->amount, 2) : '—' }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                {{ $trx->debit_credit === 'credit' ? 'GHS ' . number_format($trx->amount, 2) : '—' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-mono">
                                {{ $trx->reference ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-0">
                                <x-empty-state
                                    title="No transactions logged"
                                    description="No financial journal transactions match your current query."
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>

@endsection