@extends('layouts.portal')

@section('title', 'My Loans — FinCore Lending Portal')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">My Lending Facilities & Loans</h1>
            <p class="text-xs text-slate-500">View loan details, active balances, repayment schedules, or apply for a new loan facility.</p>
        </div>
        <div>
            <a href="{{ route('portal.loans.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Apply for New Loan
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex border-b border-slate-200 gap-6 text-xs font-bold">
        <a href="{{ route('portal.loans.index', ['tab' => 'active']) }}" class="pb-3 border-b-2 transition {{ ($tab ?? 'active') === 'active' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            Active Loans
        </a>
        <a href="{{ route('portal.loans.index', ['tab' => 'completed']) }}" class="pb-3 border-b-2 transition {{ ($tab ?? '') === 'completed' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            Completed Loans
        </a>
    </div>

    {{-- Loans List Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($loans->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">No Loans Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">You have no {{ $tab ?? 'active' }} loan facilities under this section.</p>
                <a href="{{ route('portal.loans.create') }}" class="inline-block bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm">Apply for Loan</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Loan Number</th>
                            <th class="px-5 py-3.5">Product</th>
                            <th class="px-5 py-3.5 text-right">Principal</th>
                            <th class="px-5 py-3.5 text-right">Total Payable</th>
                            <th class="px-5 py-3.5 text-right">Paid</th>
                            <th class="px-5 py-3.5 text-right">Balance</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($loans as $loan)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    <a href="{{ route('portal.loans.show', $loan) }}" class="hover:text-blue-600 hover:underline">
                                        {{ $loan->loan_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-700">
                                    {{ $loan->loanProduct->name ?? 'Standard Loan' }}
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                    GHS {{ number_format($loan->principal_amount, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                    GHS {{ number_format($loan->total_payable, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right font-bold text-emerald-600">
                                    GHS {{ number_format($loan->amount_paid, 2) }}
                                </td>
                                <td class="px-5 py-4 text-right font-extrabold text-amber-600">
                                    GHS {{ number_format($loan->outstanding_balance, 2) }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $badgeClasses = match($loan->status) {
                                            'active', 'disbursed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'partially_paid' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'fully_paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            'defaulted', 'overdue' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border {{ $badgeClasses }}">
                                        {{ str_replace('_', ' ', $loan->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right space-x-2">
                                    <a href="{{ route('portal.loans.show', $loan) }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition">
                                        View Loan
                                    </a>
                                    @if($loan->outstanding_balance > 0 && in_array($loan->status, ['active', 'disbursed', 'partially_paid', 'overdue', 'defaulted']))
                                        <a href="{{ route('portal.loans.show', $loan) }}#pay" class="inline-block px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs shadow-sm transition">
                                            Pay via Paystack
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $loans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
