@extends('layouts.app')

@section('title', 'System Settings | FinCore')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <x-page-header
        title="System Settings"
        subtitle="Configure business profile, default loan interest rates, currency display, and system parameters."
    />

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
        @csrf

        {{-- Business Information --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-slate-900 border-b border-slate-100 pb-3">
                1. General Business Profile
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Business Name</label>
                    <input type="text" name="business_name" value="FinCore Financial Services" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Business Phone</label>
                    <input type="text" name="business_phone" value="+233 24 000 0000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Business Email</label>
                    <input type="email" name="business_email" value="contact@fincore.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Default Currency</label>
                    <input type="text" name="currency" value="GHS (Ghana Cedi)" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold bg-slate-50 text-slate-700">
                </div>
            </div>
        </div>

        {{-- Loan & Interest Calculation Rules --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-slate-900 border-b border-slate-100 pb-3">
                2. Default Loan & Interest Logic
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Default Initial Interest Rate (%)</label>
                    <div class="relative">
                        <input type="number" name="default_interest_rate" value="30" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold bg-slate-50 text-blue-600">
                        <span class="absolute right-3.5 top-2.5 text-sm font-bold text-slate-400">%</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Applied as Principal × 30% on loan creation</p>
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">Month-End Repayment Rule</label>
                    <input type="text" value="Calendar Month End (e.g. 30th / 31st)" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold bg-slate-50 text-slate-700">
                    <p class="text-[11px] text-slate-400 mt-1">Due strictly on last day of current calendar month</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm shadow-md hover:bg-blue-700 transition">
                Save System Settings
            </button>
        </div>
    </form>

</div>

@endsection