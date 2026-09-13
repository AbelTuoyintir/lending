@extends('layouts.app')

@section('title', 'System Administrators | FinCore')

@section('content')

<div class="space-y-6">

    <x-page-header
        title="System Administrators"
        subtitle="Manage administrative accounts, access credentials, and user roles."
    >
        <x-slot:actions>
            <a href="{{ route('administrators.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold shadow-md shadow-blue-600/20 hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                + Add Administrator
            </a>
        </x-slot:actions>
    </x-page-header>

    {{-- Administrators Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Administrator Name</th>
                        <th class="px-6 py-4">Email Address</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Created Date</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($administrators as $admin)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $admin->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono">
                                {{ $admin->email }}
                            </td>
                            <td class="px-6 py-4 uppercase text-[10px] font-bold text-blue-600 tracking-wider">
                                System Administrator
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $admin->created_at ? $admin->created_at->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-status-badge status="active" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state
                                    title="No administrators found"
                                    description="No administrator accounts found."
                                    :actionUrl="route('administrators.create')"
                                    actionLabel="+ Add Administrator"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($administrators->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $administrators->links() }}
            </div>
        @endif
    </div>

</div>

@endsection