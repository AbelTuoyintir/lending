@props([
    'type' => 'success',
    'message' => ''
])

@php
    $bgClass = match($type) {
        'success' => 'bg-emerald-600 text-white',
        'error', 'danger' => 'bg-red-600 text-white',
        'warning' => 'bg-amber-500 text-white',
        default => 'bg-slate-900 text-white',
    };
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl {{ $bgClass }} text-xs font-semibold">
    <span>{{ $message }}</span>
    <button @click="show = false" class="opacity-70 hover:opacity-100 text-base leading-none">&times;</button>
</div>
