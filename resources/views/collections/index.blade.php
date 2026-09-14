@extends('layouts.app')

@section('title', 'Collections Dashboard | FinCore')
@section('page-title', 'Debt Collections & Overdue Management')

@section('content')
<div class="space-y-6">

    {{-- Debt Collections Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Overdue Loans</p>
            <h3 class="text-2xl font-bold text-red-600 mt-1">{{ number_format($loans->total()) }}</h3>
            <p class="text-xs text-red-600/80 mt-1">Past calendar month-end due date</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Overdue Outstanding</p>
            <h3 class="text-lg font-bold text-red-600 mt-1">GHS {{ number_format($loans->sum('outstanding_balance'), 2) }}</h3>
            <p class="text-xs text-slate-500 mt-1">Requires immediate follow-up</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Compound Interest Applied</p>
            <h3 class="text-lg font-bold text-purple-600 mt-1">GHS {{ number_format(\App\Models\LoanInterestCycle::sum('interest_charged'), 2) }}</h3>
            <p class="text-xs text-purple-600/80 mt-1">30% compounding on balance</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Defaulted Loans</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format(\App\Models\Loan::where('status', 'defaulted')->count()) }}</h3>
            <p class="text-xs text-slate-500 mt-1">Passed collection window</p>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search overdue loan #, customer name, or phone..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition shrink-0">Search Collections</button>
        </form>
    </div>

    {{-- Overdue & Defaulted Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Priority Collection Action Queue</h3>
                <p class="text-xs text-slate-500 mt-0.5">Loans sorted by highest outstanding balance requiring recovery.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Customer / Contact</th>
                        <th class="px-5 py-3.5">Loan Number</th>
                        <th class="px-5 py-3.5 text-right">Original Principal</th>
                        <th class="px-5 py-3.5 text-right">Amount Paid</th>
                        <th class="px-5 py-3.5 text-right">Current Outstanding</th>
                        <th class="px-5 py-3.5 text-center">Due Date</th>
                        <th class="px-5 py-3.5 text-center">Priority Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <a href="{{ route('customers.show', $loan->customer) }}" class="font-bold text-slate-900 hover:text-blue-600">{{ $loan->customer->full_name }}</a>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $loan->customer->phone }}</p>
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-blue-600">
                                <a href="{{ route('collections.show', $loan) }}">{{ $loan->loan_number }}</a>
                            </td>
                            <td class="px-5 py-4 text-right font-medium text-slate-700">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-emerald-600">GHS {{ number_format($loan->amount_paid, 2) }}</td>
                            <td class="px-5 py-4 text-right font-extrabold text-red-600">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="px-5 py-4 text-center font-medium text-slate-600">
                                {{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Month-End' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-100 text-red-800 ring-1 ring-inset ring-red-600/20">
                                    HIGH OVERDUE
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right space-x-1">
                                <a href="{{ route('payments.create', $loan) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">+ Pay</a>
                                <a href="{{ route('collections.show', $loan) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <p class="font-bold text-slate-700 text-sm">No overdue loans found!</p>
                                <p class="text-xs text-slate-400 mt-1">All active loans are currently within healthy repayment windows.</p>
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