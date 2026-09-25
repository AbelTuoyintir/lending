@extends('layouts.app')

@section('title', 'Server Error | FinCore')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-3xl shadow-sm border border-red-100">
            500
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Internal Server Error</h1>
            <p class="text-xs text-slate-500 mt-2">An internal server error occurred while processing your financial request. Please try again or contact system support.</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md hover:bg-blue-700 transition">
                Return to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
