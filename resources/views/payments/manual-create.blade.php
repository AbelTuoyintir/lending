@extends('layouts.app')

@section('title', 'Add Manual Payment | FinCore')
@section('page-title', 'Add Manual Payment')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('payments.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Payments</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-1">Add Manual Payment</h1>
        <p class="text-xs text-slate-500 mt-1">Record a repayment received directly at the office, including cash payments.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
        <form action="{{ route('payments.manual-store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Loan Account *</label>
                <select name="loan_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Select a loan account</option>
                    @foreach($loans as $loan)
                        <option value="{{ $loan->id }}" @selected(old('loan_id') == $loan->id)>
                            {{ $loan->loan_number }} · {{ $loan->customer->full_name }} · GHS {{ number_format($loan->outstanding_balance, 2) }} outstanding
                        </option>
                    @endforeach
                </select>
                @if($loans->isEmpty())
                    <p class="text-xs text-amber-600 mt-1.5">There are no active loans with an outstanding balance.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Amount (GHS) *</label>
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Method *</label>
                    <select name="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="cash" @selected(old('payment_method', 'cash') === 'cash')>Cash</option>
                        <option value="mobile_money" @selected(old('payment_method') === 'mobile_money')>Mobile Money</option>
                        <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer</option>
                        <option value="card" @selected(old('payment_method') === 'card')>Card / Bank Deposit</option>
                        <option value="other" @selected(old('payment_method') === 'other')>Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Transaction Reference *</label>
                    <input type="text" name="reference" value="{{ old('reference', 'CASH-' . strtoupper(Str::random(10))) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Notes / Remarks</label>
                <textarea name="notes" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Optional payment remarks...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('payments.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Cancel</a>
                <button type="submit" @disabled($loans->isEmpty()) class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection