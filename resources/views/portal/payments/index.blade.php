@extends('layouts.portal')

@section('title', 'Payments & Receipts — FinCore Portal')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Payments & Receipts</h1>
            <p class="text-xs text-slate-500">History of all repayments made towards your active and past loan facilities.</p>
        </div>
    </div>

    {{-- Payments Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($payments->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">No Payments Recorded</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">When you pay off your loans via Paystack or direct deposit, your transaction receipts will appear here.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Receipt #</th>
                            <th class="px-5 py-3.5">Loan Number</th>
                            <th class="px-5 py-3.5">Payment Date</th>
                            <th class="px-5 py-3.5">Payment Method</th>
                            <th class="px-5 py-3.5">Reference</th>
                            <th class="px-5 py-3.5 text-right">Amount Paid</th>
                            <th class="px-5 py-3.5 text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    {{ $payment->payment_number }}
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-700">
                                    <a href="{{ route('portal.loans.show', $payment->loan) }}" class="hover:text-blue-600 hover:underline">
                                        {{ $payment->loan->loan_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : '—' }}
                                </td>
                                <td class="px-5 py-4 uppercase font-semibold text-slate-700">
                                    {{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-500">
                                    {{ $payment->reference }}
                                </td>
                                <td class="px-5 py-4 text-right font-black text-emerald-600 text-sm">
                                    GHS {{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('portal.payments.receipt', $payment) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 font-bold rounded-lg hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Receipt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
