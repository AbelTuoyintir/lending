@extends('layouts.app')

@section('title', 'Loan Portfolio Report | FinCore')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                &larr; Back to reports hub
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">Loan Portfolio Report</h1>
            <p class="text-xs text-slate-500 mt-0.5">Filter loans by date range, status, and principal amounts.</p>
        </div>

        <div class="flex items-center gap-2 print:hidden">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-xl hover:bg-slate-800 transition">
                Print Report
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm print:hidden">
        <form method="GET" action="{{ route('reports.loans') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">From Date</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">To Date</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold bg-white">
                        <option value="">All Statuses</option>
                        <option value="disbursed" @selected(request('status') === 'disbursed')>Disbursed / Active</option>
                        <option value="partially_paid" @selected(request('status') === 'partially_paid')>Partially Paid</option>
                        <option value="fully_paid" @selected(request('status') === 'fully_paid')>Fully Paid</option>
                        <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 bg-blue-600 text-white font-bold rounded-xl text-xs hover:bg-blue-700 transition">Filter</button>
                    <a href="{{ route('reports.loans') }}" class="px-3 py-2 bg-slate-100 text-slate-600 font-semibold rounded-xl text-xs">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Loan #</th>
                        <th class="px-6 py-4">Customer Name</th>
                        <th class="px-6 py-4 text-right">Principal</th>
                        <th class="px-6 py-4 text-right">Interest</th>
                        <th class="px-6 py-4 text-right">Total Payable</th>
                        <th class="px-6 py-4 text-right">Paid</th>
                        <th class="px-6 py-4 text-right">Outstanding</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $loan->loan_number }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $loan->customer->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-right">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right text-indigo-600">GHS {{ number_format($loan->interest_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold">GHS {{ number_format($loan->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right text-emerald-600">GHS {{ number_format($loan->total_paid, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-amber-600">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $loan->due_date ? $loan->due_date->format('d M Y') : 'End of Month' }}</td>
                            <td class="px-6 py-4 text-center"><x-status-badge :status="$loan->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-0"><x-empty-state title="No loans match report filters" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($loans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 print:hidden">{{ $loans->links() }}</div>
        @endif
    </div>

</div>

@endsection