@extends('layouts.app')

@section('title', 'Dashboard | FinCore')

@section('page-title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Good afternoon, {{ auth()->user()->name ?? 'Admin' }}
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Here's what's happening with your lending portfolio today.
            </p>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('customers.create') }}"
                class="
                    inline-flex items-center gap-2
                    px-4 py-2.5
                    bg-white
                    border border-slate-200
                    rounded-xl
                    text-sm font-semibold
                    text-slate-700
                    hover:bg-slate-50
                "
            >
                Add Customer
            </a>

            <a
                href="{{ route('loans.create') }}"
                class="
                    inline-flex items-center gap-2
                    px-4 py-2.5
                    bg-blue-600
                    text-white
                    rounded-xl
                    text-sm font-semibold
                    shadow-lg shadow-blue-600/20
                    hover:bg-blue-700
                "
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>

                New Loan
            </a>

        </div>

    </div>


    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Customers --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Customers
                    </p>

                    <h3 class="text-2xl font-bold mt-2">
                        {{ number_format($totalCustomers) }}
                    </h3>

                    <p class="text-xs text-emerald-600 mt-2">
                        {{ number_format($activeCustomers) }} active
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5V4H2v16h5m10 0v-4H7v4m10 0H7m10-8a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                </div>

            </div>

        </div>


        {{-- Disbursed --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Disbursed
                    </p>

                    <h3 class="text-2xl font-bold mt-2">
                        GHS {{ number_format($totalPrincipalDisbursed, 2) }}
                    </h3>

                    <p class="text-xs text-slate-400 mt-2">
                        Principal issued
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                </div>

            </div>

        </div>


        {{-- Outstanding --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Outstanding
                    </p>

                    <h3 class="text-2xl font-bold mt-2">
                        GHS {{ number_format($totalOutstanding, 2) }}
                    </h3>

                    <p class="text-xs text-amber-600 mt-2">
                        Current portfolio balance
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                </div>

            </div>

        </div>


        {{-- Collected --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Collected
                    </p>

                    <h3 class="text-2xl font-bold mt-2">
                        GHS {{ number_format($totalAmountPaid, 2) }}
                    </h3>

                    <p class="text-xs text-emerald-600 mt-2">
                        GHS {{ number_format($monthPayments, 2) }} this month
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>

                </div>

            </div>

        </div>

    </div>


    {{-- Main Analytics --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Portfolio chart --}}
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-6">

            <div class="flex items-center justify-between mb-6">

                <div>
                    <h2 class="font-semibold text-slate-900">
                        Portfolio Overview
                    </h2>

                    <p class="text-xs text-slate-400 mt-1">
                        Loan collections and outstanding balance
                    </p>
                </div>

                <select
                    class="
                        text-xs
                        border border-slate-200
                        rounded-lg
                        px-3 py-2
                        bg-white
                    "
                >
                    <option>Last 6 months</option>
                    <option>Last 12 months</option>
                    <option>This year</option>
                </select>

            </div>

            <div class="h-72">
                <canvas id="portfolioChart"></canvas>
            </div>

        </div>


        {{-- Portfolio health --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <h2 class="font-semibold">
                Portfolio Health
            </h2>

            <p class="text-xs text-slate-400 mt-1">
                Current loan distribution
            </p>

            <div class="h-48 mt-5">
                <canvas id="portfolioHealth"></canvas>
            </div>

            <div class="space-y-3 mt-4">

                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">
                        Active
                    </span>

                    <span class="font-semibold">
                        {{ number_format($activeLoans) }}
                    </span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">
                        Overdue
                    </span>

                    <span class="font-semibold text-red-600">
                        {{ number_format($overdueLoans) }}
                    </span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">
                        Fully Paid
                    </span>

                    <span class="font-semibold text-emerald-600">
                        {{ number_format($fullyPaidLoans) }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Bottom --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Recent Loans --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">

                <div>
                    <h2 class="font-semibold">
                        Recent Loans
                    </h2>

                    <p class="text-xs text-slate-400 mt-1">
                        Latest lending activity
                    </p>
                </div>

                <a
                    href="{{ route('loans.index') }}"
                    class="text-sm text-blue-600 font-medium"
                >
                    View all
                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 text-xs uppercase text-slate-400">

                    <tr>
                        <th class="px-6 py-3 text-left font-medium">
                            Customer
                        </th>

                        <th class="px-6 py-3 text-right font-medium">
                            Amount
                        </th>

                        <th class="px-6 py-3 text-right font-medium">
                            Status
                        </th>
                    </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                    @forelse($recentLoans as $loan)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <div class="font-medium">
                                    {{ $loan->customer->full_name }}
                                </div>

                                <div class="text-xs text-slate-400">
                                    {{ $loan->loan_number }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-right font-semibold">
                                GHS {{ number_format($loan->principal_amount, 2) }}
                            </td>

                            <td class="px-6 py-4 text-right">

                                @php
                                    $statusClasses = [
                                        'active' => 'bg-blue-50 text-blue-700',
                                        'partially_paid' => 'bg-amber-50 text-amber-700',
                                        'fully_paid' => 'bg-emerald-50 text-emerald-700',
                                        'overdue' => 'bg-red-50 text-red-700',
                                        'defaulted' => 'bg-red-50 text-red-700',
                                        'pending' => 'bg-slate-100 text-slate-600',
                                    ];
                                @endphp

                                <span
                                    class="
                                        inline-flex
                                        px-2.5 py-1
                                        rounded-full
                                        text-xs font-medium
                                        {{ $statusClasses[$loan->status] ?? 'bg-slate-100 text-slate-600' }}
                                    "
                                >
                                    {{ str_replace('_', ' ', ucfirst($loan->status)) }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="3"
                                class="px-6 py-12 text-center text-slate-400"
                            >
                                No loans found.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Recent Payments --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">

                <div>
                    <h2 class="font-semibold">
                        Recent Payments
                    </h2>

                    <p class="text-xs text-slate-400 mt-1">
                        Latest collections
                    </p>
                </div>

                <a
                    href="{{ route('reports.payments') }}"
                    class="text-sm text-blue-600 font-medium"
                >
                    View all
                </a>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse($recentPayments as $payment)

                    <div class="px-6 py-4 flex items-center justify-between">

                        <div class="flex items-center gap-3">

                            <div
                                class="
                                    w-10 h-10
                                    rounded-xl
                                    bg-emerald-50
                                    text-emerald-600
                                    flex items-center justify-center
                                "
                            >
                                ✓
                            </div>

                            <div>

                                <p class="font-medium text-sm">
                                    {{ $payment->customer->full_name }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    {{ $payment->payment_number }}
                                </p>

                            </div>

                        </div>

                        <div class="text-right">

                            <p class="font-semibold text-sm text-emerald-600">
                                + GHS {{ number_format($payment->amount, 2) }}
                            </p>

                            <p class="text-xs text-slate-400">
                                {{ $payment->payment_date->format('d M Y') }}
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center text-slate-400">
                        No payments found.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

const portfolioCanvas =
    document.getElementById('portfolioChart');

if (portfolioCanvas) {

    new Chart(portfolioCanvas, {

        type: 'line',

        data: {
            labels: [
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep'
            ],

            datasets: [
                {
                    label: 'Collected',

                    data: [
                        18000,
                        22000,
                        26000,
                        31000,
                        35000,
                        {{ $monthPayments }}
                    ],

                    borderWidth: 2,

                    tension: 0.4,

                    fill: true
                },

                {
                    label: 'Outstanding',

                    data: [
                        65000,
                        71000,
                        78000,
                        82000,
                        91000,
                        {{ $totalOutstanding }}
                    ],

                    borderWidth: 2,

                    tension: 0.4,

                    fill: false
                }
            ]
        },

        options: {
            responsive: true,

            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: 'bottom'
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        callback: function(value) {
                            return 'GHS ' +
                                Number(value).toLocaleString();
                        }
                    }
                }
            }
        }

    });

}


const healthCanvas =
    document.getElementById('portfolioHealth');

if (healthCanvas) {

    new Chart(healthCanvas, {

        type: 'doughnut',

        data: {

            labels: [
                'Active',
                'Overdue',
                'Fully Paid'
            ],

            datasets: [{
                data: [
                    {{ $activeLoans }},
                    {{ $overdueLoans }},
                    {{ $fullyPaidLoans }}
                ],

                borderWidth: 0
            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '72%',

            plugins: {

                legend: {
                    display: false
                }

            }

        }

    });

}

</script>

@endpush