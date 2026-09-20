@props([
    'id' => null,
    'title' => '',
    'show' => false
])

<div x-data="{ open: @js($show) }"
     x-show="open"
     x-cloak
     @keydown.window.escape="open = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     @if($id) id="{{ $id }}" @endif>
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="open = false"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 p-6 space-y-4" @click.stop>
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">{{ $title }}</h3>
                <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>

            <div class="text-sm text-slate-600 space-y-3">
                {{ $slot }}
            </div>

            @if(isset($footer))
                <div class="border-t border-slate-100 pt-3 flex items-center justify-end gap-2">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
