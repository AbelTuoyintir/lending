@extends('layouts.app')

@section('title', 'Edit Customer | FinCore')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('customers.show', $customer) }}" class="text-slate-400 hover:text-slate-600 text-sm">
                &larr; Back to Customer
            </a>
            <h1 class="text-2xl font-bold mt-1">Edit Customer: {{ $customer->full_name }}</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 text-red-800 border border-red-200 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $customer->middle_name) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Alternate Phone</label>
                    <input type="text" name="alternate_phone" value="{{ old('alternate_phone', $customer->alternate_phone) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $customer->occupation) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Employer</label>
                    <input type="text" name="employer" value="{{ old('employer', $customer->employer) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Monthly Income (GHS)</label>
                    <input type="number" step="0.01" name="monthly_income" value="{{ old('monthly_income', $customer->monthly_income) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">
                        <option value="active" @selected(old('status', $customer->status) === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $customer->status) === 'inactive')>Inactive</option>
                        <option value="blacklisted" @selected(old('status', $customer->status) === 'blacklisted')>Blacklisted</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:border-blue-500">{{ old('notes', $customer->notes) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('customers.show', $customer) }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200">Cancel</a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-lg shadow-blue-600/20">
                    Update Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
