@extends('layouts.app')

@section('title', 'Payment Report | FinCore')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                &larr; Back to reports hub
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">Collections & Payments Report</h1>
            <p class="text-xs text-slate-500 mt-0.5">Filter payments by date range and transaction method.</p>
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
                        <th class="px-6 py-4">Payment #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Loan #</th>
                        <th class="px-6 py-4 text-right">Amount Collected</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Reference</th>
                        <th class="px-6 py-4">Payment Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $payment->payment_number }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $payment->customer->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-blue-600">{{ $payment->loan->loan_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">+ GHS {{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4 uppercase font-semibold text-slate-600">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                            <td class="px-6 py-4 font-mono text-slate-500">{{ $payment->reference }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-0"><x-empty-state title="No payment records match report criteria" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 print:hidden">{{ $payments->links() }}</div>
        @endif
    </div>

</div>

@endsection