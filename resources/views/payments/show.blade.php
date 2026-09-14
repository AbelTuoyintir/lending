@extends('layouts.app')

@section('title', 'Payment Receipt ' . $payment->payment_number . ' | FinCore')
@section('page-title', 'Payment Receipt')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('payments.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Payments</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Official Collection Receipt</h1>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md transition">
                🖨️ Print Receipt
            </button>
        </div>
    </div>

    {{-- Printable Receipt Card --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xl space-y-6">
        <div class="flex items-start justify-between border-b border-slate-200 pb-6">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg mb-2">F</div>
                <h2 class="text-xl font-extrabold text-slate-900">FinCore</h2>
                <p class="text-xs text-slate-500">Administrative Loan Management System</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-full uppercase tracking-wider mb-2">COMPLETED PAYMENT</span>
                <p class="text-xs text-slate-400">Receipt #: <span class="font-mono font-bold text-slate-900">{{ $payment->payment_number }}</span></p>
                <p class="text-xs text-slate-400">Date: <span class="font-semibold text-slate-700">{{ $payment->payment_date->format('d F Y') }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 text-xs border-b border-slate-100 pb-6">
            <div>
                <p class="text-slate-400 uppercase font-bold text-[10px]">Received From (Customer)</p>
                <p class="text-sm font-bold text-slate-900 mt-1">{{ $payment->customer->full_name }}</p>
                <p class="text-slate-500 mt-0.5">Customer #: {{ $payment->customer->customer_number }}</p>
                <p class="text-slate-500">Phone: {{ $payment->customer->phone }}</p>
            </div>
            <div class="text-right">
                <p class="text-slate-400 uppercase font-bold text-[10px]">Applied Loan Account</p>
                <p class="text-sm font-bold text-blue-600 font-mono mt-1">{{ $payment->loan->loan_number }}</p>
                <p class="text-slate-500 mt-0.5">Method: <span class="uppercase font-semibold text-slate-700">{{ str_replace('_', ' ', $payment->payment_method) }}</span></p>
                <p class="text-slate-500">Ref: <span class="font-mono font-semibold text-slate-700">{{ $payment->reference }}</span></p>
            </div>
        </div>

        <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200 space-y-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Amount Received</p>
            <h3 class="text-3xl font-extrabold text-emerald-600">GHS {{ number_format($payment->amount, 2) }}</h3>
            <p class="text-xs text-slate-500">Remaining Loan Balance: <span class="font-bold text-slate-900">GHS {{ number_format($payment->loan->outstanding_balance, 2) }}</span></p>
        </div>

        <div class="flex items-center justify-between text-xs text-slate-400 border-t border-slate-100 pt-4">
            <span>Recorded By: System Administrator</span>
            <span>Thank you for your prompt repayment.</span>
        </div>
    </div>

</div>
@endsection