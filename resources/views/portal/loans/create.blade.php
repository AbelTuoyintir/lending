@extends('layouts.portal')

@section('title', 'Apply for Lending / Loan — De ferg money')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb & Title --}}
    <div>
        <a href="{{ route('portal.loans.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 mb-2 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Loans
        </a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Apply for Lending Facility</h1>
        <p class="text-xs text-slate-500">Select a loan product and submit your application for review and instant processing.</p>
    </div>

    {{-- Loan Application Form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6"
         x-data="{
             selectedProduct: '',
             products: {{ $loanProducts->toJson() }},
             amount: '{{ old('principal_amount', '') }}',
             duration: '{{ old('duration', '') }}',
             get currentProduct() {
                 return this.products.find(p => p.id == this.selectedProduct) || null;
             },
             get estimatedInterest() {
                 if (!this.currentProduct || !this.amount) return 0;
                 let rate = parseFloat(this.currentProduct.interest_rate || 0);
                 let amt = parseFloat(this.amount || 0);
                 return (amt * (rate / 100)).toFixed(2);
             },
             get estimatedTotal() {
                 let amt = parseFloat(this.amount || 0);
                 let interest = parseFloat(this.estimatedInterest || 0);
                 return (amt + interest).toFixed(2);
             }
         }">

        <form action="{{ route('portal.loans.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Loan Product Selector --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Select Loan Product *
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($loanProducts as $product)
                        <label class="relative flex flex-col p-4 border rounded-xl cursor-pointer transition"
                               :class="selectedProduct == '{{ $product->id }}' ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50'">
                            <input type="radio" name="loan_product_id" value="{{ $product->id }}" x-model="selectedProduct" class="sr-only" required>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm">{{ $product->name }}</span>
                                <span class="text-xs font-extrabold text-blue-600 bg-blue-100/80 px-2 py-0.5 rounded-md">{{ $product->interest_rate }}% Interest</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $product->description ?? 'Flexible loan package with quick approval.' }}</p>
                            <div class="mt-3 pt-2 border-t border-slate-200/60 text-[11px] text-slate-600 flex justify-between">
                                <span>Range: GHS {{ number_format($product->min_amount, 0) }} - {{ number_format($product->max_amount, 0) }}</span>
                                <span>Duration: {{ $product->min_duration }}-{{ $product->max_duration }} mos</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Principal Amount & Duration --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Principal Amount (GHS) *
                    </label>
                    <input type="number" step="0.01" name="principal_amount" x-model="amount" value="{{ old('principal_amount') }}" required placeholder="e.g. 5000.00"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <template x-if="currentProduct">
                        <p class="text-[11px] text-slate-500 mt-1">
                            Min: GHS <span x-text="parseFloat(currentProduct.min_amount).toLocaleString()"></span> | Max: GHS <span x-text="parseFloat(currentProduct.max_amount).toLocaleString()"></span>
                        </p>
                    </template>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Duration (Months) *
                    </label>
                    <input type="number" name="duration" x-model="duration" value="{{ old('duration', 1) }}" required min="1" max="120"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <template x-if="currentProduct">
                        <p class="text-[11px] text-slate-500 mt-1">
                            Min: <span x-text="currentProduct.min_duration"></span> month(s) | Max: <span x-text="currentProduct.max_duration"></span> month(s)
                        </p>
                    </template>
                </div>
            </div>

            {{-- First Payment Date & Notes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Preferred First Payment Date
                    </label>
                    <input type="date" name="first_payment_date" value="{{ old('first_payment_date', now()->addMonth()->startOfMonth()->toDateString()) }}"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Purpose of Loan / Notes
                    </label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="e.g. Business expansion, personal emergency"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Real-time Interest & Total Payable Estimation Card --}}
            <div x-show="currentProduct && amount > 0" class="bg-blue-50/70 border border-blue-200 rounded-xl p-5 space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-900">Estimated Repayment Calculation</h4>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="bg-white p-3 rounded-lg border border-blue-100">
                        <span class="block text-slate-400 font-semibold">Principal</span>
                        <span class="font-extrabold text-slate-900 text-sm">GHS <span x-text="parseFloat(amount || 0).toFixed(2)"></span></span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-blue-100">
                        <span class="block text-slate-400 font-semibold">Initial Interest (<span x-text="currentProduct ? currentProduct.interest_rate : 0"></span>%)</span>
                        <span class="font-extrabold text-blue-600 text-sm">GHS <span x-text="estimatedInterest"></span></span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-blue-100">
                        <span class="block text-slate-400 font-semibold">Estimated Total</span>
                        <span class="font-black text-emerald-600 text-sm">GHS <span x-text="estimatedTotal"></span></span>
                    </div>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow transition text-sm">
                    Submit Loan Application
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
