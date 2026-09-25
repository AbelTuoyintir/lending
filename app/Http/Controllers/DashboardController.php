<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanInterestCycle;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();

        $activeCustomers = Customer::where('status', 'active')->count();

        $totalLoans = Loan::count();

        $activeLoans = Loan::whereIn('status', [
            'disbursed',
            'active',
            'partially_paid',
            'overdue',
        ])->count();

        $pendingLoans = Loan::where('status', 'pending')->count();

        $fullyPaidLoans = Loan::where('status', 'fully_paid')->count();

        $defaultedLoans = Loan::where('status', 'defaulted')->count();

        $totalPrincipalDisbursed = Loan::whereNotNull('disbursement_date')->sum('principal_amount');

        $initialInterest = Loan::sum('interest_amount');
        $compoundInterest = LoanInterestCycle::sum('interest_charged');
        $totalInterestGenerated = $initialInterest + $compoundInterest;

        $totalAmountPaid = Payment::where('status', 'completed')->sum('amount');

        $totalOutstanding = Loan::whereIn('status', [
            'disbursed',
            'active',
            'partially_paid',
            'overdue',
        ])->sum('outstanding_balance');

        $overdueAmount = Loan::whereIn('status', ['overdue', 'defaulted'])->sum('outstanding_balance');

        $overdueLoans = Loan::whereIn('status', [
            'overdue',
            'defaulted',
        ])->count();

        $todayPayments = Payment::where('status', 'completed')
            ->whereDate('payment_date', today())
            ->sum('amount');

        $monthPayments = Payment::where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $upcomingDueLoans = Loan::with('customer')
            ->whereIn('status', ['disbursed', 'active', 'partially_paid'])
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->endOfMonth())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        $overdueLoansList = Loan::with('customer')
            ->whereIn('status', ['overdue', 'defaulted'])
            ->orderByDesc('outstanding_balance')
            ->limit(5)
            ->get();

        $recentLoans = Loan::with('customer')
            ->latest()
            ->limit(10)
            ->get();

        $recentPayments = Payment::with(['customer', 'loan'])
            ->where('status', 'completed')
            ->latest('payment_date')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'totalLoans',
            'activeLoans',
            'pendingLoans',
            'fullyPaidLoans',
            'defaultedLoans',
            'totalPrincipalDisbursed',
            'totalInterestGenerated',
            'totalAmountPaid',
            'totalOutstanding',
            'overdueAmount',
            'overdueLoans',
            'todayPayments',
            'monthPayments',
            'upcomingDueLoans',
            'overdueLoansList',
            'recentLoans',
            'recentPayments'
        ));
    }
}
