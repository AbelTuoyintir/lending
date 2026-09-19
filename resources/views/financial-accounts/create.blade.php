@extends('layouts.app')

@section('title', 'Add Financial Account | FinCore')
@section('page-title', 'Add Financial Account')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('financial-accounts.index') }}" class="text-xs text-slate-500 hover:text-slate-800">&larr; Back to Financial Accounts</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-1">Add Lender Account</h1>
        <p class="text-sm text-slate-500 mt-1">Create the cash, bank, or mobile-money account used for loan disbursements.</p>
    </div>

    <form method="POST" action="{{ route('financial-accounts.store') }}" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Account Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Office Cash" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Account Number *</label>
                <input type="text" name="account_number" value="{{ old('account_number') }}" required placeholder="e.g. CASH-001" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Account Type *</label>
                <select name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                    <option value="cash" @selected(old('type', 'cash') === 'cash')>Cash</option>
                    <option value="bank" @selected(old('type') === 'bank')>Bank</option>
                    <option value="mobile_money" @selected(old('type') === 'mobile_money')>Mobile Money</option>
                    <option value="asset" @selected(old('type') === 'asset')>Asset</option>
                    <option value="equity" @selected(old('type') === 'equity')>Equity</option>
                    <option value="revenue" @selected(old('type') === 'revenue')>Revenue</option>
                    <option value="expense" @selected(old('type') === 'expense')>Expense</option>
                    <option value="liability" @selected(old('type') === 'liability')>Liability</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Opening Balance (GHS) *</label>
                <input type="number" step="0.01" min="0" name="current_balance" value="{{ old('current_balance', 0) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm">
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Active account available for disbursement</label>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('financial-accounts.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl">Save Account</button>
        </div>
    </form>
</div>
@endsection
