<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = FinancialTransaction::query()
            ->with(['account', 'user'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->latest('transaction_date')
            ->paginate(20)
            ->withQueryString();

        $totalDisbursements = FinancialTransaction::where('type', 'disbursement')->sum('amount');
        $totalCollections = FinancialTransaction::where('type', 'repayment')->sum('amount');
        $totalInterestCharged = FinancialTransaction::where('type', 'interest_charge')->sum('amount');

        return view('transactions.index', compact(
            'transactions',
            'totalDisbursements',
            'totalCollections',
            'totalInterestCharged'
        ));
    }
}
