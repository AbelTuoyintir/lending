@extends('layouts.app')

@section('title', 'Administrator Profile | FinCore')
@section('page-title', 'My Profile')

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-blue-600/20">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ auth()->user()->name }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email }} • System Administrator</p>
                <p class="text-[11px] text-slate-400 mt-1">Account Created: {{ auth()->user()->created_at?->format('d M Y') ?? 'N/A' }}</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold ring-1 ring-inset ring-emerald-600/20">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Active Session
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Profile Information --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="font-bold text-slate-900 text-base">Profile Information</h2>
                <p class="text-xs text-slate-500">Update your account name and email address.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/20 transition">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Password Update --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="font-bold text-slate-900 text-base">Change Password</h2>
                <p class="text-xs text-slate-500">Ensure your administrative account uses a strong password.</p>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Current Password</label>
                    <input type="password" name="current_password" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">New Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection