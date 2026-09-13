@extends('layouts.app')

@section('title', 'Outstanding Balance Report | FinCore')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                &larr; Back to reports hub
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">Outstanding Portfolio Balance Report</h1>
            <p class="text-xs text-slate-500 mt-0.5">Active, overdue, and defaulted receivables across customer accounts.</p>
        </div>

        <div class="flex items-center gap-2 print:hidden">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-xl hover:bg-slate-800 transition">
                Print Report
            </button>
        </div>
    </div>

    {{-- Outstanding KPI Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-stat-card title="Total Outstanding Portfolio Balance" value="GHS {{ number_format($totalOutstandingSum, 2) }}" subtitle="Active & Overdue receivables" color="amber" />
        <x-stat-card title="Overdue & Defaulted Portfolio Balance" value="GHS {{ number_format($overdueOutstandingSum, 2) }}" subtitle="At-risk collections balance" color="red" />
    </div>

    {{-- Report Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Loan #</th>
                        <th class="px-6 py-4 text-right">Principal</th>
                        <th class="px-6 py-4 text-right">Total Payable</th>
                        <th class="px-6 py-4 text-right">Amount Paid</th>
                        <th class="px-6 py-4 text-right">Outstanding Balance</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $loan->customer->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $loan->loan_number }}</td>
                            <td class="px-6 py-4 text-right">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">GHS {{ number_format($loan->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right text-emerald-600">GHS {{ number_format($loan->total_paid, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-red-600">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $loan->due_date ? $loan->due_date->format('d M Y') : 'End of Month' }}</td>
                            <td class="px-6 py-4 text-center"><x-status-badge :status="$loan->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-0"><x-empty-state title="No active outstanding balances found" /></td>
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