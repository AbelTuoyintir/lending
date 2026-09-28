<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PortalDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = $user->customer;

        $loans = $customer->loans()->with(['loanProduct', 'interestCycles', 'payments'])->latest()->get();
        $payments = $customer->payments()->with('loan')->latest()->take(5)->get();

        $activeLoans = $loans->whereIn('status', ['active', 'disbursed', 'partially_paid', 'overdue', 'defaulted']);
        $activeLoansCount = $activeLoans->count();

        $totalBorrowed = (float) $loans->sum('principal_amount');
        $totalRepaid = (float) $customer->payments()->where('status', 'completed')->sum('amount');
        $totalOutstanding = (float) $activeLoans->sum('outstanding_balance');
        $totalInterest = (float) $loans->sum('interest_amount');

        /*
         * Nearest due date calculation
         */
        $nextDueDate = $activeLoans->whereNotNull('maturity_date')
            ->pluck('maturity_date')
            ->sort()
            ->first();

        return view('portal.dashboard', compact(
            'customer',
            'loans',
            'payments',
            'activeLoansCount',
            'totalBorrowed',
            'totalRepaid',
            'totalOutstanding',
            'totalInterest',
            'nextDueDate'
        ));
    }
}
