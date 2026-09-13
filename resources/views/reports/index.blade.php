@extends('layouts.app')

@section('title', 'Financial Reports Hub | FinCore')

@section('content')

<div class="space-y-6">

    <x-page-header
        title="Reports & Financial Analytics"
        subtitle="Exportable financial statements, portfolio intelligence, interest breakdowns, and collection analytics."
    />

    {{-- Portfolio Highlights Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card title="Total Portfolio Loans" value="{{ number_format($totalLoansCount) }}" subtitle="Lifetime count" color="blue" />
        <x-stat-card title="Total Principal Issued" value="GHS {{ number_format($totalDisbursed, 2) }}" subtitle="Total disbursed" color="slate" />
        <x-stat-card title="Total Collections" value="GHS {{ number_format($totalCollected, 2) }}" subtitle="Repayments received" color="emerald" />
        <x-stat-card title="Total Outstanding" value="GHS {{ number_format($totalOutstanding, 2) }}" subtitle="Portfolio receivable" color="amber" />
    </div>

    {{-- Report Category Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- 1. Loan Report --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Loan Portfolio Report</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Comprehensive breakdown of all loans issued, principal sums, initial interest, due dates, and status filtering.
                </p>
            </div>
            <a href="{{ route('reports.loans') }}" class="inline-flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-700 border-t border-slate-100 pt-3">
                <span>View Loan Report</span>
                <span>&rarr;</span>
            </a>
        </div>

        {{-- 2. Payment Report --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14v10H5V9zm4 4h6"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Payment & Collections Report</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Detailed repayment ledger showing payment methods, transaction references, dates, and amounts.
                </p>
            </div>
            <a href="{{ route('reports.payments') }}" class="inline-flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-700 border-t border-slate-100 pt-3">
                <span>View Payment Report</span>
                <span>&rarr;</span>
            </a>
        </div>

        {{-- 3. Interest Generated Report --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Interest Revenue Report</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Analysis of 30% initial interest and accumulated month-end 30% compound interest earned.
                </p>
            </div>
            <a href="{{ route('reports.interest') }}" class="inline-flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-700 border-t border-slate-100 pt-3">
                <span>View Interest Report</span>
                <span>&rarr;</span>
            </a>
        </div>

        {{-- 4. Outstanding Balance Report --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Outstanding Balance Report</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Current active, overdue, and defaulted receivables across the entire customer portfolio.
                </p>
            </div>
            <a href="{{ route('reports.outstanding') }}" class="inline-flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-700 border-t border-slate-100 pt-3">
                <span>View Outstanding Report</span>
                <span>&rarr;</span>
            </a>
        </div>

        {{-- 5. Customer Report --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-4H7v4m10 0H7m10-8a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Borrower Summary Report</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Customer demographic directory, borrowing frequency, total principal taken, and current exposure.
                </p>
            </div>
            <a href="{{ route('reports.customers') }}" class="inline-flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-700 border-t border-slate-100 pt-3">
                <span>View Customer Report</span>
                <span>&rarr;</span>
            </a>
        </div>

    </div>

</div>

@endsection