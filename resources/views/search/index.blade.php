@extends('layouts.app')

@section('title', 'Global Search | FinCore')
@section('page-title', 'Global Search Results')

@section('content')
<div class="space-y-6">

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
        <form action="{{ route('search') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" name="q" value="{{ $query }}" placeholder="Search by customer name, phone, customer number, loan number, or payment reference..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shrink-0">
                Search Database
            </button>
        </form>
        @if($query)
            <p class="text-xs text-slate-500 mt-3">Showing search results for: <span class="font-bold text-slate-900">"{{ $query }}"</span></p>
        @endif
    </div>

    @if(!empty($query))

        {{-- Customers Results --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center justify-between">
                <span>Matching Customers</span>
                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full font-mono">{{ $customers->count() }} found</span>
            </h3>

            @if($customers->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($customers as $customer)
                        <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <a href="{{ route('customers.show', $customer) }}" class="font-bold text-blue-600 hover:text-blue-700 text-sm">
                                    {{ $customer->full_name }}
                                </a>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $customer->customer_number }} • {{ $customer->phone }} • {{ $customer->email ?? 'No email' }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-status-badge :status="$customer->status" />
                                <a href="{{ route('customers.show', $customer) }}" class="px-3 py-1.5 border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50">View Profile</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 italic">No customers matched your query.</p>
            @endif
        </div>

        {{-- Loans Results --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center justify-between">
                <span>Matching Loans</span>
                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full font-mono">{{ $loans->count() }} found</span>
            </h3>

            @if($loans->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($loans as $loan)
                        <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <a href="{{ route('loans.show', $loan) }}" class="font-mono font-bold text-blue-600 hover:text-blue-700 text-sm">
                                    {{ $loan->loan_number }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5">Borrower: <span class="font-semibold text-slate-700">{{ $loan->customer->full_name }}</span> • Payable: GHS {{ number_format($loan->total_payable, 2) }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-amber-600">GHS {{ number_format($loan->outstanding_balance, 2) }} balance</span>
                                <x-status-badge :status="$loan->status" />
                                <a href="{{ route('loans.show', $loan) }}" class="px-3 py-1.5 border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50">View Loan</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 italic">No loans matched your query.</p>
            @endif
        </div>

        {{-- Payments Results --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center justify-between">
                <span>Matching Payments</span>
                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full font-mono">{{ $payments->count() }} found</span>
            </h3>

            @if($payments->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($payments as $payment)
                        <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <a href="{{ route('payments.show', $payment) }}" class="font-mono font-bold text-slate-900 text-sm">
                                    {{ $payment->payment_number }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5">Customer: {{ $payment->customer->full_name }} • Loan: {{ $payment->loan->loan_number }} • Ref: {{ $payment->reference }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-emerald-600">+ GHS {{ number_format($payment->amount, 2) }}</span>
                                <a href="{{ route('payments.show', $payment) }}" class="px-3 py-1.5 border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50">View Receipt</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 italic">No payment transactions matched your query.</p>
            @endif
        </div>

    @else

        <x-empty-state
            title="Search the FinCore Loan Database"
            message="Enter a search term above to find customers, active/settled loans, or payment receipts."
        />

    @endif

</div>
@endsection