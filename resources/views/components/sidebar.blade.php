<aside
    class="
        hidden lg:flex
        w-64
        shrink-0
        bg-slate-950
        text-white
        flex-col
        min-h-screen
    "
>

    {{-- Logo --}}
    <div class="h-20 px-6 flex items-center border-b border-white/10">

        <div
            class="
                w-10 h-10
                rounded-xl
                bg-blue-600
                flex items-center justify-center
                shadow-lg shadow-blue-600/20
            "
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
        </div>

        <div class="ml-3">
            <h1 class="font-bold text-lg tracking-tight">
                FinCore
            </h1>

            <p class="text-xs text-slate-400">
                Loan Management
            </p>
        </div>

    </div>


    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">

        <p class="px-3 mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
            Overview
        </p>

        <a
            href="{{ route('dashboard') }}"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                transition
                {{ request()->routeIs('dashboard')
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                    : 'text-slate-400 hover:bg-white/5 hover:text-white'
                }}
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l9-9 9 9M5 10v10h14V10" />
            </svg>

            Dashboard
        </a>


        <p class="px-3 pt-6 mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
            Lending
        </p>

        <a
            href="{{ route('customers.index') }}"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                transition
                {{ request()->routeIs('customers.*')
                    ? 'bg-white/10 text-white'
                    : 'text-slate-400 hover:bg-white/5 hover:text-white'
                }}
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5V4H2v16h5m10 0v-4H7v4m10 0H7m10-8a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            Customers
        </a>


        <a
            href="{{ route('loans.index') }}"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                transition
                {{ request()->routeIs('loans.*')
                    ? 'bg-white/10 text-white'
                    : 'text-slate-400 hover:bg-white/5 hover:text-white'
                }}
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            Loans
        </a>


        <a
            href="{{ route('loan-products.index') }}"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                transition
                {{ request()->routeIs('loan-products.*')
                    ? 'bg-white/10 text-white'
                    : 'text-slate-400 hover:bg-white/5 hover:text-white'
                }}
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m-6 4h6m-6 4h4M5 4h14v16H5z" />
            </svg>

            Loan Products
        </a>


        <a
            href="{{ route('payments.create', ['loan' => 1]) }}"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                text-slate-400
                hover:bg-white/5
                hover:text-white
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a5 5 0 00-10 0v2m-2 0h14v10H5V9zm4 4h6" />
            </svg>

            Payments
        </a>


        <a
            href="{{ route('collections.index') }}"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                {{ request()->routeIs('collections.*')
                    ? 'bg-white/10 text-white'
                    : 'text-slate-400 hover:bg-white/5 hover:text-white'
                }}
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            Collections

            <span class="ml-auto px-2 py-0.5 rounded-full bg-red-500/20 text-red-400 text-[10px]">
                {{ $overdueLoansCount ?? 0 }}
            </span>
        </a>


        <p class="px-3 pt-6 mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
            Finance
        </p>

        <a
            href="{{ route('reports.loans') }}"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                text-slate-400
                hover:bg-white/5
                hover:text-white
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-6m3 6V7m3 10v-3m3 3V5M5 19h14" />
            </svg>

            Reports
        </a>


        <a
            href="#"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                text-slate-400
                hover:bg-white/5
                hover:text-white
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.5 6h3m-6 4h9m-11 4h13m-11 4h9M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
            </svg>

            Transactions
        </a>


        <p class="px-3 pt-6 mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
            System
        </p>

        <a
            href="#"
            class="
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-sm font-medium
                text-slate-400
                hover:bg-white/5
                hover:text-white
            "
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM19.4 15a1.7 1.7 0 00.34 1.88l.06.06-1.8 1.8-.06-.06A1.7 1.7 0 0016.06 19l-.02.08h-2.55l-.02-.08a1.7 1.7 0 00-1.88-.34l-.06.06-1.8-1.8.06-.06A1.7 1.7 0 009.06 15l-.08-.02v-2.55l.08-.02a1.7 1.7 0 00.34-1.88l-.06-.06 1.8-1.8.06.06a1.7 1.7 0 001.88.34l.02-.08h2.55l.02.08a1.7 1.7 0 001.88-.34l.06-.06 1.8 1.8-.06.06a1.7 1.7 0 00-.34 1.88l.08.02v2.55l-.08.02z" />
            </svg>

            Settings
        </a>

    </nav>


    {{-- User --}}
    <div class="p-4 border-t border-white/10">

        <div class="flex items-center gap-3">

            <div
                class="
                    w-10 h-10
                    rounded-full
                    bg-blue-600
                    flex items-center justify-center
                    font-semibold
                "
            >
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div class="flex-1 min-w-0">

                <p class="text-sm font-medium truncate">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </p>

                <p class="text-xs text-slate-500 truncate">
                    Administrator
                </p>

            </div>

        </div>

    </div>

</aside>