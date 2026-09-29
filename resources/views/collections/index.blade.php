@extends('layouts.app')

@section('title', 'Collections Dashboard | FinCore')
@section('page-title', 'Debt Collections & Overdue Operations')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Collections & Recovery Management
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Monitor loans approaching month-end, overdue balances, compound interest, and priority defaulted accounts.
            </p>
        </div>
    </div>

    {{-- Collections Summary Cards Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Due Today</p>
            <p class="text-xl font-extrabold text-blue-600 mt-1">{{ number_format($dueTodayCount) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Due This Week</p>
            <p class="text-xl font-extrabold text-slate-900 mt-1">{{ number_format($dueThisWeekCount) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Due This Month</p>
            <p class="text-xl font-extrabold text-slate-900 mt-1">{{ number_format($dueThisMonthCount) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Overdue Loans</p>
            <p class="text-xl font-extrabold text-orange-600 mt-1">{{ number_format($overdueCount) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Overdue Amount</p>
            <p class="text-lg font-extrabold text-orange-600 mt-1 truncate">GHS {{ number_format($overdueAmount, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Defaulted Loans</p>
            <p class="text-xl font-extrabold text-red-600 mt-1">{{ number_format($defaultedCount) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Defaulted Amount</p>
            <p class="text-lg font-extrabold text-red-600 mt-1 truncate">GHS {{ number_format($defaultedAmount, 2) }}</p>
        </div>
    </div>

    {{-- Search & Priority Filter --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('collections.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer name, phone or loan number..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">All Collection Statuses</option>
                        <option value="overdue" @selected(request('status') === 'overdue')>Overdue Loans Only</option>
                        <option value="defaulted" @selected(request('status') === 'defaulted')>Defaulted Loans Only</option>
                        <option value="partially_paid" @selected(request('status') === 'partially_paid')>Partially Paid</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('collections.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Collections Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-900 text-base">Priority Debt Collection Queue</h2>
            <span class="text-xs text-slate-400">Sorted by highest outstanding balance</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Priority</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Loan Number</th>
                        <th class="px-5 py-3.5 text-right">Original Principal</th>
                        <th class="px-5 py-3.5 text-right">Compound Interest</th>
                        <th class="px-5 py-3.5 text-right">Total Outstanding</th>
                        <th class="px-5 py-3.5 text-center">Due Date</th>
                        <th class="px-5 py-3.5 text-center">Days Overdue</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        @php
                            $daysOverdue = 0;
                            if ($loan->maturity_date && now()->greaterThan($loan->maturity_date) && $loan->outstanding_balance > 0) {
                                $daysOverdue = (int) now()->diffInDays($loan->maturity_date);
                            }
                            $compound = $loan->interestCycles->sum('interest_charged');
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5">
                                @if($loan->status === 'defaulted' || $daysOverdue > 60)
                                    <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-800 font-extrabold text-[10px] uppercase">HIGH RISK</span>
                                @elseif($daysOverdue > 30)
                                    <span class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 font-extrabold text-[10px] uppercase">MEDIUM</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] uppercase">STANDARD</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                <a href="{{ route('customers.show', $loan->customer) }}" class="hover:text-blue-600">
                                    {{ $loan->customer->full_name }}
                                </a>
                                <div class="text-[10px] text-slate-400 font-normal">{{ $loan->customer->phone }}</div>
                            </td>
                            <td class="px-5 py-3.5 font-mono font-bold text-blue-600">
                                <a href="{{ route('loans.show', $loan) }}">{{ $loan->loan_number }}</a>
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-slate-900">
                                GHS {{ number_format($loan->principal_amount, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-purple-600">
                                GHS {{ number_format($compound, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-extrabold text-rose-600">
                                GHS {{ number_format($loan->outstanding_balance, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-600 font-medium">
                                {{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Month-End' }}
                            </td>
                            <td class="px-5 py-3.5 text-center font-bold text-rose-600">
                                {{ $daysOverdue > 0 ? $daysOverdue . ' days' : '0' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <x-status-badge :status="$loan->status" />
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('collections.show', $loan) }}" class="px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-[11px] rounded-lg transition">
                                    Collection View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center text-slate-400">
                                <x-empty-state title="No Overdue Loans" message="There are currently no active loans requiring collections attention." />
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