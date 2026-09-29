<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PortalFinancialHistoryController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;

        $transactions = $customer->financialTransactions()
            ->with(['loan', 'financialAccount'])
            ->latest('transaction_date')
            ->paginate(15);

        $totalDebits = (float) $customer->financialTransactions()->sum('debit');
        $totalCredits = (float) $customer->financialTransactions()->sum('credit');

        return view('portal.financial-history', compact(
            'customer',
            'transactions',
            'totalDebits',
            'totalCredits'
        ));
    }
}
