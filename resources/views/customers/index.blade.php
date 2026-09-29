@extends('layouts.app')

@section('title', 'Customers | FinCore')
@section('page-title', 'Borrowers & Customers Directory')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Customers Directory
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Manage administrative client profiles, borrower records, and credit eligibility.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('customers.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>

            <a href="{{ route('customers.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition">
                <span>+</span> Add Customer
            </a>
        </div>
    </div>

    {{-- Search & Filters Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, email, or customer number..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">All Statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                        <option value="blacklisted" @selected(request('status') === 'blacklisted')>Blacklisted</option>
                    </select>
                </div>

                <div>
                    <select name="sort" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="latest" @selected(request('sort') === 'latest')>Newest First</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                        <option value="name_asc" @selected(request('sort') === 'name_asc')>Name (A-Z)</option>
                        <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z-A)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition">
                        Filter Results
                    </button>
                    @if(request()->hasAny(['search', 'status', 'sort', 'date_from', 'date_to']))
                        <a href="{{ route('customers.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Customer Number</th>
                        <th class="px-5 py-3.5">Customer Name</th>
                        <th class="px-5 py-3.5">Phone / Email</th>
                        <th class="px-5 py-3.5">Occupation</th>
                        <th class="px-5 py-3.5 text-right">Monthly Income</th>
                        <th class="px-5 py-3.5 text-center">Loans</th>
                        <th class="px-5 py-3.5 text-right">Outstanding</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Registered</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-blue-600">
                                <a href="{{ route('customers.show', $customer) }}">{{ $customer->customer_number }}</a>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                <a href="{{ route('customers.show', $customer) }}" class="hover:text-blue-600">
                                    {{ $customer->full_name }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $customer->phone }}</div>
                                <div class="text-[11px] text-slate-400">{{ $customer->email ?? 'No email' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $customer->occupation ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold text-slate-800">
                                {{ $customer->monthly_income ? 'GHS ' . number_format($customer->monthly_income, 2) : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 font-bold text-slate-700">
                                    {{ $customer->loans_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-amber-600">
                                GHS {{ number_format($customer->outstanding_balance ?? 0, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($customer->status === 'active')
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] uppercase">Active</span>
                                @elseif($customer->status === 'blacklisted')
                                    <span class="px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 font-bold text-[10px] uppercase">Blacklisted</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px] uppercase">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right text-slate-500 font-medium">
                                {{ $customer->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('customers.show', $customer) }}" class="px-2 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold rounded-lg transition">View</a>
                                <a href="{{ route('customers.edit', $customer) }}" class="px-2 py-1 bg-slate-100 text-slate-700 hover:bg-slate-200 font-semibold rounded-lg transition">Edit</a>
                                @if($customer->status === 'active')
                                    <a href="{{ route('loans.create', ['customer_id' => $customer->id]) }}" class="px-2 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold rounded-lg transition">+ Loan</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center text-slate-400">
                                <x-empty-state title="No Customers Found" message="There are currently no borrower records matching your search query." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>

@endsection