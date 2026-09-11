<header
    class="
        h-20
        bg-white
        border-b border-slate-200
        flex items-center
        justify-between
        px-4 lg:px-6
    "
>

    <div>

        <p class="text-xs text-slate-400">
            {{ now()->format('l, d F Y') }}
        </p>

        <h2 class="font-semibold text-slate-900">
            @yield('page-title', 'Dashboard')
        </h2>

    </div>


    <div class="flex items-center gap-3">

        {{-- Search --}}
        <button
            class="
                hidden md:flex
                items-center gap-2
                px-3 py-2
                rounded-xl
                border border-slate-200
                text-sm text-slate-400
                hover:bg-slate-50
            "
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
            </svg>

            Search

            <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded">
                ⌘ K
            </span>
        </button>


        {{-- Notifications --}}
        <button
            class="
                relative
                w-10 h-10
                rounded-xl
                border border-slate-200
                flex items-center justify-center
                text-slate-500
                hover:bg-slate-50
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.5-2V9a6.5 6.5 0 00-13 0v6L4 17h5m6 0a3 3 0 01-6 0" />
            </svg>

            <span
                class="
                    absolute top-2 right-2
                    w-2 h-2
                    bg-red-500
                    rounded-full
                "
            ></span>
        </button>


        {{-- Profile --}}
        <div class="hidden sm:flex items-center gap-3 pl-3 border-l border-slate-200">

            <div
                class="
                    w-9 h-9
                    rounded-full
                    bg-blue-100
                    text-blue-700
                    flex items-center justify-center
                    font-semibold text-sm
                "
            >
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div class="hidden xl:block">

                <p class="text-sm font-semibold">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </p>

                <p class="text-xs text-slate-400">
                    Administrator
                </p>

            </div>

        </div>

    </div>

</header>