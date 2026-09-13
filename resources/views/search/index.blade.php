@extends('layouts.app')

@section('title', 'Global Search Results | FinCore')

@section('content')

<div class="space-y-6">

    <x-page-header
        title="Global System Search"
        subtitle="Search results across customers, loan accounts, and payment collections."
    />

    {{-- Search Input Form --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('search') }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="q" value="{{ $query }}" placeholder="Type customer name, phone, loan #, or receipt #..."
                       class="w-full pl-11 pr-28 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
                <button type="submit" class="absolute right-2 top-2 px-5 py-1.5 bg-blue-600 text-white font-bold rounded-lg text-xs hover:bg-blue-700 transition">
                    Search
                </button>
            </div>
        </form>
    </div>

    @if(!empty($query))
        <p class="text-xs text-slate-500 font-medium">Showing search results for "<span class="font-bold text-slate-900">{{ $query }}</span>"</p>

        {{-- CUSTOMERS RESULTS --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                <span>Customers Found ({{ $customers->count() }})</span>
            </h2>

            @forelse($customers as $customer)
                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition">
                    <div>
                        <p class="text-sm font-bold text-slate-900">{{ $customer->full_name }}</p>
                        <p class="text-xs text-slate-500">Customer #: {{ $customer->customer_number }} &bull; Phone: {{ $customer->phone }}</p>
                    </div>
                    <a href="{{ route('customers.show', $customer) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 font-bold text-xs rounded-lg hover:bg-blue-100">
                        View Profile
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400">No customers found matching "{{ $query }}".</p>
            @endforelse
        </div>

        {{-- LOANS RESULTS --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                <span>Loans Found ({{ $loans->count() }})</span>
            </h2>

            @forelse($loans as $loan)
                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition">
                    <div>
                        <p class="text-sm font-bold text-slate-900">{{ $loan->loan_number }} &bull; {{ $loan->customer->full_name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate-500">Principal: GHS {{ number_format($loan->principal_amount, 2) }} &bull; Outstanding: GHS {{ number_format($loan->outstanding_balance, 2) }}</p>
                    </div>
                    <a href="{{ route('loans.show', $loan) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 font-bold text-xs rounded-lg hover:bg-blue-100">
                        View Loan
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400">No loan accounts found matching "{{ $query }}".</p>
            @endforelse
        </div>

        {{-- PAYMENTS RESULTS --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                <span>Payments Found ({{ $payments->count() }})</span>
            </h2>

            @forelse($payments as $payment)
                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition">
                    <div>
                        <p class="text-sm font-bold text-slate-900">{{ $payment->payment_number }} &bull; {{ $payment->customer->full_name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate-500">Amount: GHS {{ number_format($payment->amount, 2) }} &bull; Reference: {{ $payment->reference }}</p>
                    </div>
                    <a href="{{ route('payments.show', $payment) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 font-bold text-xs rounded-lg hover:bg-blue-100">
                        View Receipt
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400">No payments found matching "{{ $query }}".</p>
            @endforelse
        </div>
    @endif

</div>

@endsection