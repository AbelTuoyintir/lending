@extends('layouts.portal')

@section('title', 'My Profile Settings — FinCore Portal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profile & Account Settings</h1>
        <p class="text-xs text-slate-500">Update your contact details, employment info, and account security password.</p>
    </div>

    {{-- Profile Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Personal & Contact Details</h3>

        <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name ?? '') }}" required
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name ?? '') }}" required
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" required
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $customer->occupation ?? '') }}"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Monthly Income (GHS)</label>
                    <input type="number" step="0.01" name="monthly_income" value="{{ old('monthly_income', $customer->monthly_income ?? '') }}"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Residential Address</label>
                <input type="text" name="address" value="{{ old('address', $customer->address ?? '') }}"
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-xs">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Password Change Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Security & Password</h3>

        <form action="{{ route('portal.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Current Password *</label>
                <input type="password" name="current_password" required
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">New Password *</label>
                    <input type="password" name="password" required placeholder="Minimum 8 characters"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-xs">
                    Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
