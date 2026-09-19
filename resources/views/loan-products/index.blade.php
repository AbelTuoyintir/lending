@extends('layouts.app')

@section('title', 'Loan Products | FinCore')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Lending Products</h1>
            <p class="text-sm text-slate-500 mt-1">Configure interest rates, amounts, and duration rules for Lendings.</p>
        </div>
        <a href="{{ route('loan-products.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold shadow-lg shadow-blue-600/20">
            + New Lending Product
        </a>
    </div>

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

    {{-- Filter --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product name or code..." class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">Filter</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Product</th>
                        <th class="px-6 py-4 text-left font-semibold">Code</th>
                        <th class="px-6 py-4 text-left font-semibold">Interest</th>
                        <th class="px-6 py-4 text-left font-semibold">Amount Range</th>
                        <th class="px-6 py-4 text-left font-semibold">Duration Range</th>
                        <th class="px-6 py-4 text-left font-semibold">Status</th>
                        <th class="px-6 py-4 text-right font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loanProducts as $product)
                        <tr class="hover:bg-slate-50 transition text-sm">
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $product->code }}</td>
                            <td class="px-6 py-4">
                                {{ $product->interest_rate }}%
                                <span class="text-xs text-slate-400 font-normal capitalize">({{ $product->interest_type }})</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                GHS {{ number_format($product->min_amount, 2) }} – {{ number_format($product->max_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $product->min_duration }} – {{ $product->max_duration }} months
                            </td>
                            <td class="px-6 py-4">
                                @if($product->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">Disabled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('loan-products.show', $product) }}" class="text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">No Lending products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $loanProducts->links() }}
        </div>
    </div>
</div>
@endsection
