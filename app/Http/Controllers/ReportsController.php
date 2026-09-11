<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
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
            'loan',
            'receivedBy'
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
}