<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class CollectionsController extends Controller
{
    public function index(Request $request)
    {
        $dueTodayCount = Loan::whereIn('status', ['active', 'partially_paid', 'disbursed'])
            ->whereDate('maturity_date', today())
            ->count();

        $dueThisWeekCount = Loan::whereIn('status', ['active', 'partially_paid', 'disbursed'])
            ->whereBetween('maturity_date', [today(), today()->addDays(7)])
            ->count();

        $dueThisMonthCount = Loan::whereIn('status', ['active', 'partially_paid', 'disbursed'])
            ->whereMonth('maturity_date', now()->month)
            ->whereYear('maturity_date', now()->year)
            ->count();

        $overdueCount = Loan::where('status', 'overdue')->count();
        $overdueAmount = Loan::where('status', 'overdue')->sum('outstanding_balance');

        $defaultedCount = Loan::where('status', 'defaulted')->count();
        $defaultedAmount = Loan::where('status', 'defaulted')->sum('outstanding_balance');

        $dueTodayLoans = Loan::with('customer')
            ->whereIn('status', ['active', 'partially_paid', 'disbursed'])
            ->whereDate('maturity_date', today())
            ->limit(5)
            ->get();

        $dueSoonLoans = Loan::with('customer')
            ->whereIn('status', ['active', 'partially_paid', 'disbursed'])
            ->where('maturity_date', '>=', today())
            ->where('maturity_date', '<=', today()->endOfMonth())
            ->orderBy('maturity_date')
            ->limit(5)
            ->get();

        $query = Loan::query()
            ->with(['customer', 'loanProduct', 'interestCycles'])
            ->whereIn('status', ['active', 'partially_paid', 'overdue', 'defaulted'])
            ->where('outstanding_balance', '>', 0)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('loan_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderByDesc('outstanding_balance');

        $loans = $query->paginate(20)->withQueryString();

        return view('collections.index', compact(
            'loans',
            'dueTodayCount',
            'dueThisWeekCount',
            'dueThisMonthCount',
            'overdueCount',
            'overdueAmount',
            'defaultedCount',
            'defaultedAmount',
            'dueTodayLoans',
            'dueSoonLoans'
        ));
    }

    public function show(Loan $loan)
    {
        $loan->load(['customer', 'repayments', 'payments', 'interestCycles']);

        $daysOverdue = 0;
        if ($loan->maturity_date && now()->greaterThan($loan->maturity_date) && $loan->outstanding_balance > 0) {
            $daysOverdue = (int) now()->diffInDays($loan->maturity_date);
        }

        $accumulatedCompoundInterest = $loan->interestCycles->sum('interest_charged');

        return view('collections.show', compact('loan', 'daysOverdue', 'accumulatedCompoundInterest'));
    }
}
