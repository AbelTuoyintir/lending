@extends('layouts.app')

@section('title', 'Collection View - ' . $loan->loan_number . ' | FinCore')
@section('page-title', 'Collection Management View')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('collections.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Collections Queue</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-mono font-bold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-md">{{ $loan->loan_number }}</span>
                <x-status-badge :status="$loan->status" />
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-2">
                Borrower: <a href="{{ route('customers.show', $loan->customer) }}" class="hover:text-blue-600">{{ $loan->customer->full_name }}</a>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Phone: <span class="font-bold text-slate-800">{{ $loan->customer->phone }}</span> •
                Days Overdue: <span class="font-bold text-rose-600">{{ $daysOverdue }} days past due</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('payments.create', $loan) }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md transition">
                + Record Payment
            </a>
            <a href="{{ route('customers.show', $loan->customer) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                View Customer
            </a>
            <a href="{{ route('loans.show', $loan) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                View Loan File
            </a>
            <a href="{{ route('loans.statement', $loan) }}" target="_blank" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-50 transition">
                Print Statement
            </a>
        </div>
    </div>

    {{-- Detailed Collection Metrics Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 text-xs">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Original Principal</p>
            <p class="text-lg font-bold text-slate-900 mt-1">GHS {{ number_format($loan->principal_amount, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Original Initial Interest</p>
            <p class="text-lg font-bold text-blue-600 mt-1">GHS {{ number_format($loan->interest_amount, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Repaid</p>
            <p class="text-lg font-bold text-emerald-600 mt-1">GHS {{ number_format($loan->amount_paid, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Compound Interest</p>
            <p class="text-lg font-bold text-purple-600 mt-1">GHS {{ number_format($accumulatedCompoundInterest, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Current Outstanding</p>
            <p class="text-xl font-extrabold text-rose-600 mt-1">GHS {{ number_format($loan->outstanding_balance, 2) }}</p>
        </div>
    </div>

    {{-- Interest Calculation Timeline --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Compound Interest Timeline</h3>
            <span class="text-xs bg-purple-50 text-purple-700 font-bold px-3 py-1 rounded-full">30% Monthly Rollover</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Period</th>
                        <th class="px-4 py-3 text-right">Opening Balance</th>
                        <th class="px-4 py-3 text-center">Interest Rate</th>
                        <th class="px-4 py-3 text-right">Interest Charged</th>
                        <th class="px-4 py-3 text-right">Payments</th>
                        <th class="px-4 py-3 text-right">Closing Balance</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="bg-blue-50/30 font-medium">
                        <td class="px-4 py-3.5 font-bold text-slate-900">Initial Disbursement Period</td>
                        <td class="px-4 py-3.5 text-right">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                        <td class="px-4 py-3.5 text-center font-bold text-blue-600">30%</td>
                        <td class="px-4 py-3.5 text-right font-bold text-blue-600">GHS {{ number_format($loan->interest_amount, 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-bold text-emerald-600">GHS {{ number_format($loan->amount_paid, 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-bold text-slate-900">GHS {{ number_format($loan->total_payable - $loan->amount_paid, 2) }}</td>
                        <td class="px-4 py-3.5 text-center"><span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold">INITIAL</span></td>
                    </tr>

                    @forelse($loan->interestCycles as $cycle)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $cycle->year_month }}</td>
                            <td class="px-4 py-3.5 text-right text-slate-700">GHS {{ number_format($cycle->opening_balance, 2) }}</td>
                            <td class="px-4 py-3.5 text-center font-semibold text-purple-600">{{ number_format($cycle->interest_rate, 0) }}%</td>
                            <td class="px-4 py-3.5 text-right font-bold text-purple-600">+ GHS {{ number_format($cycle->interest_charged, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-emerald-600">- GHS {{ number_format($cycle->payment_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-rose-600">GHS {{ number_format($cycle->closing_balance, 2) }}</td>
                            <td class="px-4 py-3.5 text-center"><span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold uppercase">{{ $cycle->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-slate-400 italic">No month-end compound interest cycles recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection