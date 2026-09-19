<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FinCore — Lending Management System')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 antialiased font-sans" x-data="{ sidebarOpen: false, searchOpen: false }">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- Desktop & Mobile Sidebar --}}
    @include('components.sidebar')

    {{-- Main Area --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

        {{-- Topbar --}}
        @include('components.topbar')

        {{-- Main Container --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center justify-between text-emerald-800 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl bg-red-50 border border-red-200 p-4 flex items-center justify-between text-red-800 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 text-sm font-medium space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Please fix the following validation errors:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700 pl-7">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

        {{-- Footer --}}
        <footer class="border-t border-slate-200 bg-white py-4 px-6 text-xs text-slate-400 text-center flex flex-col sm:flex-row justify-between items-center gap-2">
            <span>&copy; {{ date('Y') }} FinCore Administrative Lending Management System. All rights reserved.</span>
            <span class="font-mono text-[10px]">Strictly Confidential & Confidential Internal Access Only</span>
        </footer>

    </div>

</div>

{{-- Global Quick Search Modal --}}
<div x-show="searchOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.window.escape="searchOpen = false">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="searchOpen = false"></div>
    <div class="relative min-h-screen flex items-start justify-center p-4 pt-16 sm:pt-24">
        <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200" @click.stop>
            <form action="{{ route('search') }}" method="GET" class="flex items-center border-b border-slate-200 px-4 py-3">
                <svg class="w-5 h-5 text-slate-400 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" name="q" placeholder="Search customers, loan numbers, payment numbers..." class="w-full text-sm bg-transparent border-0 focus:outline-none focus:ring-0 text-slate-900 placeholder-slate-400" autofocus>
                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700">Search</button>
            </form>
            <div class="p-4 text-xs text-slate-500 bg-slate-50 flex items-center justify-between">
                <span>Press <kbd class="px-1.5 py-0.5 bg-white border border-slate-200 rounded font-mono">ESC</kbd> to close</span>
                <span>Search customers by name/phone or loans by loan number</span>
            </div>
        </div>
    </div>
</div>

@stack('scripts')

</body>
</html>