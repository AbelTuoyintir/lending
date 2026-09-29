@extends('layouts.app')

@section('title', 'Edit Customer ' . $customer->customer_number . ' | FinCore')
@section('page-title', 'Edit Customer Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('customers.show', $customer) }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Customer Details</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Edit Customer: {{ $customer->full_name }}</h1>
            <p class="text-xs text-slate-500">Customer Number: <span class="font-mono font-bold text-blue-600">{{ $customer->customer_number }}</span> • Registered: {{ $customer->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Account Status & Actions Header Box --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <label class="block font-bold uppercase tracking-wider text-xs text-slate-600 mb-1">Account Eligibility Status *</label>
                <select name="status" required class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white font-semibold text-xs focus:ring-2 focus:ring-blue-500">
                    <option value="active" @selected(old('status', $customer->status) === 'active')>Active (Eligible for Loans)</option>
                    <option value="inactive" @selected(old('status', $customer->status) === 'inactive')>Inactive (Temporarily Suspended)</option>
                    <option value="blacklisted" @selected(old('status', $customer->status) === 'blacklisted')>Blacklisted (Strictly Prohibited from Lending)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                @if($customer->status !== 'blacklisted')
                    <button type="button" @click="Swal.fire({
                        title: 'Blacklist Customer?',
                        text: 'Blacklisted customers cannot be issued any new loans.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Blacklist',
                        confirmButtonColor: '#dc2626'
                    }).then((r) => { if (r.isConfirmed) { document.querySelector('select[name=status]').value = 'blacklisted'; } })" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold rounded-xl transition">
                        Blacklist Customer
                    </button>
                @endif
            </div>
        </div>

        {{-- Section 1: Personal Information --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Personal Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $customer->middle_name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Primary Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Alternate Phone</label>
                    <input type="text" name="alternate_phone" value="{{ old('alternate_phone', $customer->alternate_phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth?->format('Y-m-d')) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Gender</label>
                    <select name="gender" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Select gender</option>
                        <option value="Male" @selected(old('gender', $customer->gender) === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $customer->gender) === 'Female')>Female</option>
                        <option value="Other" @selected(old('gender', $customer->gender) === 'Other')>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">National ID Type</label>
                    <select name="id_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Select ID type</option>
                        <option value="Ghana Card" @selected(old('id_type', $customer->id_type) === 'Ghana Card')>Ghana Card</option>
                        <option value="Passport" @selected(old('id_type', $customer->id_type) === 'Passport')>Passport</option>
                        <option value="Voter ID" @selected(old('id_type', $customer->id_type) === 'Voter ID')>Voter ID</option>
                        <option value="Driver License" @selected(old('id_type', $customer->id_type) === 'Driver License')>Driver License</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">ID Number</label>
                    <input type="text" name="id_number" value="{{ old('id_number', $customer->id_number) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Section 2: Residential Address --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Residential Address
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="md:col-span-3">
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Residential Address</label>
                    <input type="text" name="address" value="{{ old('address', $customer->address) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">City / Town</label>
                    <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Region</label>
                    <input type="text" name="region" value="{{ old('region', $customer->region) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Ghana Digital Address</label>
                    <input type="text" name="digital_address" value="{{ old('digital_address', $customer->digital_address) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Section 3: Employment Details --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Employment & Income
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Occupation / Role</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $customer->occupation) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Employer / Business Name</label>
                    <input type="text" name="employer" value="{{ old('employer', $customer->employer) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Employment Address</label>
                    <input type="text" name="employment_address" value="{{ old('employment_address', $customer->employment_address) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Monthly Income (GHS)</label>
                    <input type="number" step="0.01" name="monthly_income" value="{{ old('monthly_income', $customer->monthly_income) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Section 4: Emergency / Reference Contact --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Emergency & Guarantor Contact
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Contact Name</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $customer->emergency_contact_name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Relationship</label>
                    <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $customer->emergency_contact_relationship) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $customer->emergency_contact_phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-3">
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Reference Address</label>
                    <input type="text" name="emergency_contact_address" value="{{ old('emergency_contact_address', $customer->emergency_contact_address) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Section 5: Additional Info --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Additional Notes
            </h2>

            <div class="text-xs">
                <textarea name="notes" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">{{ old('notes', $customer->notes) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('customers.show', $customer) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition">
                Update Customer
            </button>
        </div>

    </form>
</div>
@endsection