@extends('layouts.portal')

@section('title', 'Notifications — De ferg money Portal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Notification Center</h1>
        <p class="text-xs text-slate-500">Real-time alerts regarding loan approvals, disbursements, repayments, due dates, and interest updates.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">No Notifications</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">You're all caught up! Automated alerts will appear here as your loan account is processed.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($notifications as $notif)
                    <div class="p-5 flex items-start gap-4 hover:bg-slate-50 transition">
                        <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="space-y-1 flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-900">{{ $notif['title'] }}</h4>
                                <span class="text-[11px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($notif['date'])->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $notif['message'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
