@extends('layouts.portal')

@section('title', 'Financial History — FinCore Portal')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Financial Transaction History</h1>
            <p class="text-xs text-slate-500">Comprehensive transaction log of loan disbursements, repayments, and account balance updates.</p>
        </div>
    </div>

    {{-- Ledger Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Disbursements (Debits)</span>
            <div class="text-2xl font-black text-slate-900">GHS {{ number_format($totalDebits, 2) }}</div>
            <p class="text-[11px] text-slate-500">Funds disbursed to your account</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Repayments (Credits)</span>
            <div class="text-2xl font-black text-emerald-600">GHS {{ number_format($totalCredits, 2) }}</div>
            <p class="text-[11px] text-slate-500">Funds paid back towards loans</p>
        </div>
    </div>

    {{-- Financial Transactions Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($transactions->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">No Transactions Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Your financial transaction log is currently empty. Transactions will record automatically upon loan disbursement or payment.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Transaction #</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Type</th>
                            <th class="px-5 py-3.5">Description / Ref</th>
                            <th class="px-5 py-3.5 text-right">Debit (Disbursed)</th>
                            <th class="px-5 py-3.5 text-right">Credit (Paid)</th>
                            <th class="px-5 py-3.5 text-right">Account Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $txn)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    {{ $txn->transaction_number }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $txn->transaction_date ? $txn->transaction_date->format('M d, Y h:i A') : '—' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $txn->type === 'loan_disbursement' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ str_replace('_', ' ', $txn->type) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-700">
                                    <span class="font-semibold">{{ $txn->description }}</span>
                                    <span class="block text-[10px] font-mono text-slate-400">Ref: {{ $txn->reference }}</span>
                                </td>
                                <td class="px-5 py-4 text-right font-bold text-slate-900">
                                    {{ $txn->debit > 0 ? 'GHS ' . number_format($txn->debit, 2) : '—' }}
                                </td>
                                <td class="px-5 py-4 text-right font-bold text-emerald-600">
                                    {{ $txn->credit > 0 ? 'GHS ' . number_format($txn->credit, 2) : '—' }}
                                </td>
                                <td class="px-5 py-4 text-right font-extrabold text-slate-800">
                                    GHS {{ number_format($txn->balance_after, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
