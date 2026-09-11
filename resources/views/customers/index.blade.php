@extends('layouts.app')

@section('title', 'Customers | FinCore')

@section('page-title', 'Customers')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold">
                Customers
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Manage borrowers and customer profiles.
            </p>
        </div>

        <a
            href="{{ route('customers.create') }}"
            class="
                inline-flex items-center justify-center gap-2
                px-4 py-2.5
                bg-blue-600
                text-white
                rounded-xl
                text-sm font-semibold
                shadow-lg shadow-blue-600/20
            "
        >
            <span class="text-lg">+</span>
            Add Customer
        </a>

    </div>


    {{-- Search --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4">

        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                <div class="md:col-span-2">

                    <div class="relative">

                        <svg
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search name, phone or customer number..."
                            class="
                                w-full
                                pl-10 pr-4 py-2.5
                                rounded-xl
                                border border-slate-200
                                text-sm
                                focus:ring-2 focus:ring-blue-500/20
                                focus:border-blue-500
                                outline-none
                            "
                        >

                    </div>

                </div>


                <select
                    name="status"
                    class="
                        px-3 py-2.5
                        rounded-xl
                        border border-slate-200
                        text-sm
                        bg-white
                        outline-none
                    "
                >

                    <option value="">
                        All Statuses
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Inactive
                    </option>

                    <option
                        value="blacklisted"
                        @selected(request('status') === 'blacklisted')
                    >
                        Blacklisted
                    </option>

                </select>


                <button
                    class="
                        px-4 py-2.5
                        rounded-xl
                        bg-slate-900
                        text-white
                        text-sm font-medium
                        hover:bg-slate-800
                    "
                >
                    Search
                </button>

            </div>

        </form>

    </div>


    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50 border-b border-slate-200">

                <tr class="text-xs uppercase tracking-wide text-slate-400">

                    <th class="px-6 py-4 text-left font-semibold">
                        Customer
                    </th>

                    <th class="px-6 py-4 text-left font-semibold">
                        Contact
                    </th>

                    <th class="px-6 py-4 text-left font-semibold">
                        Occupation
                    </th>

                    <th class="px-6 py-4 text-left font-semibold">
                        Loans
                    </th>

                    <th class="px-6 py-4 text-left font-semibold">
                        Status
                    </th>

                    <th class="px-6 py-4 text-right font-semibold">
                        Action
                    </th>

                </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                @forelse($customers as $customer)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <div
                                    class="
                                        w-10 h-10
                                        rounded-full
                                        bg-blue-50
                                        text-blue-600
                                        flex items-center justify-center
                                        font-semibold
                                    "
                                >
                                    {{ strtoupper(substr($customer->first_name, 0, 1)) }}
                                </div>

                                <div>

                                    <p class="font-semibold text-sm">
                                        {{ $customer->full_name }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        {{ $customer->customer_number }}
                                    </p>

                                </div>

                            </div>

                        </td>

                        <td class="px-6 py-4">

                            <p class="text-sm">
                                {{ $customer->phone }}
                            </p>

                            <p class="text-xs text-slate-400">
                                {{ $customer->email ?? 'No email' }}
                            </p>

                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $customer->occupation ?? '—' }}
                        </td>

                        <td class="px-6 py-4">

                            <span
                                class="
                                    inline-flex
                                    px-2.5 py-1
                                    rounded-lg
                                    bg-slate-100
                                    text-slate-600
                                    text-xs font-medium
                                "
                            >
                                {{ $customer->loans_count ?? $customer->loans->count() }}
                            </span>

                        </td>

                        <td class="px-6 py-4">

                            @if($customer->status === 'active')

                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                                    Active
                                </span>

                            @elseif($customer->status === 'blacklisted')

                                <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">
                                    Blacklisted
                                </span>

                            @else

                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-right">

                            <a
                                href="{{ route('customers.show', $customer) }}"
                                class="
                                    inline-flex
                                    px-3 py-2
                                    rounded-lg
                                    text-blue-600
                                    hover:bg-blue-50
                                    text-sm font-medium
                                "
                            >
                                View
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="px-6 py-16 text-center"
                        >

                            <div class="text-slate-300 text-4xl mb-3">
                                —
                            </div>

                            <p class="font-medium text-slate-700">
                                No customers found
                            </p>

                            <p class="text-sm text-slate-400 mt-1">
                                Add your first customer to get started.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div class="px-6 py-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>

    </div>

</div>

@endsection