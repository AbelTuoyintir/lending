@extends('layouts.app')

@section('title', 'Page Not Found | FinCore')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-3xl">
            404
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Page Not Found</h1>
            <p class="text-xs text-slate-500 mt-2">The requested administrative resource could not be found or has been moved.</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md hover:bg-blue-700 transition">
                Return to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection