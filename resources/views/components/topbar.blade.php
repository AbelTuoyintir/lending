<header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30 shadow-xs">

    <div class="flex items-center gap-4">
        {{-- Mobile Hamburger Button --}}
        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div>
            <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">
                {{ now()->format('l, d F Y') }}
            </p>
            <h2 class="font-bold text-slate-900 text-lg sm:text-xl leading-tight">
                @yield('page-title', 'Dashboard')
            </h2>
        </div>
    </div>

    <div class="flex items-center gap-3">

        {{-- Global Search Trigger --}}
        <button @click="searchOpen = true" type="button" class="hidden sm:flex items-center gap-3 px-3.5 py-2 rounded-xl border border-slate-200 text-sm text-slate-400 hover:border-slate-300 hover:bg-slate-50 transition">
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
            <span class="text-xs text-slate-500">Quick search...</span>
            <span class="text-[10px] bg-slate-100 border border-slate-200 text-slate-500 font-mono px-1.5 py-0.5 rounded">⌘ K</span>
        </button>

        {{-- Mobile Search Trigger --}}
        <button @click="searchOpen = true" type="button" class="sm:hidden p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
        </button>

        {{-- Notifications Link --}}
        <a href="{{ route('notifications.index') }}" class="relative p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.5-2V9a6.5 6.5 0 00-13 0v6L4 17h5m6 0a3 3 0 01-6 0"/></svg>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        </a>

        {{-- Profile Dropdown Link --}}
        <div class="flex items-center pl-3 border-l border-slate-200">
            <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 hover:opacity-80 transition">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="hidden xl:block text-left">
                    <p class="text-xs font-semibold text-slate-900 leading-snug">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-400 font-medium">Administrator</p>
                </div>
            </a>
        </div>

    </div>

</header>