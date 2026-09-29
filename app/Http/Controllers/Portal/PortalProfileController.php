<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = $user->customer;

        return view('portal.profile', compact('user', 'customer'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;

        $validatedUser = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $validatedCustomer = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'employer' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user->update([
            'email' => $validatedUser['email'],
            'name' => trim($validatedCustomer['first_name'].' '.$validatedCustomer['last_name']),
        ]);

        if ($customer) {
            $customer->update(array_merge($validatedCustomer, [
                'email' => $validatedUser['email'],
            ]));
        }

        return back()->with('success', 'Your profile details have been updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Your password has been changed successfully.');
    }
}
