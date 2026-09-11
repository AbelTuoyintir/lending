@extends('layouts.app')

@section('title', 'Add Customer | FinCore')

@section('page-title', 'Add Customer')

@section('content')

<div class="max-w-5xl mx-auto">

    <form
        method="POST"
        action="{{ route('customers.store') }}"
        class="space-y-6"
    >

        @csrf

        {{-- Header --}}
        <div>

            <a
                href="{{ route('customers.index') }}"
                class="text-sm text-blue-600"
            >
                ← Back to customers
            </a>

            <h1 class="text-2xl font-bold mt-3">
                Add New Customer
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Create a borrower profile before issuing a loan.
            </p>

        </div>


        {{-- Personal Information --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <div class="mb-6">

                <h2 class="font-semibold">
                    Personal Information
                </h2>

                <p class="text-xs text-slate-400 mt-1">
                    Basic customer identification details.
                </p>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <label class="block text-sm font-medium mb-2">
                        First Name *
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        class="fintech-input"
                        required
                    >
                </div>


                <div>
                    <label class="block text-sm font-medium mb-2">
                        Middle Name
                    </label>

                    <input
                        type="text"
                        name="middle_name"
                        value="{{ old('middle_name') }}"
                        class="fintech-input"
                    >
                </div>


                <div>
                    <label class="block text-sm font-medium mb-2">
                        Last Name *
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        class="fintech-input"
                        required
                    >
                </div>


                <div>
                    <label class="block text-sm font-medium mb-2">
                        Phone *
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="fintech-input"
                        required
                    >
                </div>


                <div>
                    <label class="block text-sm font-medium mb-2">
                        Alternate Phone
                    </label>

                    <input
                        type="text"
                        name="alternate_phone"
                        value="{{ old('alternate_phone') }}"
                        class="fintech-input"
                    >
                </div>


                <div>
                    <label class="block text-sm font-medium mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="fintech-input"
                    >
                </div>

            </div>

        </div>


        {{-- Employment --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <h2 class="font-semibold">
                Employment & Income
            </h2>

            <p class="text-xs text-slate-400 mt-1 mb-6">
                Financial background of the borrower.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Occupation
                    </label>

                    <input
                        type="text"
                        name="occupation"
                        value="{{ old('occupation') }}"
                        class="fintech-input"
                    >
                </div>


                <div>
                    <label class="block text-sm font-medium mb-2">
                        Employer
                    </label>

                    <input
                        type="text"
                        name="employer"
                        value="{{ old('employer') }}"
                        class="fintech-input"
                    >
                </div>


                <div>
                    <label class="block text-sm font-medium mb-2">
                        Monthly Income
                    </label>

                    <div class="relative">

                        <span
                            class="
                                absolute left-3 top-1/2
                                -translate-y-1/2
                                text-sm text-slate-400
                            "
                        >
                            GHS
                        </span>

                        <input
                            type="number"
                            step="0.01"
                            name="monthly_income"
                            value="{{ old('monthly_income') }}"
                            class="fintech-input pl-14"
                        >

                    </div>

                </div>


                <div class="md:col-span-3">

                    <label class="block text-sm font-medium mb-2">
                        Address
                    </label>

                    <textarea
                        name="address"
                        rows="3"
                        class="fintech-input"
                    >{{ old('address') }}</textarea>

                </div>

            </div>

        </div>


        {{-- Notes --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <label class="block text-sm font-medium mb-2">
                Notes
            </label>

            <textarea
                name="notes"
                rows="4"
                class="fintech-input"
                placeholder="Additional information about the customer..."
            >{{ old('notes') }}</textarea>

        </div>


        {{-- Actions --}}
        <div class="flex justify-end gap-3">

            <a
                href="{{ route('customers.index') }}"
                class="
                    px-5 py-2.5
                    rounded-xl
                    border border-slate-200
                    bg-white
                    text-sm font-medium
                "
            >
                Cancel
            </a>

            <button
                type="submit"
                class="
                    px-5 py-2.5
                    rounded-xl
                    bg-blue-600
                    text-white
                    text-sm font-semibold
                    shadow-lg shadow-blue-600/20
                "
            >
                Create Customer
            </button>

        </div>

    </form>

</div>

@endsection