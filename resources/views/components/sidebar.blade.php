{{-- Desktop Sidebar --}}
<aside class="hidden lg:flex w-64 shrink-0 bg-slate-950 text-white flex-col min-h-screen sticky top-0 h-screen border-r border-slate-800">

    {{-- Logo --}}
    <div class="h-20 px-6 flex items-center border-b border-white/10 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/20 text-white font-bold text-lg">
                F
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-tight text-white leading-none">De Ferg Money</h1>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-1">Lending Management</p>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-6 space-y-6 overflow-y-auto">

        <div>
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Overview</p>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h14V10"/></svg>
                Dashboard
            </a>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Lending</p>
            <div class="space-y-1">
                <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('customers.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-4H7v4m10 0H7m10-8a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Customers
                </a>
                <a href="{{ route('loans.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('loans.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Lendings
                </a>
                <a href="{{ route('loan-products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('loan-products.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m-6 4h4M5 4h14v16H5z"/></svg>
                    Lending Products
                </a>
                <a href="{{ route('payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('payments.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white'}}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14v10H5V9zm4 4h6"/></svg>
                    Payments
                </a>
                <a href="{{ route('collections.index') }}" class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('collections.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Collections</span>
                    </div>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Finance</p>
            <div class="space-y-1">
                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('transactions.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 6h3m-6 4h9m-11 4h13m-11 4h9M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                    Transactions
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('reports.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10v-3m3 3V5M5 19h14"/></svg>
                    Reports
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Administration</p>
            <div class="space-y-1">
                <a href="{{ route('administrators.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('administrators.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Administrators
                </a>
                <a href="{{ route('audit-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('audit-logs.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Audit Logs
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">System</p>
            <div class="space-y-1">
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('notifications.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.5-2V9a6.5 6.5 0 00-13 0v6L4 17h5m6 0a3 3 0 01-6 0"/></svg>
                    Notifications
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('settings.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    Settings
                </a>
            </div>
        </div>

    </nav>

    {{-- User Profile --}}
    <div class="p-4 border-t border-white/10 shrink-0 bg-slate-900">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 min-w-0 hover:opacity-80 transition">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate text-white leading-snug">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">Admin Profile</p>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-red-400 transition rounded-lg hover:bg-white/5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- Mobile Sidebar Drawer --}}
<div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden">
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="sidebarOpen = false"></div>
    <div class="fixed inset-y-0 left-0 flex max-w-full">
        <div class="w-72 bg-slate-950 text-white flex flex-col h-full shadow-2xl">
            <div class="h-20 px-6 flex items-center justify-between border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg text-white font-bold text-lg">F</div>
                    <div>
                        <h1 class="font-bold text-lg tracking-tight text-white leading-none">FinCore</h1>
                        <p class="text-[10px] text-slate-400 font-medium uppercase mt-1">Loan Management</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-4 overflow-y-auto">
                <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Dashboard</a>
                <a href="{{ route('customers.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Customers</a>
                <a href="{{ route('loans.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Loans</a>
                <a href="{{ route('loan-products.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Loan Products</a>
                <a href="{{ route('payments.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Payments</a>
                <a href="{{ route('collections.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Collections</a>
                <a href="{{ route('transactions.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Transactions</a>
                <a href="{{ route('reports.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Reports</a>
                <a href="{{ route('administrators.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Administrators</a>
                <a href="{{ route('settings.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10">Settings</a>
            </nav>
        </div>
    </div>
</div>