@extends('layouts.app')

@section('title', 'Overdue Lending Collection | FinCore')
@section('page-title', 'Collection Profile')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('collections.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Collections</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Debt Recovery Case: {{ $loan->loan_number }}</h1>
            <p class="text-xs text-slate-500">Borrower: <span class="font-bold text-slate-900">{{ $loan->customer->full_name }}</span> ({{ $loan->customer->phone }})</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('payments.create', $loan) }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition">
                + Record Repayment
            </a>
            <a href="{{ route('loans.statement', $loan) }}" target="_blank" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition">
                Print Statement
            </a>
        </div>
    </div>

    {{-- Urgent Recovery Card --}}
    <div class="bg-gradient-to-br from-red-900 via-slate-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4 border border-red-500/20">
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-300 font-bold text-xs uppercase tracking-wider ring-1 ring-inset ring-red-500/30">OVERDUE RECOVERY CASE</span>
            <span class="text-xs text-slate-400">Due Date: <span class="text-white font-bold">{{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Month-End' }}</span></span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
            <div>
                <p class="text-xs text-slate-400">Original Disbursed Principal</p>
                <p class="text-2xl font-extrabold text-white mt-1">GHS {{ number_format($loan->principal_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Total Payments Collected</p>
                <p class="text-2xl font-extrabold text-emerald-400 mt-1">GHS {{ number_format($loan->amount_paid, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Total Outstanding Owed</p>
                <p class="text-3xl font-extrabold text-red-400 mt-1">GHS {{ number_format($loan->outstanding_balance, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Compound Interest History --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <h3 class="font-bold text-slate-900 text-base">Compound Interest Applied</h3>
        <p class="text-xs text-slate-500">Every un-cleared month-end balance accumulates 30% compound interest per business rules.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Cycle Period</th>
                        <th class="px-4 py-3 text-right">Opening Balance</th>
                        <th class="px-4 py-3 text-right">30% Compound Interest</th>
                        <th class="px-4 py-3 text-right">Payments Made</th>
                        <th class="px-4 py-3 text-right">Closing Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loan->interestCycles as $cycle)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold text-slate-900">{{ $cycle->cycle_date ? $cycle->cycle_date->format('F Y') : 'Cycle ' . $cycle->cycle_number }}</td>
                            <td class="px-4 py-3.5 text-right">GHS {{ number_format($cycle->opening_balance, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-purple-600">+ GHS {{ number_format($cycle->interest_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-emerald-600">- GHS {{ number_format($cycle->payment_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-red-600">GHS {{ number_format($cycle->closing_balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400 text-xs">No extra monthly compound cycles recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection