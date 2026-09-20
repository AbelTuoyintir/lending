@props([
    'title' => '',
    'amount' => 0,
    'subtitle' => null,
    'color' => 'slate', // slate, blue, emerald, amber, red, purple
])

@php
    $textColors = [
        'slate' => 'text-slate-900',
        'blue' => 'text-blue-600',
        'emerald' => 'text-emerald-600',
        'amber' => 'text-amber-600',
        'red' => 'text-red-600',
        'purple' => 'text-purple-600',
    ];

    $colorClass = $textColors[$color] ?? 'text-slate-900';
@endphp

<div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $title }}</p>
    <p class="text-lg font-extrabold {{ $colorClass }} mt-1 truncate">
        GHS {{ number_format((float) $amount, 2) }}
    </p>
    @if($subtitle)
        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $subtitle }}</p>
    @endif
</div>
