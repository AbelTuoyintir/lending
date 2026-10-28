<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');

        $customers = collect();
        $loans = collect();
        $payments = collect();

        if (! empty($query)) {
            $customers = Customer::query()
                ->where(function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('customer_number', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get();

            $loans = Loan::query()
                ->with('customer')
                ->where(function ($q) use ($query) {
                    $q->where('loan_number', 'like', "%{$query}%")
                        ->orWhereHas('customer', function ($sub) use ($query) {
                            $sub->where('first_name', 'like', "%{$query}%")
                                ->orWhere('last_name', 'like', "%{$query}%");
                        });
                })
                ->limit(10)
                ->get();

            $payments = Payment::query()
                ->with(['customer', 'loan'])
                ->where(function ($q) use ($query) {
                    $q->where('payment_number', 'like', "%{$query}%")
                        ->orWhere('reference', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get();
        }

        return view('search.index', compact('query', 'customers', 'loans', 'payments'));
    }
}
