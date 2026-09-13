@props([
    'title',
    'value',
    'subtitle' => null,
    'color' => 'blue',
])

@php
    $colorStyles = [
        'blue' => 'bg-blue-50 text-blue-600',
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'red' => 'bg-red-50 text-red-600',
        'slate' => 'bg-slate-100 text-slate-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm']) }}>
    <div class="mb-4 flex items-center justify-between gap-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $title }}</p>
        <span class="h-2.5 w-2.5 rounded-full {{ $colorStyles[$color] ?? $colorStyles['blue'] }}"></span>
    </div>
    <p class="text-xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
    @if($subtitle)
        <p class="mt-1 text-xs text-slate-500">{{ $subtitle }}</p>
    @endif
</div>
