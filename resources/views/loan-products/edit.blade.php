@extends('layouts.app')

@section('title', 'Edit Loan Product | FinCore')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('loan-products.show', $loanProduct) }}" class="text-slate-400 hover:text-slate-600 text-sm">
            &larr; Back to Loan Product
        </a>
        <h1 class="text-2xl font-bold mt-1">Edit Loan Product: {{ $loanProduct->name }}</h1>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 text-red-800 border border-red-200 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <form action="{{ route('loan-products.update', $loanProduct) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $loanProduct->name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Product Code *</label>
                    <input type="text" name="code" value="{{ old('code', $loanProduct->code) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Interest Rate (%) *</label>
                    <input type="number" step="0.01" name="interest_rate" value="{{ old('interest_rate', $loanProduct->interest_rate) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Interest Type *</label>
                    <select name="interest_type" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                        <option value="flat" @selected(old('interest_type', $loanProduct->interest_type) === 'flat')>Flat Rate</option>
                        <option value="reducing_balance" @selected(old('interest_type', $loanProduct->interest_type) === 'reducing_balance')>Reducing Balance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Repayment Frequency *</label>
                    <select name="repayment_frequency" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                        <option value="monthly" @selected(old('repayment_frequency', $loanProduct->repayment_frequency) === 'monthly')>Monthly</option>
                        <option value="weekly" @selected(old('repayment_frequency', $loanProduct->repayment_frequency) === 'weekly')>Weekly</option>
                        <option value="biweekly" @selected(old('repayment_frequency', $loanProduct->repayment_frequency) === 'biweekly')>Bi-weekly</option>
                        <option value="daily" @selected(old('repayment_frequency', $loanProduct->repayment_frequency) === 'daily')>Daily</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Min Amount (GHS) *</label>
                    <input type="number" step="0.01" name="min_amount" value="{{ old('min_amount', $loanProduct->min_amount) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Max Amount (GHS) *</label>
                    <input type="number" step="0.01" name="max_amount" value="{{ old('max_amount', $loanProduct->max_amount) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Min Duration (Months) *</label>
                    <input type="number" name="min_duration" value="{{ old('min_duration', $loanProduct->min_duration) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Max Duration (Months) *</label>
                    <input type="number" name="max_duration" value="{{ old('max_duration', $loanProduct->max_duration) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">{{ old('description', $loanProduct->description) }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $loanProduct->is_active)) class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Is Product Active?</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('loan-products.show', $loanProduct) }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200">Cancel</a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-lg shadow-blue-600/20">
                    Update Loan Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
