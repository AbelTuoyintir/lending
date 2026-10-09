@extends('layouts.portal')

@section('title', 'My Profile Settings — De ferg money Portal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profile & Account Settings</h1>
        <p class="text-xs text-slate-500">Update your contact details, employment info, and account security password.</p>
    </div>

    {{-- Administrator Approval Notice Banner --}}
    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-amber-800 text-xs flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <span class="font-bold block text-amber-900">Important Identity & Verification Notice</span>
            Contact and employment details can be updated below. Note that updates to sensitive financial details or identity verification records will require administrator approval before final verification on your active lending account.
        </div>
    </div>

    {{-- Profile Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 to-indigo-600 text-white flex items-center justify-center font-black text-xl shadow-md shrink-0">
                {{ strtoupper(substr($customer->first_name ?? $user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">{{ $customer->full_name ?? $user->name }}</h3>
                <p class="text-xs text-slate-500">Customer Number: {{ $customer->customer_number ?? 'CUS-PORTAL' }}</p>
            </div>
        </div>

        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Personal & Contact Details</h3>

        <form action="{{ route('portal.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Profile Photo / Identification Image</label>
                <input type="file" name="profile_photo" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1.5">
                <p class="text-[11px] text-slate-400 mt-1">Uploaded profile photo / identity document updates will be submitted for administrator verification.</p>
            </div>

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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $customer->occupation ?? '') }}"
                           class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Employer / Company</label>
                    <input type="text" name="employer" value="{{ old('employer', $customer->employer ?? '') }}" placeholder="e.g. Acme Corp Ltd"
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
