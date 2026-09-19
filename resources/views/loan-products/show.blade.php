@extends('layouts.app')

@section('title', $loanProduct->name . ' - Product Details | FinCore')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('loan-products.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                    &larr; Back to Lending products
                </a>
                <div class="flex items-center gap-3 mt-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $loanProduct->name }}</h1>
                    <x-status-badge :status="$loanProduct->is_active ? 'active' : 'inactive'" />
                </div>
                <p class="text-xs font-mono text-slate-500 mt-1">Code: {{ $loanProduct->code }}</p>
            </div>

            <div>
                <a href="{{ route('loan-products.edit', $loanProduct) }}" class="px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl text-sm shadow-md hover:bg-blue-700 transition">
                    Edit Product
                </a>
            </div>
        </div>
    </div>

    {{-- Specifications Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-stat-card title="Default Interest Rate" value="{{ number_format($loanProduct->interest_rate ?? 30, 0) }}%" subtitle="Initial interest rate" color="blue" />
        <x-stat-card title="Calculation Method" value="Monthly Compound" subtitle="Calendar month-end rule" color="indigo" />
        <x-stat-card title="Borrowing Range" value="GHS {{ number_format($loanProduct->min_amount ?? 100, 0) }} - {{ number_format($loanProduct->max_amount ?? 50000, 0) }}" subtitle="Min - Max Limits" color="slate" />
    </div>

    {{-- Description Box --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Product Description & Terms</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
            {{ $loanProduct->description ?? 'Standard Lending product template with 30% initial interest rate and 30% monthly compound interest on outstanding balances at month-end.' }}
        </p>
    </div>

</div>

@endsection