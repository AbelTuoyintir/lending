@props(['status'])

@php
    $statusStyles = [
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'approved' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'disbursed' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        'partially_paid' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'overdue' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
        'defaulted' => 'bg-red-50 text-red-700 ring-red-600/20',
        'blacklisted' => 'bg-red-50 text-red-700 ring-red-600/20',
        'cancelled' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'inactive' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'draft' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
        'fully_paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'written_off' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
    ];

    $normalizedStatus = strtolower((string) $status);
    $label = str_replace('_', ' ', $normalizedStatus);
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide ring-1 ring-inset '.($statusStyles[$normalizedStatus] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20'),
]) }}>
    {{ $label }}
</span>
