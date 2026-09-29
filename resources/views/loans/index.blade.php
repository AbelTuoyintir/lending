@extends('layouts.app')

@section('title', 'Loans | FinCore')
@section('page-title', 'Lending Portfolio & Loans Management')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Lending Portfolio
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Monitor loans, monthly compound interest, repayments, and due dates.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('loans.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>

            <a href="{{ route('loans.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition">
                <span>+</span> New Loan
            </a>
        </div>
    </div>

    {{-- Search & Filters Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('loans.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3 text-xs">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search loan number, customer name or phone..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">All Statuses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending Approval</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="partially_paid" @selected(request('status') === 'partially_paid')>Partially Paid</option>
                        <option value="fully_paid" @selected(request('status') === 'fully_paid')>Fully Paid</option>
                        <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
                        <option value="defaulted" @selected(request('status') === 'defaulted')>Defaulted</option>
                    </select>
                </div>

                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" title="Loan Date From" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <select name="sort" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="latest" @selected(request('sort') === 'latest')>Newest First</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                        <option value="amount_desc" @selected(request('sort') === 'amount_desc')>Amount (High - Low)</option>
                        <option value="amount_asc" @selected(request('sort') === 'amount_asc')>Amount (Low - High)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'sort']))
                        <a href="{{ route('loans.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition">
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
                        <th class="px-5 py-3.5">Loan Number</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5 text-right">Principal</th>
                        <th class="px-5 py-3.5 text-right">Interest</th>
                        <th class="px-5 py-3.5 text-right">Total Payable</th>
                        <th class="px-5 py-3.5 text-right">Amount Paid</th>
                        <th class="px-5 py-3.5 text-right">Outstanding</th>
                        <th class="px-5 py-3.5 text-center">Loan Date</th>
                        <th class="px-5 py-3.5 text-center">Due Date</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-blue-600">
                                <a href="{{ route('loans.show', $loan) }}">{{ $loan->loan_number }}</a>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                <a href="{{ route('customers.show', $loan->customer) }}" class="hover:text-blue-600">
                                    {{ $loan->customer->full_name }}
                                </a>
                                <div class="text-[10px] text-slate-400 font-mono font-normal">{{ $loan->customer->phone }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-slate-900">
                                GHS {{ number_format($loan->principal_amount, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-indigo-600">
                                GHS {{ number_format($loan->interest_amount, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                GHS {{ number_format($loan->total_payable, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">
                                GHS {{ number_format($loan->amount_paid, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-extrabold text-amber-600">
                                GHS {{ number_format($loan->outstanding_balance, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-600 font-medium">
                                {{ $loan->loan_date ? $loan->loan_date->format('d M Y') : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-900 font-semibold">
                                {{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Month-End' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <x-status-badge :status="$loan->status" />
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('loans.show', $loan) }}" class="px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold rounded-lg transition">
                                    Details
                                </a>
                                @if(in_array($loan->status, ['disbursed', 'active', 'partially_paid', 'overdue']))
                                    <a href="{{ route('payments.create', $loan) }}" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold rounded-lg transition">
                                        Collect
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-5 py-12 text-center text-slate-400">
                                <x-empty-state title="No Loans Found" message="There are currently no lending records matching your filters." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($loans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $loans->links() }}
            </div>
        @endif
    </div>

</div>

@endsection