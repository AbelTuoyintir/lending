<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv($request);
        }

        $transactions = FinancialTransaction::query()
            ->with(['account', 'customer', 'loan', 'user'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                $query->whereDate('transaction_date', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                $query->whereDate('transaction_date', '<=', $dateTo);
            })
            ->latest('transaction_date')
            ->paginate(20)
            ->withQueryString();

        $totalDisbursements = FinancialTransaction::whereIn('type', ['loan_disbursement', 'disbursement'])->sum('debit');
        $totalCollections = FinancialTransaction::whereIn('type', ['loan_payment', 'repayment'])->sum('credit');
        $totalInterestCharged = FinancialTransaction::whereIn('type', ['interest_charge', 'compound_interest'])->sum('credit');

        return view('transactions.index', compact(
            'transactions',
            'totalDisbursements',
            'totalCollections',
            'totalInterestCharged'
        ));
    }

    private function exportCsv(Request $request)
    {
        $transactions = FinancialTransaction::with(['customer', 'loan', 'user'])->get();

        $filename = 'transactions_ledger_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Transaction ID',
                'Date',
                'Type',
                'Customer',
                'Loan Number',
                'Debit (GHS)',
                'Credit (GHS)',
                'Balance After (GHS)',
                'Reference',
            ]);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->transaction_number,
                    $t->transaction_date ? $t->transaction_date->format('Y-m-d') : $t->created_at->format('Y-m-d'),
                    $t->type,
                    $t->customer->full_name ?? '',
                    $t->loan->loan_number ?? '',
                    $t->debit ?? 0,
                    $t->credit ?? 0,
                    $t->balance_after ?? 0,
                    $t->reference,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
