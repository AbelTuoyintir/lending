@extends('layouts.app')

@section('title', $loanProduct->name . ' | Loan Product Details | FinCore')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 text-red-800 border border-red-200 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('loan-products.index') }}" class="text-slate-400 hover:text-slate-600 text-sm">
                &larr; Back to Loan Products
            </a>
            <h1 class="text-2xl font-bold mt-1">{{ $loanProduct->name }}</h1>
            <p class="text-sm text-slate-500">Code: <span class="font-mono text-slate-700 font-semibold">{{ $loanProduct->code }}</span></p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('loan-products.edit', $loanProduct) }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
                Edit Product
            </a>
            <form action="{{ route('loan-products.destroy', $loanProduct) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-50 text-red-600 text-sm font-medium hover:bg-red-100">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Interest & Rates</h3>
            <div>
                <p class="text-xs text-slate-400">Interest Rate</p>
                <p class="text-lg font-bold text-slate-900">{{ $loanProduct->interest_rate }}%</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Interest Type</p>
                <p class="text-sm font-semibold capitalize text-slate-800">{{ str_replace('_', ' ', $loanProduct->interest_type) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Repayment Frequency</p>
                <p class="text-sm font-semibold capitalize text-slate-800">{{ $loanProduct->repayment_frequency }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Limits & Rules</h3>
            <div>
                <p class="text-xs text-slate-400">Allowed Amount Range</p>
                <p class="text-sm font-semibold text-slate-800">
                    GHS {{ number_format($loanProduct->min_amount, 2) }} – {{ number_format($loanProduct->max_amount, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Allowed Duration Range</p>
                <p class="text-sm font-semibold text-slate-800">
                    {{ $loanProduct->min_duration }} – {{ $loanProduct->max_duration }} Months
                </p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Product Info</h3>
            <div>
                <p class="text-xs text-slate-400 mb-1">Status</p>
                @if($loanProduct->is_active)
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">Active</span>
                @else
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">Disabled</span>
                @endif
            </div>
            <div>
                <p class="text-xs text-slate-400">Total Loans Issued</p>
                <p class="text-sm font-semibold text-slate-800">{{ $loanProduct->loans_count ?? 0 }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Description</p>
                <p class="text-sm text-slate-600 italic">{{ $loanProduct->description ?? 'No description.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
