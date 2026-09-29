@extends('layouts.app')

@section('title', 'Add Customer | FinCore')
@section('page-title', 'Register New Customer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('customers.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← Back to Customers</a>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Register New Borrower Customer</h1>
            <p class="text-xs text-slate-500">Provide personal, address, employment, and emergency contact details.</p>
        </div>
    </div>

    <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Section 1: Personal Information --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Personal Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Kwame">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Mensah">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Appiah">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Primary Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. 0241234567">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Alternate Phone</label>
                    <input type="text" name="alternate_phone" value="{{ old('alternate_phone') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. 0501234567">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. kwame@example.com">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Gender</label>
                    <select name="gender" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Select gender</option>
                        <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                        <option value="Other" @selected(old('gender') === 'Other')>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">National ID Type</label>
                    <select name="id_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Select ID type</option>
                        <option value="Ghana Card" @selected(old('id_type') === 'Ghana Card')>Ghana Card</option>
                        <option value="Passport" @selected(old('id_type') === 'Passport')>Passport</option>
                        <option value="Voter ID" @selected(old('id_type') === 'Voter ID')>Voter ID</option>
                        <option value="Driver License" @selected(old('id_type') === 'Driver License')>Driver License</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">ID Number</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. GHA-123456789-0">
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
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="Street name, house number, landmark...">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">City / Town</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Accra / Kumasi">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Region</label>
                    <input type="text" name="region" value="{{ old('region') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Greater Accra Region">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Ghana Digital Address</label>
                    <input type="text" name="digital_address" value="{{ old('digital_address') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. GA-123-4567">
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
                    <input type="text" name="occupation" value="{{ old('occupation') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Civil Servant / Trader / Accountant">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Employer / Business Name</label>
                    <input type="text" name="employer" value="{{ old('employer') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Ministry of Health / Self Employed">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Employment Address</label>
                    <input type="text" name="employment_address" value="{{ old('employment_address') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="Workplace location or market shop...">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Monthly Income (GHS)</label>
                    <input type="number" step="0.01" name="monthly_income" value="{{ old('monthly_income') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. 3500.00">
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
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="Full name of reference...">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Relationship</label>
                    <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="e.g. Spouse / Brother / Business Partner">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="Reference phone number...">
                </div>

                <div class="md:col-span-3">
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Reference Address</label>
                    <input type="text" name="emergency_contact_address" value="{{ old('emergency_contact_address') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="Address of reference contact...">
                </div>
            </div>
        </div>

        {{-- Section 5: Additional Info --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span> Additional Notes
            </h2>

            <div class="text-xs">
                <textarea name="notes" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500" placeholder="Internal background checks, remarks, or special credit notes...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('customers.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition">
                Save Customer
            </button>
        </div>

    </form>
</div>
@endsection