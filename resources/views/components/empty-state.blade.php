@props([
    'title',
    'description' => null,
    'actionUrl' => null,
    'actionLabel' => null,
])

<div class="flex flex-col items-center justify-center px-6 py-12 text-center">
    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0H4m4-5h8" />
        </svg>
    </div>
    <h3 class="text-sm font-bold text-slate-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 max-w-md text-xs text-slate-500">{{ $description }}</p>
    @endif
    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="mt-4 rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
            {{ $actionLabel }}
        </a>
    @endif
</div>
