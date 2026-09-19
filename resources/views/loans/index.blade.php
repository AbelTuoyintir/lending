@extends('layouts.app')

@section('title', 'Loans | FinCore')
@section('page-title', 'Loans Management')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Lending Portfolio</h1>
            <p class="text-sm text-slate-500 mt-1">Manage all pending, active, overdue, and settled Lendings.</p>
        </div>

        <a href="{{ route('loans.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-lg shadow-blue-600/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Create New Lending
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search loan # or customer..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white text-slate-700 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Lending Statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="disbursed" @selected(request('status') === 'disbursed')>Disbursed / Active</option>
                    <option value="partially_paid" @selected(request('status') === 'partially_paid')>Partially Paid</option>
                    <option value="fully_paid" @selected(request('status') === 'fully_paid')>Fully Paid</option>
                    <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
                    <option value="defaulted" @selected(request('status') === 'defaulted')>Defaulted</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">Filter Portfolio</button>
            </div>
        </form>
    </div>

    {{-- Loans Data Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Lending Number</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5 text-right">Principal</th>
                        <th class="px-5 py-3.5 text-right">Total Payable</th>
                        <th class="px-5 py-3.5 text-right">Amount Paid</th>
                        <th class="px-5 py-3.5 text-right">Outstanding</th>
                        <th class="px-5 py-3.5 text-center">Due Date</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 font-mono font-semibold text-blue-600">
                                <a href="{{ route('loans.show', $loan) }}">{{ $loan->loan_number }}</a>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-900">
                                <a href="{{ route('customers.show', $loan->customer) }}" class="hover:text-blue-600">{{ $loan->customer->full_name }}</a>
                                <p class="text-[10px] text-slate-400 font-normal">{{ $loan->customer->phone }}</p>
                            </td>
                            <td class="px-5 py-4 text-right font-medium text-slate-800">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-900">GHS {{ number_format($loan->total_payable, 2) }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-emerald-600">GHS {{ number_format($loan->amount_paid, 2) }}</td>
                            <td class="px-5 py-4 text-right font-bold text-amber-600">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="px-5 py-4 text-center text-slate-600">
                                {{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Month-End' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <x-status-badge :status="$loan->status" />
                            </td>
                            <td class="px-5 py-4 text-right space-x-2">
                                <a href="{{ route('loans.show', $loan) }}" class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold rounded-lg transition">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-slate-400">
                                <x-empty-state title="No Lending found" message="Create a Lending for an active customer to start tracking disbursements." actionUrl="{{ route('loans.create') }}" actionLabel="Create First Lending" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ $loans->links() }}
        </div>
    </div>

</div>
@endsection