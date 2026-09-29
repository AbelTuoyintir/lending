@extends('layouts.app')

@section('title', 'Create Lending | FinCore')
@section('page-title', 'Create New Lending')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="loanCalculator()">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('loans.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Lendings</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Issue New Administrative Lending</h1>
            <p class="text-xs text-slate-500">Repayment is aligned with calendar month-end. Default initial interest rate is 30%.</p>
        </div>
    </div>

    <form action="{{ route('loans.store') }}" method="POST" id="loanForm" class="space-y-6">
        @csrf

        {{-- Step 1: Select Customer --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center">1</span>
                    Select Borrower Customer
                </h2>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Select Customer *</label>
                <select name="customer_id" x-model="selectedCustomerId" @change="updateCustomerDetails()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">-- Choose active customer --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                                data-name="{{ $customer->full_name }}"
                                data-phone="{{ $customer->phone }}"
                                data-status="{{ $customer->status }}"
                                data-outstanding="{{ $customer->loans->whereIn('status', ['disbursed','active','partially_paid','overdue'])->sum('outstanding_balance') }}"
                                @selected(old('customer_id', request('customer_id')) == $customer->id)>
                            {{ $customer->full_name }} ({{ $customer->customer_number }}) - {{ $customer->phone }}
                        </option>
                    @endforeach
                </select>
                @if($customers->isEmpty())
                    <p class="text-xs text-amber-600 mt-2">No active customers found. Please <a href="{{ route('customers.create') }}" class="underline font-bold">add a customer</a> first.</p>
                @endif
            </div>

            {{-- Selected Customer Info Box --}}
            <div x-show="selectedCustomer.name" x-cloak class="rounded-xl p-4 bg-slate-50 border border-slate-200 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-sm" x-text="selectedCustomer.name"></span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase" :class="selectedCustomer.status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" x-text="selectedCustomer.status"></span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-slate-600">
                    <div>Phone: <span class="font-semibold text-slate-900" x-text="selectedCustomer.phone"></span></div>
                    <div>Current Active Balance: <span class="font-bold text-amber-600">GHS <span x-text="formatMoney(selectedCustomer.outstanding)"></span></span></div>
                </div>
                <div x-show="selectedCustomer.status === 'blacklisted'" class="p-2 bg-red-100 text-red-800 font-bold rounded-lg text-xs">
                    ⚠️ Blacklisted customers are prohibited from receiving new loan disbursements.
                </div>
            </div>
        </div>

        {{-- Step 2: Loan Product & Details --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center">2</span>
                    Lending Details & Product
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1.5">Lending Product *</label>
                    <select name="loan_product_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 bg-white">
                        @foreach($loanProducts as $product)
                            <option value="{{ $product->id }}" @selected(old('loan_product_id') == $product->id)>
                                {{ $product->name }} ({{ number_format($product->interest_rate, 0) }}% Interest)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1.5">Principal Amount (GHS) *</label>
                    <input type="number" step="0.01" name="principal_amount" x-model.number="principal" @input="calculate()" min="1" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500" placeholder="e.g. 1000.00">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1.5">Interest Rate (%) *</label>
                    <input type="number" step="0.01" name="interest_rate_display" value="30.00" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold" title="Default rate: 30%">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1.5">Lending Date *</label>
                    <input type="date" name="loan_date" x-model="loanDate" @change="calculateDueDate()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1.5">Repayment Month / Duration (Months) *</label>
                    <input type="number" name="duration" value="1" min="1" max="12" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Administrative Notes</label>
                <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500" placeholder="Purpose of the money requested, collateral details, or approval remarks..."></textarea>
            </div>
        </div>

        {{-- Step 3: Live Financial Calculation Breakdown --}}
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="font-bold text-white text-base flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center">3</span>
                    Live Financial Calculation Breakdown
                </h3>
                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-xs rounded-full font-mono font-bold">Default 30% Interest</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <p class="text-slate-400">Principal Amount</p>
                    <p class="text-lg font-bold text-white mt-1">GHS <span x-text="formatMoney(principal)"></span></p>
                </div>
                <div>
                    <p class="text-slate-400">Initial Interest (30%)</p>
                    <p class="text-lg font-bold text-blue-400 mt-1">GHS <span x-text="formatMoney(interest)"></span></p>
                </div>
                <div>
                    <p class="text-slate-400">Initial Total Payable</p>
                    <p class="text-lg font-bold text-emerald-400 mt-1">GHS <span x-text="formatMoney(totalPayable)"></span></p>
                </div>
                <div>
                    <p class="text-slate-400">Calendar Due Date</p>
                    <p class="text-sm font-bold text-amber-300 mt-1" x-text="dueDateFormatted"></p>
                </div>
            </div>

            <div class="p-3 bg-white/5 rounded-xl text-[11px] text-slate-300 leading-relaxed border border-white/10">
                <strong>Business Repayment Rule:</strong> The loan is <u>not due 30 days after disbursement</u>. Repayment is strictly due at the <strong>end of the calendar month</strong>. If unpaid by month-end, a 30% compound interest applies to the remaining balance for subsequent periods.
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('loans.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Cancel</a>
            <button type="button" @click="confirmLoanCreation('pending')" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                Save as Pending
            </button>
            <button type="button" @click="confirmLoanCreation('disburse')" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition">
                Approve & Disburse
            </button>
        </div>
        <input type="hidden" name="disburse_immediately" id="disburse_immediately" value="0">
    </form>
</div>
@endsection

@push('scripts')
<script>
function loanCalculator() {
    return {
        selectedCustomerId: '{{ old('customer_id', request('customer_id')) }}',
        selectedCustomer: { name: '', phone: '', status: '', outstanding: 0 },
        principal: 1000,
        rate: 0.30,
        interest: 300,
        totalPayable: 1300,
        loanDate: new Date().toISOString().split('T')[0],
        dueDateFormatted: '',

        init() {
            this.updateCustomerDetails();
            this.calculate();
            this.calculateDueDate();
        },

        updateCustomerDetails() {
            const select = document.querySelector('select[name="customer_id"]');
            if (select && select.selectedIndex > 0) {
                const opt = select.options[select.selectedIndex];
                this.selectedCustomer = {
                    name: opt.getAttribute('data-name') || '',
                    phone: opt.getAttribute('data-phone') || '',
                    status: opt.getAttribute('data-status') || '',
                    outstanding: parseFloat(opt.getAttribute('data-outstanding') || '0')
                };
            } else {
                this.selectedCustomer = { name: '', phone: '', status: '', outstanding: 0 };
            }
        },

        calculate() {
            const p = parseFloat(this.principal) || 0;
            this.interest = p * this.rate;
            this.totalPayable = p + this.interest;
        },

        calculateDueDate() {
            if (!this.loanDate) return;
            const d = new Date(this.loanDate);
            const endOfMonth = new Date(d.getFullYear(), d.getMonth() + 1, 0);
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            this.dueDateFormatted = endOfMonth.toLocaleDateString('en-GB', options);
        },

        formatMoney(amount) {
            return (parseFloat(amount) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        confirmLoanCreation(type) {
            if (!this.selectedCustomerId) {
                Swal.fire('Customer Required', 'Please select a customer first.', 'warning');
                return;
            }

            if (this.selectedCustomer.status === 'blacklisted') {
                Swal.fire('Prohibited Action', 'Cannot create a loan for a blacklisted customer.', 'error');
                return;
            }

            const isDisburse = type === 'disburse';
            document.getElementById('disburse_immediately').value = isDisburse ? '1' : '0';

            Swal.fire({
                title: isDisburse ? 'Approve & Disburse Loan?' : 'Save Loan Application?',
                html: `
                    <div class="text-left text-xs space-y-2">
                        <p><strong>Customer:</strong> ${this.selectedCustomer.name}</p>
                        <p><strong>Principal:</strong> GHS ${this.formatMoney(this.principal)}</p>
                        <p><strong>Initial Interest (30%):</strong> GHS ${this.formatMoney(this.interest)}</p>
                        <p><strong>Total Payable:</strong> GHS ${this.formatMoney(this.totalPayable)}</p>
                        <p><strong>Due Date:</strong> ${this.dueDateFormatted}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: isDisburse ? 'Approve & Disburse' : 'Save as Pending',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('loanForm').submit();
                }
            });
        }
    }
}
</script>
@endpush