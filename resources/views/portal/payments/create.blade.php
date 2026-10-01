@extends('layouts.portal')

@section('title', 'Make Payment — Customer Lending Portal')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{ payAmount: '{{ $selectedLoan ? $selectedLoan->outstanding_balance : 0 }}', method: 'mobile_money' }">

    {{-- Breadcrumb --}}
    <div>
        <a href="{{ route('portal.payments.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 mb-1 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Payments
        </a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Initiate Loan Payment</h1>
        <p class="text-xs text-slate-500">Pay your loan balance using Mobile Money, Card, Bank Transfer, or Paystack Checkout.</p>
    </div>

    @if(!$selectedLoan || $loans->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center space-y-3">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">No Outstanding Loan Balances</h3>
            <p class="text-xs text-slate-500">You currently have no active loans with unpaid balance due.</p>
            <a href="{{ route('portal.dashboard') }}" class="inline-block bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl">Return to Dashboard</a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">

            {{-- Select Loan Facility --}}
            @if($loans->count() > 1)
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Select Active Loan *</label>
                    <select onchange="window.location.href='{{ route('portal.payments.create') }}?loan_id=' + this.value"
                            class="w-full text-sm font-semibold rounded-xl border border-slate-300 px-3.5 py-2.5 bg-slate-50">
                        @foreach($loans as $l)
                            <option value="{{ $l->id }}" {{ $selectedLoan->id === $l->id ? 'selected' : '' }}>
                                {{ $l->loan_number }} — GHS {{ number_format($l->outstanding_balance, 2) }} Outstanding
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Current Balance Box --}}
            <div class="bg-slate-900 text-white rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-md">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Current Balance</span>
                    <div class="text-3xl font-black text-amber-400 mt-1">
                        GHS {{ number_format($selectedLoan->outstanding_balance, 2) }}
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Loan #{{ $selectedLoan->loan_number }} | Due {{ $selectedLoan->maturity_date ? \Carbon\Carbon::parse($selectedLoan->maturity_date)->format('d M Y') : 'Month End' }}
                    </p>
                </div>
                <div class="shrink-0 bg-slate-800 px-3.5 py-2 rounded-xl border border-slate-700 text-right">
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Loan Status</span>
                    <span class="text-xs font-extrabold text-emerald-400 uppercase tracking-wider">{{ str_replace('_', ' ', $selectedLoan->status) }}</span>
                </div>
            </div>

            <form action="{{ route('portal.payments.paystack.initialize') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="loan_id" value="{{ $selectedLoan->id }}">

                {{-- Payment Method Choice --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Select Payment Method *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="p-3.5 rounded-xl border cursor-pointer transition flex flex-col gap-1"
                               :class="method === 'mobile_money' ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500/30' : 'border-slate-200 hover:bg-slate-50'"
                               @click="method = 'mobile_money'">
                            <span class="text-xs font-bold text-slate-900">Mobile Money</span>
                            <span class="text-[10px] text-slate-500">MTN, Telecel, AT</span>
                        </label>

                        <label class="p-3.5 rounded-xl border cursor-pointer transition flex flex-col gap-1"
                               :class="method === 'card' ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500/30' : 'border-slate-200 hover:bg-slate-50'"
                               @click="method = 'card'">
                            <span class="text-xs font-bold text-slate-900">Bank Card</span>
                            <span class="text-[10px] text-slate-500">Visa / Mastercard</span>
                        </label>

                        <label class="p-3.5 rounded-xl border cursor-pointer transition flex flex-col gap-1"
                               :class="method === 'bank' ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500/30' : 'border-slate-200 hover:bg-slate-50'"
                               @click="method = 'bank'">
                            <span class="text-xs font-bold text-slate-900">Bank Transfer</span>
                            <span class="text-[10px] text-slate-500">Direct Account Pay</span>
                        </label>
                    </div>
                </div>

                {{-- Amount to Pay Input --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Amount to Pay (GHS) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 font-bold text-sm">GHS</span>
                        <input type="number" step="0.01" min="1" max="{{ $selectedLoan->outstanding_balance }}" name="amount" x-model="payAmount" required
                               class="w-full text-lg font-black rounded-xl border border-slate-300 pl-14 pr-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                        <span>Min: GHS 1.00</span>
                        <button type="button" @click="payAmount = '{{ $selectedLoan->outstanding_balance }}'" class="text-blue-600 font-bold hover:underline">
                            Pay Full Balance (GHS {{ number_format($selectedLoan->outstanding_balance, 2) }})
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 px-6 rounded-xl shadow-md transition text-sm flex items-center justify-center gap-2">
                    <span>Continue Payment</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

        </div>
    @endif

</div>
@endsection
