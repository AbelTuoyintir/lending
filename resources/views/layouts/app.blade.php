<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', 'FinCore')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

<div class="min-h-screen flex">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Area --}}
    <div class="flex-1 min-w-0">

        {{-- Topbar --}}
        @include('components.topbar')

        <main class="p-4 lg:p-6">

            @yield('content')

        </main>

    </div>

</div>

@stack('scripts')

</body>
</html>