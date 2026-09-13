@extends('layouts.app')

@section('title', 'Notification Center | FinCore')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <x-page-header
        title="Notification Center"
        subtitle="Real-time administrator alerts for overdue loans, payment collections, and system activities."
    />

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm divide-y divide-slate-100">
        @forelse($notifications as $notif)
            <div class="p-5 flex items-start gap-4 transition hover:bg-slate-50/80 {{ !$notif['read'] ? 'bg-blue-50/20' : '' }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notif['type'] === 'overdue' ? 'bg-red-50 text-red-600' : ($notif['type'] === 'payment' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600') }}">
                    @if($notif['type'] === 'overdue')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-slate-900">{{ $notif['title'] }}</h4>
                        <span class="text-[10px] text-slate-400 font-semibold">{{ $notif['time'] }}</span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $notif['message'] }}</p>
                </div>
            </div>
        @empty
            <div class="p-0">
                <x-empty-state title="No notifications" description="You're all caught up! No unread system notifications." />
            </div>
        @endforelse
    </div>

</div>

@endsection