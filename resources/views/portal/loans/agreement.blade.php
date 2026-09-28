@extends('layouts.portal')

@section('title', 'Loan Agreement ' . $loan->loan_number . ' — FinCore')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('portal.loans.show', $loan) }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Loan Details
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / Download Agreement
        </button>
    </div>

    {{-- Printable Agreement Document --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-8 print:border-0 print:shadow-none print:p-0">

        <div class="text-center border-b border-slate-200 pb-6 space-y-2">
            <div class="font-black text-2xl text-slate-900 tracking-tight">FINCORE LENDING SERVICES</div>
            <h2 class="text-lg font-bold text-blue-700 uppercase tracking-widest">Formal Lending Agreement & Terms</h2>
            <p class="text-xs text-slate-500 font-mono">Agreement Reference: {{ $loan->loan_number }}</p>
        </div>

        {{-- Borrower Details --}}
        <div class="grid grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-100 text-xs">
            <div>
                <h4 class="font-bold text-slate-400 uppercase tracking-wider mb-2">Lender</h4>
                <p class="font-bold text-slate-900">FinCore Financial Services Ltd.</p>
                <p class="text-slate-600">Accra, Ghana</p>
            </div>
            <div>
                <h4 class="font-bold text-slate-400 uppercase tracking-wider mb-2">Borrower (Customer)</h4>
                <p class="font-bold text-slate-900">{{ $customer->full_name }}</p>
                <p class="text-slate-600">Customer #: {{ $customer->customer_number }}</p>
                <p class="text-slate-600">Phone: {{ $customer->phone }}</p>
                <p class="text-slate-600">Address: {{ $customer->address ?? 'Accra, Ghana' }}</p>
            </div>
        </div>

        {{-- Financial Terms Table --}}
        <div class="space-y-3">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">1. Loan Principal & Interest Terms</h3>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <tbody class="divide-y divide-slate-200 text-slate-700">
                        <tr>
                            <td class="px-4 py-3 font-semibold bg-slate-50 w-1/2">Approved Principal Amount</td>
                            <td class="px-4 py-3 font-bold text-slate-900">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold bg-slate-50">Initial Interest Rate</td>
                            <td class="px-4 py-3 font-bold text-blue-600">{{ $loan->interest_rate }}% (GHS {{ number_format($loan->interest_amount, 2) }})</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold bg-slate-50">Initial Total Payable</td>
                            <td class="px-4 py-3 font-extrabold text-slate-900">GHS {{ number_format($loan->total_payable, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold bg-slate-50">Duration & Frequency</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $loan->duration }} Month(s) — {{ ucfirst($loan->repayment_frequency) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-semibold bg-slate-50">Maturity / Due Date</td>
                            <td class="px-4 py-3 font-bold text-amber-700">{{ $loan->maturity_date ? \Carbon\Carbon::parse($loan->maturity_date)->format('M d, Y') : 'Calendar Month End' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Terms & Conditions Clauses --}}
        <div class="space-y-3 text-xs text-slate-700 leading-relaxed">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">2. Interest Calculation & Compound Rules</h3>
            <p>
                A default initial interest rate of {{ $loan->interest_rate }}% applies upon disbursement. Repayments align with calendar month-end. In accordance with FinCore lending policies, remaining unpaid balances at calendar month-end incur a {{ $loan->interest_rate }}% monthly compound interest charge calculated on the closing balance.
            </p>

            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mt-4">3. Repayment Policy</h3>
            <p>
                The Borrower agrees to make repayments electronically via Paystack or approved company financial channels on or before the due date. Partial repayments reduce the outstanding principal balance immediately upon receipt.
            </p>

            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mt-4">4. Digital Acceptance</h3>
            <p>
                By submitting this loan application electronically via the FinCore User Portal, the Borrower acknowledges full understanding and legal acceptance of all loan conditions herein.
            </p>
        </div>

        {{-- Signature Block --}}
        <div class="border-t border-slate-200 pt-8 grid grid-cols-2 gap-8 text-xs text-center">
            <div>
                <div class="border-b border-slate-400 pb-2 mb-2 font-mono font-bold text-slate-900">{{ $customer->full_name }}</div>
                <span class="text-slate-500">Borrower Electronic Acceptance</span>
            </div>
            <div>
                <div class="border-b border-slate-400 pb-2 mb-2 font-mono font-bold text-blue-700">FinCore Authorized Officer</div>
                <span class="text-slate-500">Lender Digital Verification</span>
            </div>
        </div>

    </div>

</div>
@endsection
