<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class CollectionsController extends Controller
{
    public function index(Request $request)
    {
        $loans = Loan::query()
            ->with([
                'customer',
                'loanProduct',
            ])
            ->whereIn('status', [
                'overdue',
                'defaulted',
            ])
            ->where(
                'outstanding_balance',
                '>',
                0
            )
            ->when(
                $request->search,
                function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where(
                                'loan_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'customer',
                                function ($query) use ($search) {
                                    $query
                                        ->where(
                                            'first_name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'last_name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'phone',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );
                    });
                }
            )
            ->orderByDesc(
                'outstanding_balance'
            )
            ->paginate(20)
            ->withQueryString();

        return view(
            'collections.index',
            compact('loans')
        );
    }

    public function show(Loan $loan)
    {
        $loan->load([
            'customer',
            'repayments',
            'payments',
            'interestCycles',
        ]);

        return view(
            'collections.show',
            compact('loan')
        );
    }
}
