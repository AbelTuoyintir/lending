@props([
    'title' => 'No data found',
    'message' => 'There are currently no records available to display.',
    'actionUrl' => null,
    'actionLabel' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 text-center shadow-xs flex flex-col items-center justify-center']) }}>
    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mb-4">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        @endif
    </div>

    <h3 class="text-base font-bold text-slate-900 tracking-tight">{{ $title }}</h3>
    <p class="mt-1 text-xs text-slate-500 max-w-sm leading-relaxed">{{ $message }}</p>

    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>{{ $actionLabel }}</span>
        </a>
    @endif
</div>