@extends('layouts.app')

@section('title', 'Add Administrator | FinCore')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('administrators.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
            &larr; Back to administrators
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-2">
            Create Administrator Account
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Grant administrative access to internal team members.
        </p>
    </div>

    <form method="POST" action="{{ route('administrators.store') }}" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                Full Name *
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Sarah Mensah"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
        </div>

        <div>
            <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                Email Address *
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@fincore.com"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Password *
                </label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wider text-slate-500 mb-2">
                    Confirm Password *
                </label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('administrators.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm shadow-md shadow-blue-600/20 hover:bg-blue-700 transition">
                Create Account
            </button>
        </div>
    </form>

</div>

@endsection