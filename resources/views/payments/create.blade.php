@extends('layouts.app')

@section('title', 'Record Payment | FinCore')
@section('page-title', 'Record Loan Payment')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="paymentCalculator({{ $loan->outstanding_balance }})">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('loans.show', $loan) }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Loan Details</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Record Repayment</h1>
            <p class="text-xs text-slate-500">Loan #: <span class="font-mono font-bold text-blue-600">{{ $loan->loan_number }}</span> • Borrower: <span class="font-semibold text-slate-800">{{ $loan->customer->full_name }}</span></p>
        </div>
    </div>

    {{-- Loan Current Financial Summary Box --}}
    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-xl space-y-3">
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
            <span class="text-xs text-slate-400">Current Outstanding Balance</span>
            <span class="text-2xl font-extrabold text-amber-400">GHS {{ number_format($loan->outstanding_balance, 2) }}</span>
        </div>
        <div class="grid grid-cols-3 gap-2 text-xs text-slate-300">
            <div>Principal: <span class="font-semibold text-white">GHS {{ number_format($loan->principal_amount, 2) }}</span></div>
            <div>Total Payable: <span class="font-semibold text-white">GHS {{ number_format($loan->total_payable, 2) }}</span></div>
            <div>Already Paid: <span class="font-semibold text-emerald-400">GHS {{ number_format($loan->amount_paid, 2) }}</span></div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
        <form action="{{ route('payments.store', $loan) }}" method="POST" id="paymentForm" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Amount (GHS) *</label>
                <input type="number" step="0.01" name="amount" x-model.number="amount" @input="calculateRemaining()" min="0.01" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                <p x-show="isOverpaying" x-cloak class="text-xs text-red-600 font-bold mt-1.5">⚠️ Warning: Entered payment amount exceeds current loan outstanding balance!</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Method *</label>
                    <select name="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card / Bank Deposit</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Transaction Reference *</label>
                    <input type="text" name="reference" value="{{ 'PAY-' . strtoupper(Str::random(10)) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Date *</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Notes / Remarks</label>
                <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Optional payment remarks..."></textarea>
            </div>

            {{-- Live Remaining Calculation Box --}}
            <div class="rounded-xl p-4 bg-slate-50 border border-slate-200 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Outstanding Before Payment:</span>
                    <span class="font-bold text-slate-900">GHS {{ number_format($loan->outstanding_balance, 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Payment Amount:</span>
                    <span class="font-bold text-emerald-600">- GHS <span x-text="formatMoney(amount)"></span></span>
                </div>
                <div class="flex items-center justify-between border-t border-slate-200 pt-2 text-sm">
                    <span class="font-bold text-slate-900">Remaining Loan Balance:</span>
                    <span class="font-extrabold" :class="remainingBalance < 0 ? 'text-red-600' : 'text-blue-600'">GHS <span x-text="formatMoney(remainingBalance)"></span></span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('loans.show', $loan) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Cancel</a>
                <button type="button" @click="confirmPayment()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition">
                    Save Payment
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function paymentCalculator(currentOutstanding) {
    return {
        outstanding: currentOutstanding,
        amount: currentOutstanding,
        remainingBalance: 0,
        isOverpaying: false,

        init() {
            this.calculateRemaining();
        },

        calculateRemaining() {
            const amt = parseFloat(this.amount) || 0;
            this.remainingBalance = this.outstanding - amt;
            this.isOverpaying = amt > this.outstanding;
        },

        formatMoney(val) {
            return (parseFloat(val) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        confirmPayment() {
            if (!this.amount || this.amount <= 0) {
                Swal.fire('Error', 'Please enter a valid payment amount.', 'error');
                return;
            }

            Swal.fire({
                title: 'Confirm Payment',
                text: `Record repayment of GHS ${this.formatMoney(this.amount)} for Loan {{ $loan->loan_number }}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Record Payment',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('paymentForm').submit();
                }
            });
        }
    }
}
</script>
@endpush