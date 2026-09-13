@extends('layouts.app')

@section('title', 'Audit Logs | FinCore')

@section('content')

<div class="space-y-6">

    <x-page-header
        title="Administrative Audit Logs"
        subtitle="Immutable security trail tracking all system operations, disbursements, status changes, and repayments."
    />

    {{-- Audit Logs Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Timestamp</th>
                        <th class="px-6 py-4">Administrator</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Module</th>
                        <th class="px-6 py-4">Record ID</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-500 font-mono">{{ $log['date'] }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $log['admin'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700">
                                    {{ $log['action'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-600">{{ $log['module'] }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $log['record'] }}</td>
                            <td class="px-6 py-4 text-slate-400 font-mono">{{ $log['ip_address'] }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log['description'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-0"><x-empty-state title="No audit activity recorded" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection