@extends('layouts.app')

@section('title', 'Interest Revenue Report | FinCore')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                &larr; Back to reports hub
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">Interest Revenue Report</h1>
            <p class="text-xs text-slate-500 mt-0.5">30% initial interest and accumulated month-end compound interest revenue breakdown.</p>
        </div>

        <div class="flex items-center gap-2 print:hidden">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-xl hover:bg-slate-800 transition">
                Print Report
            </button>
        </div>
    </div>

    {{-- Interest KPI Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-stat-card title="Initial 30% Interest Sum" value="GHS {{ number_format($initialInterestSum, 2) }}" subtitle="Base interest fee" color="blue" />
        <x-stat-card title="Compound Interest Sum" value="GHS {{ number_format($compoundInterestSum, 2) }}" subtitle="Month-end accrued" color="indigo" />
        <x-stat-card title="Total Interest Generated" value="GHS {{ number_format($totalInterestSum, 2) }}" subtitle="Initial + Compound sum" color="emerald" />
    </div>

    {{-- Interest Trend Chart Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
        <h3 class="font-bold text-slate-900 text-sm mb-4">Interest Revenue Distribution</h3>
        <div class="h-48">
            <canvas id="interestReportChart"></canvas>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Loan #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4 text-right">Principal</th>
                        <th class="px-6 py-4 text-right">Initial 30% Interest</th>
                        <th class="px-6 py-4 text-right">Compound Accrued</th>
                        <th class="px-6 py-4 text-right">Current Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($loans as $loan)
                        @php
                            $compound = $loan->interestCycles->sum('interest_amount');
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $loan->loan_number }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $loan->customer->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-right">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-600">GHS {{ number_format($loan->interest_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-purple-600">GHS {{ number_format($compound, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0"><x-empty-state title="No interest revenue data found" /></td>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('interestReportChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Initial Interest (30%)', 'Compound Interest (30%)', 'Total Interest'],
                datasets: [{
                    label: 'Amount (GHS)',
                    data: [{{ $initialInterestSum }}, {{ $compoundInterestSum }}, {{ $totalInterestSum }}],
                    backgroundColor: ['#2563eb', '#8b5cf6', '#10b981'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { ticks: { callback: v => 'GHS ' + v.toLocaleString() } } }
            }
        });
    }
});
</script>
@endpush