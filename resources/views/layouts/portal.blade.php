<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FinCore — Customer Lending Portal')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 antialiased font-sans min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

    {{-- Top Header / Navigation Bar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Brand / Logo --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                            F
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-slate-900 text-lg leading-none tracking-tight">FinCore</span>
                            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest leading-none mt-0.5">Customer Portal</span>
                        </div>
                    </a>
                </div>

                {{-- Desktop Navigation Links --}}
                <nav class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('portal.dashboard') }}"
                       class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('portal.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('portal.loans.index') }}"
                       class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('portal.loans.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        My Loans
                    </a>
                    <a href="{{ route('portal.loans.create') }}"
                       class="px-3 py-2 rounded-lg text-xs font-semibold transition bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200/60">
                        + Apply for Loan
                    </a>
                    <a href="{{ route('portal.payments.index') }}"
                       class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('portal.payments.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Payments & Receipts
                    </a>
                    <a href="{{ route('portal.financial-history') }}"
                       class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('portal.financial-history') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Financial History
                    </a>
                    <a href="{{ route('portal.notifications') }}"
                       class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('portal.notifications') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Notifications
                    </a>
                    <a href="{{ route('portal.support') }}"
                       class="px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('portal.support') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Help & Support
                    </a>
                </nav>

                {{-- User Profile & Actions --}}
                <div class="hidden lg:flex items-center gap-3">
                    <a href="{{ route('portal.profile') }}" class="flex items-center gap-2 text-xs font-semibold text-slate-700 hover:text-blue-600 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 transition">
                        <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-[10px]">
                            {{ strtoupper(substr(Auth::user()->name ?? 'C', 0, 1)) }}
                        </div>
                        <span>{{ Auth::user()->name }}</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-red-600 px-2 py-1.5 transition">
                            Log out
                        </button>
                    </form>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex lg:hidden items-center">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>

            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-2">
            <a href="{{ route('portal.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Dashboard</a>
            <a href="{{ route('portal.loans.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">My Loans</a>
            <a href="{{ route('portal.loans.create') }}" class="block px-3 py-2 rounded-lg text-sm font-bold text-emerald-700 bg-emerald-50">Apply for Loan</a>
            <a href="{{ route('portal.payments.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Payments & Receipts</a>
            <a href="{{ route('portal.financial-history') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Financial History</a>
            <a href="{{ route('portal.notifications') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Notifications</a>
            <a href="{{ route('portal.support') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Help & Support</a>
            <a href="{{ route('portal.profile') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Profile Settings</a>
            <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-slate-100">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">Log out</button>
            </form>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center justify-between text-emerald-800 text-sm font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 flex items-center justify-between text-red-800 text-sm font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 text-sm font-medium space-y-1 shadow-sm">
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
    <footer class="bg-white border-t border-slate-200 py-6 px-4 sm:px-8 mt-auto text-xs text-slate-500 text-center flex flex-col sm:flex-row justify-between items-center gap-2">
        <span>&copy; {{ date('Y') }} FinCore Customer Lending Portal. All rights reserved.</span>
        <span class="font-mono text-[11px] text-slate-400">Secure Paystack Payments & Real-time Loan Tracking</span>
    </footer>

    @stack('scripts')
</body>
</html>
