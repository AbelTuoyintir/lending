@extends('layouts.app')

@section('title', 'Page Expired | FinCore')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-3xl shadow-sm border border-slate-200">
            419
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Session Expired</h1>
            <p class="text-xs text-slate-500 mt-2">Your administrative session has timed out due to inactivity. Please refresh and try again.</p>
        </div>
        <div>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md hover:bg-blue-700 transition">
                Log In Again
            </a>
        </div>
    </div>
</div>
@endsection
