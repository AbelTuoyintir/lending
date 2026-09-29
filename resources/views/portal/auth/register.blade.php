<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Registration — De ferg money User Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased font-sans min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-xl bg-white rounded-2xl border border-slate-200 shadow-xl p-8 space-y-6">

    <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 text-white font-extrabold text-2xl shadow-sm mb-1">
            F
        </div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create Customer Portal Account</h1>
        <p class="text-xs text-slate-500">Sign up to apply for lending, track your loans, and pay online with Paystack.</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 text-xs font-medium space-y-1">
            <span class="font-bold">Please correct the error(s) below:</span>
            <ul class="list-disc list-inside space-y-0.5 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Phone Number *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g., 0241234567"
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Password *</label>
                <input type="password" name="password" required placeholder="At least 8 characters"
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Confirm Password *</label>
                <input type="password" name="password_confirmation" required
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Occupation</label>
                <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="e.g. Teacher, Entrepreneur"
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Monthly Income (GHS)</label>
                <input type="number" step="0.01" name="monthly_income" value="{{ old('monthly_income') }}" placeholder="e.g. 2500.00"
                       class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Residential Address</label>
            <input type="text" name="address" value="{{ old('address') }}" placeholder="Street, City, Region"
                   class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition text-sm">
            Complete Customer Registration
        </button>
    </form>

    <div class="border-t border-slate-200 pt-4 text-center">
        <p class="text-xs text-slate-600">Already have an account? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Log in here</a></p>
    </div>

</div>

</body>
</html>
