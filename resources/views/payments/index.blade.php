@extends('layouts.app')

@section('title', 'Payments | FinCore')
@section('page-title', 'Payments & Collections')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Payment Records</h1>
            <p class="text-xs text-slate-500 mt-1">Track client repayments and cash collections.</p>
        </div>
        <a href="{{ route('payments.manual-create') }}" class="shrink-0 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/20 transition">
            Add Manual Payment
        </a>
    </div>

    {{-- Top Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Payments</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($payments->total()) }}</h3>
            <p class="text-xs text-slate-500 mt-1">Completed collections</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Today's Collections</p>
            <h3 class="text-lg font-bold text-emerald-600 mt-1">GHS {{ number_format(\App\Models\Payment::where('status', 'completed')->whereDate('payment_date', today())->sum('amount'), 2) }}</h3>
            <p class="text-xs text-emerald-600/80 mt-1">Recorded today</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">This Month</p>
            <h3 class="text-lg font-bold text-blue-600 mt-1">GHS {{ number_format(\App\Models\Payment::where('status', 'completed')->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount'), 2) }}</h3>
            <p class="text-xs text-blue-600/80 mt-1">{{ now()->format('F Y') }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Collected</p>
            <h3 class="text-lg font-bold text-slate-900 mt-1">GHS {{ number_format(\App\Models\Payment::where('status', 'completed')->sum('amount'), 2) }}</h3>
            <p class="text-xs text-slate-500 mt-1">All time</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payment #, ref, or borrower..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <select name="payment_method" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white text-slate-700">
                    <option value="">All Payment Methods</option>
                    <option value="cash" @selected(request('payment_method') === 'cash')>Cash</option>
                    <option value="mobile_money" @selected(request('payment_method') === 'mobile_money')>Mobile Money</option>
                    <option value="bank_transfer" @selected(request('payment_method') === 'bank_transfer')>Bank Transfer</option>
                    <option value="card" @selected(request('payment_method') === 'card')>Card</option>
                    <option value="other" @selected(request('payment_method') === 'other')>Other</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">Filter Payments</button>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Payment #</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Loan #</th>
                        <th class="px-5 py-3.5">Method</th>
                        <th class="px-5 py-3.5">Reference</th>
                        <th class="px-5 py-3.5 text-right">Amount</th>
                        <th class="px-5 py-3.5 text-right">Date</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 font-mono font-semibold text-slate-900">
                                <a href="{{ route('payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $payment->customer->full_name }}</td>
                            <td class="px-5 py-4 font-mono font-semibold text-blue-600">
                                <a href="{{ route('loans.show', $payment->loan) }}">{{ $payment->loan->loan_number }}</a>
                            </td>
                            <td class="px-5 py-4 uppercase font-semibold text-slate-500 text-[10px]">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                            <td class="px-5 py-4 font-mono text-slate-600">{{ $payment->reference }}</td>
                            <td class="px-5 py-4 text-right font-bold text-emerald-600">+ GHS {{ number_format($payment->amount, 2) }}</td>
                            <td class="px-5 py-4 text-right text-slate-600">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('payments.show', $payment) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">Receipt</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">No payment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

</div>
@endsection