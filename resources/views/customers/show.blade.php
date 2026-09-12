@extends('layouts.app')

@section('title', 'Customer Details | FinCore')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 text-red-800 border border-red-200 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customers.index') }}" class="text-slate-400 hover:text-slate-600 text-sm">
                    &larr; Back to Customers
                </a>
            </div>
            <h1 class="text-2xl font-bold mt-2">
                {{ $customer->full_name }}
            </h1>
            <p class="text-sm text-slate-500">
                Customer Number: <span class="font-mono text-slate-700 font-semibold">{{ $customer->customer_number }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">
                Edit Profile
            </a>
            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-50 text-red-600 text-sm font-medium hover:bg-red-100">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Contact Information</h3>
            <div>
                <p class="text-xs text-slate-400">Phone</p>
                <p class="text-sm font-semibold text-slate-800">{{ $customer->phone }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Alternate Phone</p>
                <p class="text-sm text-slate-800">{{ $customer->alternate_phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Email</p>
                <p class="text-sm text-slate-800">{{ $customer->email ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Address</p>
                <p class="text-sm text-slate-800">{{ $customer->address ?? '—' }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Employment Details</h3>
            <div>
                <p class="text-xs text-slate-400">Occupation</p>
                <p class="text-sm font-semibold text-slate-800">{{ $customer->occupation ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Employer</p>
                <p class="text-sm text-slate-800">{{ $customer->employer ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Monthly Income</p>
                <p class="text-sm text-slate-800 font-medium">
                    {{ $customer->monthly_income ? 'GHS ' . number_format($customer->monthly_income, 2) : '—' }}
                </p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Status & Notes</h3>
            <div>
                <p class="text-xs text-slate-400 mb-1">Account Status</p>
                @if($customer->status === 'active')
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">Active</span>
                @elseif($customer->status === 'blacklisted')
                    <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">Blacklisted</span>
                @else
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">Inactive</span>
                @endif
            </div>
            <div>
                <p class="text-xs text-slate-400">Notes</p>
                <p class="text-sm text-slate-600 italic">{{ $customer->notes ?? 'No additional notes.' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Loans History</h2>
            <a href="{{ route('loans.create', ['customer_id' => $customer->id]) }}" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                + New Loan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Loan Number</th>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Principal</th>
                        <th class="px-4 py-3 text-left">Outstanding</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($customer->loans as $loan)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-blue-600">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-3">{{ $loan->loanProduct->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-medium">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-4 py-3 font-medium text-slate-900">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $loan->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('loans.show', $loan) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View Loan</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">No loans found for this customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
