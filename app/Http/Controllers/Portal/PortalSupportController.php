<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalSupportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = $user->customer;

        return view('portal.support', compact('customer', 'user'));
    }

    public function submitInquiry(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return back()->with('success', 'Your inquiry has been submitted successfully. Our support team will contact you shortly.');
    }
}
