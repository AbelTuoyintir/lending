<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PortalRegisterController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['nullable', 'string', 'max:500'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $customer = Customer::where('email', $validated['email'])
                ->orWhere('phone', $validated['phone'])
                ->first();

            if (! $customer) {
                $customerNumber = 'CUST-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
                $customer = Customer::create([
                    'customer_number' => $customerNumber,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] ?? null,
                    'occupation' => $validated['occupation'] ?? null,
                    'monthly_income' => $validated['monthly_income'] ?? null,
                    'status' => 'active',
                ]);
            }

            $user = User::create([
                'name' => $customer->full_name,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'customer_id' => $customer->id,
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('portal.dashboard')->with('success', 'Welcome to FinCore User Portal! Your account has been registered successfully.');
    }
}
