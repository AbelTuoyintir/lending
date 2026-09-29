@extends('layouts.app')

@section('title', 'Customer Profile - ' . $customer->full_name . ' | FinCore')
@section('page-title', 'Customer Financial Profile')

@section('content')
<div class="space-y-6">

    {{-- Customer Profile Header --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customers.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Customers</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-md">{{ $customer->customer_number }}</span>
                @if($customer->status === 'active')
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] uppercase">Active</span>
                @elseif($customer->status === 'blacklisted')
                    <span class="px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 font-bold text-[10px] uppercase">Blacklisted</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px] uppercase">Inactive</span>
                @endif
            </div>

            <h1 class="text-2xl font-extrabold text-slate-900 mt-2">
                {{ $customer->full_name }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Phone: <span class="font-semibold text-slate-800">{{ $customer->phone }}</span> •
                Email: <span class="font-semibold text-slate-800">{{ $customer->email ?? 'N/A' }}</span> •
                Registered: <span class="font-semibold text-slate-700">{{ $customer->created_at->format('d M Y') }}</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                Edit Profile
            </a>

            @if($customer->status === 'active')
                <a href="{{ route('loans.create', ['customer_id' => $customer->id]) }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition">
                    + New Loan
                </a>
            @else
                <button type="button" disabled class="px-5 py-2.5 bg-slate-200 text-slate-400 text-xs font-semibold rounded-xl cursor-not-allowed">
                    + New Loan (Blacklisted/Inactive)
                </button>
            @endif
        </div>
    </div>

    {{-- Customer Financial Summary Cards Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Loans</p>
            <p class="text-xl font-extrabold text-slate-900 mt-1">{{ number_format($totalLoansCount) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Active Loans</p>
            <p class="text-xl font-extrabold text-blue-600 mt-1">{{ number_format($activeLoansCount) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Borrowed</p>
            <p class="text-lg font-extrabold text-slate-900 mt-1">GHS {{ number_format($totalBorrowed, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Repaid</p>
            <p class="text-lg font-extrabold text-emerald-600 mt-1">GHS {{ number_format($totalRepaid, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Interest</p>
            <p class="text-lg font-extrabold text-indigo-600 mt-1">GHS {{ number_format($totalInterest, 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <p class="text-[10px] font-bold uppercase text-slate-400">Current Outstanding</p>
            <p class="text-lg font-extrabold text-amber-600 mt-1">GHS {{ number_format($currentOutstanding, 2) }}</p>
        </div>
    </div>

    {{-- Information Details Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
        {{-- Personal --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Personal Details</h3>
            <div><span class="text-slate-400 block">Full Name</span><span class="font-semibold text-slate-900">{{ $customer->full_name }}</span></div>
            <div><span class="text-slate-400 block">Date of Birth</span><span class="font-semibold text-slate-900">{{ $customer->date_of_birth ? $customer->date_of_birth->format('d M Y') : '—' }}</span></div>
            <div><span class="text-slate-400 block">Gender</span><span class="font-semibold text-slate-900">{{ $customer->gender ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">National ID</span><span class="font-semibold text-slate-900">{{ $customer->id_type ?? 'ID' }}: {{ $customer->id_number ?? '—' }}</span></div>
        </div>

        {{-- Contact & Address --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Contact & Address</h3>
            <div><span class="text-slate-400 block">Primary Phone</span><span class="font-semibold text-slate-900">{{ $customer->phone }}</span></div>
            <div><span class="text-slate-400 block">Alternate Phone</span><span class="font-semibold text-slate-900">{{ $customer->alternate_phone ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">Residential Address</span><span class="font-semibold text-slate-900">{{ $customer->address ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">City / Region</span><span class="font-semibold text-slate-900">{{ $customer->city ?? '—' }} {{ $customer->region ? '('.$customer->region.')' : '' }}</span></div>
            <div><span class="text-slate-400 block">Digital Address</span><span class="font-semibold font-mono text-slate-900">{{ $customer->digital_address ?? '—' }}</span></div>
        </div>

        {{-- Employment --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Employment & Income</h3>
            <div><span class="text-slate-400 block">Occupation</span><span class="font-semibold text-slate-900">{{ $customer->occupation ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">Employer</span><span class="font-semibold text-slate-900">{{ $customer->employer ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">Employment Address</span><span class="font-semibold text-slate-900">{{ $customer->employment_address ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">Monthly Income</span><span class="font-bold text-emerald-600">{{ $customer->monthly_income ? 'GHS ' . number_format($customer->monthly_income, 2) : '—' }}</span></div>
        </div>

        {{-- Reference / Emergency --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Guarantor / Emergency</h3>
            <div><span class="text-slate-400 block">Contact Name</span><span class="font-semibold text-slate-900">{{ $customer->emergency_contact_name ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">Relationship</span><span class="font-semibold text-slate-900">{{ $customer->emergency_contact_relationship ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">Phone</span><span class="font-semibold text-slate-900">{{ $customer->emergency_contact_phone ?? '—' }}</span></div>
            <div><span class="text-slate-400 block">Address</span><span class="font-semibold text-slate-900">{{ $customer->emergency_contact_address ?? '—' }}</span></div>
        </div>
    </div>

    {{-- Loan History Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-900 text-base">Loan History</h3>
            @if($customer->status === 'active')
                <a href="{{ route('loans.create', ['customer_id' => $customer->id]) }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                    + New Loan
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Loan #</th>
                        <th class="px-4 py-3">Loan Date</th>
                        <th class="px-4 py-3 text-right">Principal</th>
                        <th class="px-4 py-3 text-right">Interest</th>
                        <th class="px-4 py-3 text-right">Total Payable</th>
                        <th class="px-4 py-3 text-right">Paid</th>
                        <th class="px-4 py-3 text-right">Balance</th>
                        <th class="px-4 py-3 text-right">Due Date</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customer->loans as $loan)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-mono font-bold text-blue-600">
                                <a href="{{ route('loans.show', $loan) }}">{{ $loan->loan_number }}</a>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700">{{ $loan->loan_date ? $loan->loan_date->format('d M Y') : '—' }}</td>
                            <td class="px-4 py-3.5 text-right font-semibold text-slate-900">GHS {{ number_format($loan->principal_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-right text-blue-600 font-semibold">GHS {{ number_format($loan->interest_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-semibold text-slate-900">GHS {{ number_format($loan->total_payable, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-emerald-600">GHS {{ number_format($loan->amount_paid, 2) }}</td>
                            <td class="px-4 py-3.5 text-right font-bold text-amber-600">GHS {{ number_format($loan->outstanding_balance, 2) }}</td>
                            <td class="px-4 py-3.5 text-right text-slate-600">{{ $loan->maturity_date ? $loan->maturity_date->format('d M Y') : 'Month-End' }}</td>
                            <td class="px-4 py-3.5 text-center"><x-status-badge :status="$loan->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400">No loan records found for this customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payment History Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">Payment Collections History</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Payment #</th>
                        <th class="px-4 py-3">Loan #</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Recorded By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customer->payments as $payment)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-mono font-bold text-slate-700">
                                <a href="{{ route('payments.show', $payment) }}">{{ $payment->payment_number }}</a>
                            </td>
                            <td class="px-4 py-3.5 font-mono font-bold text-blue-600">
                                <a href="{{ route('loans.show', $payment->loan) }}">{{ $payment->loan->loan_number ?? 'N/A' }}</a>
                            </td>
                            <td class="px-4 py-3.5 text-right font-bold text-emerald-600">+ GHS {{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 uppercase text-[10px] font-semibold text-slate-500">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                            <td class="px-4 py-3.5 text-slate-600">Administrator</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">No payment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Customer Activity Log --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">Customer Activity Timeline</h3>

        <div class="space-y-3 text-xs">
            <div class="flex items-start gap-3">
                <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 shrink-0"></div>
                <div>
                    <p class="font-semibold text-slate-900">Customer Registered</p>
                    <p class="text-slate-400 text-[11px]">{{ $customer->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
            @foreach($customer->loans as $loan)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-600 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-semibold text-slate-900">Loan Issued: {{ $loan->loan_number }} (GHS {{ number_format($loan->principal_amount, 2) }})</p>
                        <p class="text-slate-400 text-[11px]">{{ $loan->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            @endforeach
            @foreach($customer->payments as $payment)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-600 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-semibold text-slate-900">Repayment Received: GHS {{ number_format($payment->amount, 2) }} ({{ $payment->payment_number }})</p>
                        <p class="text-slate-400 text-[11px]">{{ $payment->payment_date->format('d M Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection