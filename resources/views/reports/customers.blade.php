@extends('layouts.app')

@section('title', 'Borrower Summary Report | FinCore')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                &larr; Back to reports hub
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">Borrower Directory & Exposure Report</h1>
            <p class="text-xs text-slate-500 mt-0.5">Borrower counts, borrowing frequency, total principal taken, and balance exposure.</p>
        </div>

        <div class="flex items-center gap-2 print:hidden">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-xl hover:bg-slate-800 transition">
                Print Report
            </button>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Customer #</th>
                        <th class="px-6 py-4">Customer Name</th>
                        <th class="px-6 py-4">Phone / Email</th>
                        <th class="px-6 py-4 text-center">Total Loans</th>
                        <th class="px-6 py-4 text-right">Total Principal Taken</th>
                        <th class="px-6 py-4 text-right">Current Outstanding</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $customer->customer_number }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $customer->full_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $customer->phone }}</td>
                            <td class="px-6 py-4 text-center font-bold">{{ $customer->loans_count }}</td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">GHS {{ number_format($customer->loans_sum_principal_amount ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-amber-600">GHS {{ number_format($customer->loans_sum_outstanding_balance ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-center"><x-status-badge :status="$customer->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-0"><x-empty-state title="No customer reports found" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 print:hidden">{{ $customers->links() }}</div>
        @endif
    </div>

</div>

@endsection