@extends('layouts.app')

@section('title', 'Dashboard | FinCore')
@section('page-title', 'Administrative Overview')

@section('content')

<div class="space-y-8">

    {{-- Welcome Header --}}
    <div class="bg-slate-900 text-white border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative overflow-hidden">
        <div class="relative z-10 space-y-2 max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-xs font-semibold ring-1 ring-inset ring-blue-500/30">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span> {{ now()->format('l, d F Y') }}
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Welcome back, {{ auth()->user()->name ?? 'Administrator' }}
            </h1>
            <p class="text-sm text-slate-300 leading-relaxed">
                Internal administrative dashboard for monitoring credit performance, GHS loan disbursements, compound interest accumulation, and daily debt collections.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 relative z-10 w-full sm:w-auto">
            <a href="{{ route('customers.create') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl text-xs font-bold transition border border-white/10 backdrop-blur-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                + Add Customer
            </a>

            <a href="{{ route('loans.create') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl text-xs font-bold transition shadow-lg shadow-blue-600/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + New Loan
            </a>
        </div>
    </div>

    {{-- 10 KPI Summary Cards Grid --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Key Performance Indicators</h2>
            <span class="text-xs text-slate-400">Currency: GHS (Ghana Cedi)</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            {{-- 1. Total Customers --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Customers</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($totalCustomers) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Registered clients</p>
            </div>

            {{-- 2. Active Customers --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Active Customers</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-2">{{ number_format($activeCustomers) }}</h3>
                <p class="text-xs text-emerald-600/80 mt-1">Eligible for lending</p>
            </div>

            {{-- 3. Total Loans --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Loans</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ number_format(\App\Models\Loan::count()) }}</h3>
                <p class="text-xs text-slate-500 mt-1">All time issued</p>
            </div>

            {{-- 4. Active Loans --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Active Loans</p>
                <h3 class="text-2xl font-bold text-blue-600 mt-2">{{ number_format($activeLoans) }}</h3>
                <p class="text-xs text-blue-600/80 mt-1">Ongoing repayments</p>
            </div>

            {{-- 5. Total Principal Disbursed --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Principal Disbursed</p>
                <h3 class="text-lg font-bold text-slate-900 mt-2 truncate">GHS {{ number_format($totalPrincipalDisbursed, 2) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Total principal</p>
            </div>

            {{-- 6. Total Interest Generated --}}
            @php
                $totalInterest = \App\Models\Loan::sum('interest_amount') + \App\Models\LoanInterestCycle::sum('interest_charged');
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Interest Generated</p>
                <h3 class="text-lg font-bold text-indigo-600 mt-2 truncate">GHS {{ number_format($totalInterest, 2) }}</h3>
                <p class="text-xs text-indigo-600/80 mt-1">Initial + Compound</p>
            </div>

            {{-- 7. Total Amount Collected --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Amount Collected</p>
                <h3 class="text-lg font-bold text-emerald-600 mt-2 truncate">GHS {{ number_format($totalAmountPaid, 2) }}</h3>
                <p class="text-xs text-emerald-600/80 mt-1">GHS {{ number_format($todayPayments, 2) }} today</p>
            </div>

            {{-- 8. Total Outstanding Balance --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Outstanding Balance</p>
                <h3 class="text-lg font-bold text-amber-600 mt-2 truncate">GHS {{ number_format($totalOutstanding, 2) }}</h3>
                <p class="text-xs text-amber-600/80 mt-1">Portfolio balance</p>
            </div>

            {{-- 9. Overdue Amount --}}
            @php
                $overdueAmount = \App\Models\Loan::whereIn('status', ['overdue', 'defaulted'])->sum('outstanding_balance');
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Overdue Amount</p>
                <h3 class="text-lg font-bold text-red-600 mt-2 truncate">GHS {{ number_format($overdueAmount, 2) }}</h3>
                <p class="text-xs text-red-600/80 mt-1">Requires collection</p>
            </div>

            {{-- 10. Number of Overdue Loans --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Overdue Loans</p>
                <h3 class="text-2xl font-bold text-red-600 mt-2">{{ number_format($overdueLoans) }}</h3>
                <p class="text-xs text-red-600/80 mt-1">Past month-end due date</p>
            </div>

        </div>
    </div>

    {{-- Visual Financial Analytics Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Portfolio Overview Chart --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Portfolio Overview</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Disbursements, collections, interest, and outstanding balance</p>
                </div>
                <select class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Last 30 days</option>
                    <option selected>Last 6 months</option>
                    <option>Last 12 months</option>
                    <option>Current year</option>
                </select>
            </div>
            <div class="h-72">
                <canvas id="portfolioOverviewChart"></canvas>
            </div>
        </div>

        {{-- Loan Status Distribution Doughnut --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Loan Status Distribution</h3>
                <p class="text-xs text-slate-500 mt-0.5">Active, overdue, paid, and defaulted breakdown</p>
            </div>
            <div class="h-56 my-4">
                <canvas id="statusDistributionChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs border-t border-slate-100 pt-3">
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Active ({{ $activeLoans }})</div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Fully Paid ({{ $fullyPaidLoans }})</div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Overdue ({{ $overdueLoans }})</div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-600"></span> Defaulted ({{ $defaultedLoans }})</div>
            </div>
        </div>

    </div>

    {{-- Tables Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Loans --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs flex flex-col justify-between">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Recent Loans</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Latest loans created and disbursed</p>
                </div>
                <a href="{{ route('loans.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View all loans →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">Loan #</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3 text-right">Payable</th>
                            <th class="px-5 py-3 text-right">Balance</th>
                            <th class="px-5 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentLoans as $loan)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5 font-mono font-semibold text-blue-600">
                                    <a href="{{ route('loans.show', $loan) }}">{{ $loan->loan_number }}</a>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-900">{{ $loan->customer->full_name }}</td>
                                <td class="px-5 py-3.5 text-right font-semibold text-slate-900">GHS {{ number_format($loan->total_payable, 2) }}</td>
                                <td class="px-5 py-3.5 text-right font-semibold text-amber-600">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <x-status-badge :status="$loan->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">No recent loans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs flex flex-col justify-between">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Recent Collections</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Latest loan payments recorded</p>
                </div>
                <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View all payments →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">Payment #</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Method</th>
                            <th class="px-5 py-3 text-right">Amount</th>
                            <th class="px-5 py-3 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentPayments as $payment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5 font-mono font-semibold text-slate-700">
                                    <a href="{{ route('payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-900">{{ $payment->customer->full_name }}</td>
                                <td class="px-5 py-3.5 uppercase font-semibold text-slate-500 text-[10px]">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                <td class="px-5 py-3.5 text-right font-bold text-emerald-600">+ GHS {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-5 py-3.5 text-right text-slate-500">{{ $payment->payment_date->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">No payments recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const portfolioCtx = document.getElementById('portfolioOverviewChart');
    if (portfolioCtx) {
        new Chart(portfolioCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Disbursed (GHS)',
                        data: [15000, 24000, 32000, 45000, 52000, {{ $totalPrincipalDisbursed }}],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Collected (GHS)',
                        data: [8000, 14000, 21000, 31000, 42000, {{ $totalAmountPaid }}],
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        tension: 0.3
                    },
                    {
                        label: 'Outstanding (GHS)',
                        data: [7000, 10000, 11000, 14000, 10000, {{ $totalOutstanding }}],
                        borderColor: '#f59e0b',
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) { return 'GHS ' + value.toLocaleString(); },
                            font: { size: 10 }
                        }
                    },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    const statusCtx = document.getElementById('statusDistributionChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Fully Paid', 'Overdue', 'Defaulted'],
                datasets: [{
                    data: [
                        {{ $activeLoans }},
                        {{ $fullyPaidLoans }},
                        {{ $overdueLoans }},
                        {{ $defaultedLoans }}
                    ],
                    backgroundColor: ['#2563eb', '#10b981', '#f97316', '#dc2626'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endpush