<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\LoanInterestCycle;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Reports Overview Hub.
     */
    public function index()
    {
        $totalLoansCount = Loan::count();
        $totalDisbursed = Loan::whereNotNull('disbursement_date')->sum('principal_amount');
        $totalCollected = Payment::where('status', 'completed')->sum('amount');
        $totalOutstanding = Loan::whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue'])->sum('outstanding_balance');

        return view('reports.index', compact(
            'totalLoansCount',
            'totalDisbursed',
            'totalCollected',
            'totalOutstanding'
        ));
    }

    public function loans(Request $request)
    {
        $loans = Loan::with([
            'customer',
            'loanProduct'
        ])
            ->when(
                $request->from,
                fn ($query, $from) =>
                    $query->whereDate(
                        'loan_date',
                        '>=',
                        $from
                    )
            )
            ->when(
                $request->to,
                fn ($query, $to) =>
                    $query->whereDate(
                        'loan_date',
                        '<=',
                        $to
                    )
            )
            ->when(
                $request->status,
                fn ($query, $status) =>
                    $query->where(
                        'status',
                        $status
                    )
            )
            ->latest('loan_date')
            ->paginate(50)
            ->withQueryString();

        return view(
            'reports.loans',
            compact('loans')
        );
    }

    public function payments(Request $request)
    {
        $payments = Payment::with([
            'customer',
            'loan'
        ])
            ->where(
                'status',
                'completed'
            )
            ->when(
                $request->from,
                fn ($query, $from) =>
                    $query->whereDate(
                        'payment_date',
                        '>=',
                        $from
                    )
            )
            ->when(
                $request->to,
                fn ($query, $to) =>
                    $query->whereDate(
                        'payment_date',
                        '<=',
                        $to
                    )
            )
            ->latest('payment_date')
            ->paginate(50)
            ->withQueryString();

        return view(
            'reports.payments',
            compact('payments')
        );
    }

    public function customers()
    {
        $customers = Customer::withCount(
            'loans'
        )
            ->withSum(
                'loans',
                'principal_amount'
            )
            ->withSum(
                'loans',
                'outstanding_balance'
            )
            ->latest()
            ->paginate(50);

        return view(
            'reports.customers',
            compact('customers')
        );
    }

    public function interest()
    {
        $loans = Loan::with(['customer', 'interestCycles'])
            ->where('interest_amount', '>', 0)
            ->paginate(50);

        $initialInterestSum = Loan::sum('interest_amount');
        $compoundInterestSum = LoanInterestCycle::where('cycle_number', '>', 1)->sum('interest_amount');
        $totalInterestSum = $initialInterestSum + $compoundInterestSum;

        return view('reports.interest', compact(
            'loans',
            'initialInterestSum',
            'compoundInterestSum',
            'totalInterestSum'
        ));
    }

    public function outstanding()
    {
        $loans = Loan::with(['customer', 'loanProduct'])
            ->whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue', 'defaulted'])
            ->where('outstanding_balance', '>', 0)
            ->orderByDesc('outstanding_balance')
            ->paginate(50);

        $totalOutstandingSum = Loan::whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue', 'defaulted'])->sum('outstanding_balance');
        $overdueOutstandingSum = Loan::whereIn('status', ['overdue', 'defaulted'])->sum('outstanding_balance');

        return view('reports.outstanding', compact(
            'loans',
            'totalOutstandingSum',
            'overdueOutstandingSum'
        ));
    }
}