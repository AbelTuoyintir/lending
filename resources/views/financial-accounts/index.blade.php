@extends('layouts.app')

@section('title', 'Financial Accounts | FinCore')
@section('page-title', 'Financial Accounts')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Financial Accounts</h1>
            <p class="text-sm text-slate-500 mt-1">Manage the lender's cash, bank, and mobile-money accounts.</p>
        </div>
        <a href="{{ route('financial-accounts.create') }}" class="shrink-0 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md transition">Add Account</a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Account</th>
                        <th class="px-5 py-3.5">Number</th>
                        <th class="px-5 py-3.5">Type</th>
                        <th class="px-5 py-3.5 text-right">Balance</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($financialAccounts as $account)
                        <tr>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $account->name }}</td>
                            <td class="px-5 py-4 font-mono text-slate-600">{{ $account->account_number }}</td>
                            <td class="px-5 py-4 capitalize text-slate-600">{{ str_replace('_', ' ', $account->type) }}</td>
                            <td class="px-5 py-4 text-right font-bold text-slate-900">GHS {{ number_format($account->current_balance, 2) }}</td>
                            <td class="px-5 py-4"><span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $account->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $account->is_active ? 'Active' : 'Inactive' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No financial accounts created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-slate-100">{{ $financialAccounts->links() }}</div>
    </div>
</div>
@endsection
