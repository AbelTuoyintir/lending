@extends('layouts.app')

@section('title', 'Create Lending Product | FinCore')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('loan-products.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
            &larr; Back to Lending products
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-2">
            Create Lending Product
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Define product parameters, default initial interest rate, and borrowing bounds.
        </p>
    </div>

    <form method="POST" action="{{ route('loan-products.store') }}" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Product Name *
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Standard Lendings"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Product Code *
                </label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g. LP-STD"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                Description
            </label>
            <textarea name="description" rows="2" placeholder="Brief description of Lending product eligibility..."
                      class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Minimum Amount (GHS) *
                </label>
                <input type="number" step="0.01" min="0" name="min_amount" value="{{ old('min_amount', 100) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Maximum Amount (GHS) *
                </label>
                <input type="number" step="0.01" min="0" name="max_amount" value="{{ old('max_amount', 50000) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Default Initial Interest Rate (%) *
                </label>
                <div class="relative">
                    <input type="number" step="0.01" min="0" name="interest_rate" value="{{ old('interest_rate', 30) }}" required
                           class="w-full pr-10 pl-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
                    <span class="absolute right-3.5 top-2.5 text-sm font-bold text-slate-400">%</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Status *
                </label>
                <select name="is_active" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition bg-white">
                    <option value="1" @selected(old('is_active', '1') == '1')>Active</option>
                    <option value="0" @selected(old('is_active') == '0')>Inactive</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Interest Type *
                </label>
                <select name="interest_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition bg-white">
                    <option value="flat" @selected(old('interest_type', 'flat') === 'flat')>Flat</option>
                    <option value="reducing_balance" @selected(old('interest_type') === 'reducing_balance')>Reducing Balance</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Repayment Frequency *
                </label>
                <select name="repayment_frequency" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition bg-white">
                    <option value="daily" @selected(old('repayment_frequency') === 'daily')>Daily</option>
                    <option value="weekly" @selected(old('repayment_frequency') === 'weekly')>Weekly</option>
                    <option value="biweekly" @selected(old('repayment_frequency') === 'biweekly')>Biweekly</option>
                    <option value="monthly" @selected(old('repayment_frequency', 'monthly') === 'monthly')>Monthly</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Minimum Duration (months) *
                </label>
                <input type="number" min="1" name="min_duration" value="{{ old('min_duration', 1) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Maximum Duration (months) *
                </label>
                <input type="number" min="1" name="max_duration" value="{{ old('max_duration', 12) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('loan-products.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm shadow-md shadow-blue-600/20 hover:bg-blue-700 transition">
                Save Product
            </button>
        </div>
    </form>

</div>

@endsection