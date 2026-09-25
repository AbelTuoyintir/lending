@extends('layouts.app')

@section('title', 'Record Payment | FinCore')
@section('page-title', 'Record Repayment')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="manualPaymentCalculator()">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('payments.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Payments</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Record Repayment Collection</h1>
            <p class="text-xs text-slate-500">Record a customer repayment into the lending ledger.</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
        <form action="{{ route('payments.manual-store') }}" method="POST" id="manualPaymentForm" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Select Active Loan *</label>
                <select name="loan_id" x-model="selectedLoanId" @change="updateLoanDetails()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">-- Choose borrower loan --</option>
                    @foreach($loans as $loan)
                        <option value="{{ $loan->id }}"
                                data-number="{{ $loan->loan_number }}"
                                data-customer="{{ $loan->customer->full_name }}"
                                data-outstanding="{{ $loan->outstanding_balance }}"
                                @selected(old('loan_id', request('loan_id')) == $loan->id)>
                            {{ $loan->loan_number }} — {{ $loan->customer->full_name }} (Outstanding: GHS {{ number_format($loan->outstanding_balance, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Selected Loan Banner --}}
            <div x-show="selectedLoan.number" x-cloak class="p-4 bg-slate-900 text-white rounded-xl space-y-2 text-xs shadow-md">
                <div class="flex items-center justify-between border-b border-white/10 pb-2">
                    <span class="font-mono font-bold text-blue-400 text-sm" x-text="selectedLoan.number"></span>
                    <span class="font-bold text-slate-300" x-text="selectedLoan.customer"></span>
                </div>
                <div class="flex items-center justify-between text-slate-300">
                    <span>Current Loan Outstanding:</span>
                    <span class="text-base font-extrabold text-amber-400">GHS <span x-text="formatMoney(selectedLoan.outstanding)"></span></span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Amount (GHS) *</label>
                <input type="number" step="0.01" name="amount" x-model.number="amount" @input="calculateRemaining()" min="0.01" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                <p x-show="isOverpaying" x-cloak class="text-xs text-red-600 font-bold mt-1.5">⚠️ Warning: Entered payment amount exceeds current loan outstanding balance!</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Method *</label>
                    <select name="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card / Bank Deposit</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Transaction Reference *</label>
                    <input type="text" name="reference" value="{{ 'PAY-' . strtoupper(Str::random(10)) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Payment Date *</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Notes / Remarks</label>
                <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500" placeholder="Optional collection remarks..."></textarea>
            </div>

            {{-- Live Calculation Box --}}
            <div x-show="selectedLoan.number" x-cloak class="rounded-xl p-4 bg-slate-50 border border-slate-200 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Outstanding Before Payment:</span>
                    <span class="font-bold text-slate-900">GHS <span x-text="formatMoney(selectedLoan.outstanding)"></span></span>
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
                <a href="{{ route('payments.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Cancel</a>
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
function manualPaymentCalculator() {
    return {
        selectedLoanId: '{{ old('loan_id', request('loan_id')) }}',
        selectedLoan: { number: '', customer: '', outstanding: 0 },
        amount: 0,
        remainingBalance: 0,
        isOverpaying: false,

        init() {
            this.updateLoanDetails();
        },

        updateLoanDetails() {
            const select = document.querySelector('select[name="loan_id"]');
            if (select && select.selectedIndex > 0) {
                const opt = select.options[select.selectedIndex];
                const out = parseFloat(opt.getAttribute('data-outstanding') || '0');
                this.selectedLoan = {
                    number: opt.getAttribute('data-number') || '',
                    customer: opt.getAttribute('data-customer') || '',
                    outstanding: out
                };
                if (!this.amount || this.amount === 0) {
                    this.amount = out;
                }
            } else {
                this.selectedLoan = { number: '', customer: '', outstanding: 0 };
            }
            this.calculateRemaining();
        },

        calculateRemaining() {
            const amt = parseFloat(this.amount) || 0;
            this.remainingBalance = this.selectedLoan.outstanding - amt;
            this.isOverpaying = amt > this.selectedLoan.outstanding && this.selectedLoan.outstanding > 0;
        },

        formatMoney(val) {
            return (parseFloat(val) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        confirmPayment() {
            if (!this.selectedLoanId) {
                Swal.fire('Loan Required', 'Please select a loan first.', 'warning');
                return;
            }

            if (!this.amount || this.amount <= 0) {
                Swal.fire('Amount Required', 'Please enter a valid payment amount.', 'error');
                return;
            }

            Swal.fire({
                title: 'Confirm Payment',
                text: `Record repayment of GHS ${this.formatMoney(this.amount)} for Loan ${this.selectedLoan.number}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Record Payment',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('manualPaymentForm').submit();
                }
            });
        }
    }
}
</script>
@endpush