<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\LoanRepayment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();

        $activeCustomers = Customer::where(
            'status',
            'active'
        )->count();

        $activeLoans = Loan::whereIn('status', [
            'disbursed',
            'active',
            'partially_paid',
            'overdue',
        ])->count();

        $totalPrincipalDisbursed = Loan::whereNotNull(
            'disbursement_date'
        )->sum('principal_amount');

        $totalAmountPaid = Payment::where(
            'status',
            'completed'
        )->sum('amount');

        $totalOutstanding = Loan::whereIn('status', [
            'disbursed',
            'active',
            'partially_paid',
            'overdue',
        ])->sum('outstanding_balance');

        $overdueLoans = Loan::whereIn('status', [
            'overdue',
            'defaulted',
        ])->count();

        $fullyPaidLoans = Loan::where(
            'status',
            'fully_paid'
        )->count();

        $defaultedLoans = Loan::where(
            'status',
            'defaulted'
        )->count();

        $todayPayments = Payment::where(
            'status',
            'completed'
        )
            ->whereDate(
                'payment_date',
                today()
            )
            ->sum('amount');

        $monthPayments = Payment::where(
            'status',
            'completed'
        )
            ->whereMonth(
                'payment_date',
                now()->month
            )
            ->whereYear(
                'payment_date',
                now()->year
            )
            ->sum('amount');

        $recentLoans = Loan::with('customer')
            ->latest()
            ->limit(10)
            ->get();

        $recentPayments = Payment::with([
            'customer',
            'loan'
        ])
            ->where('status', 'completed')
            ->latest('payment_date')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'activeLoans',
            'totalPrincipalDisbursed',
            'totalAmountPaid',
            'totalOutstanding',
            'overdueLoans',
            'fullyPaidLoans',
            'defaultedLoans',
            'todayPayments',
            'monthPayments',
            'recentLoans',
            'recentPayments'
        ));
    }
}