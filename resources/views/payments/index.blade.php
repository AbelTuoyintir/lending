@extends('layouts.app')

@section('title', 'Payments | FinCore')
@section('page-title', 'Repayment Collections Ledger')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Collections & Payments Ledger
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Monitor received repayments, transaction references, and payment methods.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('payments.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>

            <a href="{{ route('payments.manual-create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition">
                <span>+</span> Record Repayment
            </a>
        </div>
    </div>

    {{-- Summary Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Payments</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($totalPaymentsCount) }}</h3>
            <p class="text-xs text-slate-500 mt-1">Completed transactions</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Today's Collections</p>
            <h3 class="text-xl font-bold text-emerald-600 mt-2">GHS {{ number_format($todayCollections, 2) }}</h3>
            <p class="text-xs text-emerald-600/80 mt-1">Received today</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">This Month's Collections</p>
            <h3 class="text-xl font-bold text-blue-600 mt-2">GHS {{ number_format($thisMonthCollections, 2) }}</h3>
            <p class="text-xs text-blue-600/80 mt-1">{{ now()->format('F Y') }} collections</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total All-Time Collected</p>
            <h3 class="text-xl font-bold text-slate-900 mt-2">GHS {{ number_format($totalCollected, 2) }}</h3>
            <p class="text-xs text-slate-500 mt-1">Cumulative repayments</p>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('payments.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payment #, reference, customer or loan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <select name="payment_method" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">All Payment Methods</option>
                        <option value="cash" @selected(request('payment_method') === 'cash')>Cash</option>
                        <option value="mobile_money" @selected(request('payment_method') === 'mobile_money')>Mobile Money</option>
                        <option value="bank_transfer" @selected(request('payment_method') === 'bank_transfer')>Bank Transfer</option>
                        <option value="card" @selected(request('payment_method') === 'card')>Card / Bank Deposit</option>
                        <option value="other" @selected(request('payment_method') === 'other')>Other</option>
                    </select>
                </div>

                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" title="Payment Date From" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'payment_method', 'date_from']))
                        <a href="{{ route('payments.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Payment Number</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Loan Number</th>
                        <th class="px-5 py-3.5 text-right">Amount</th>
                        <th class="px-5 py-3.5 text-center">Method</th>
                        <th class="px-5 py-3.5">Reference</th>
                        <th class="px-5 py-3.5 text-center">Payment Date</th>
                        <th class="px-5 py-3.5 text-center">Recorded By</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-slate-800">
                                <a href="{{ route('payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                <a href="{{ route('customers.show', $payment->customer) }}" class="hover:text-blue-600">
                                    {{ $payment->customer->full_name }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 font-mono font-semibold text-blue-600">
                                <a href="{{ route('loans.show', $payment->loan) }}">
                                    {{ $payment->loan->loan_number ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 text-right font-extrabold text-emerald-600">
                                + GHS {{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-center uppercase font-semibold text-[10px] text-slate-600">
                                {{ str_replace('_', ' ', $payment->payment_method) }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-600">
                                {{ $payment->reference }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-600 font-medium">
                                {{ $payment->payment_date->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-500">
                                Administrator
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('payments.show', $payment) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition">
                                    Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-slate-400">
                                <x-empty-state title="No Payments Recorded" message="There are currently no payment transactions matching your filters." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>

@endsection