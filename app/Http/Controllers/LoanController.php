<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FinancialAccount;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(
        protected LoanService $loanService
    ) {}

    /**
     * Display all loans.
     */
    public function index(Request $request)
    {
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv($request);
        }

        $loans = Loan::query()
            ->with(['customer', 'loanProduct'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('loan_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('customer_number', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                $query->whereDate('loan_date', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                $query->whereDate('loan_date', '<=', $dateTo);
            })
            ->when($request->due_date, function ($query, $dueDate) {
                $query->whereDate('maturity_date', '<=', $dueDate);
            })
            ->when($request->amount_min, function ($query, $min) {
                $query->where('principal_amount', '>=', $min);
            })
            ->when($request->amount_max, function ($query, $max) {
                $query->where('principal_amount', '<=', $max);
            })
            ->when($request->sort, function ($query, $sort) {
                if ($sort === 'oldest') {
                    $query->oldest();
                } elseif ($sort === 'amount_desc') {
                    $query->orderBy('principal_amount', 'desc');
                } elseif ($sort === 'amount_asc') {
                    $query->orderBy('principal_amount', 'asc');
                } else {
                    $query->latest();
                }
            }, function ($query) {
                $query->latest();
            })
            ->paginate(20)
            ->withQueryString();

        return view('loans.index', compact('loans'));
    }

    /**
     * Show loan creation form.
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')
            ->orderBy('first_name')
            ->get();

        $loanProducts = LoanProduct::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('loans.create', compact(
            'customers',
            'loanProducts'
        ));
    }

    /**
     * Store a new loan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'loan_product_id' => ['required', 'exists:loan_products,id'],
            'principal_amount' => ['required', 'numeric', 'min:0.01'],
            'duration' => ['required', 'integer', 'min:1'],
            'loan_date' => ['required', 'date'],
            'first_payment_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        if ($customer->status === 'blacklisted') {
            return back()->withInput()->with('error', 'Cannot create loan for a blacklisted customer.');
        }

        try {
            $loan = $this->loanService->createLoan($validated);

            if ($request->has('disburse_immediately') && $request->disburse_immediately) {
                $this->loanService->approveLoan($loan);
                $firstAccount = FinancialAccount::where('is_active', true)->first();
                if ($firstAccount) {
                    $this->loanService->disburseLoan($loan, $firstAccount->id, $validated['loan_date']);
                }
            }

            return redirect()
                ->route('loans.show', $loan)
                ->with('success', 'Loan created successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display a loan.
     */
    public function show(Loan $loan)
    {
        $financialAccounts = FinancialAccount::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $loan->load([
            'customer',
            'loanProduct',
            'repayments',
            'payments.allocations',
            'interestCycles',
        ]);

        return view('loans.show', compact('loan', 'financialAccounts'));
    }

    /**
     * Approve loan.
     */
    public function approve(Loan $loan)
    {
        try {
            $this->loanService->approveLoan($loan);

            return back()->with('success', 'Loan approved successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Disburse loan.
     */
    public function disburse(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'financial_account_id' => ['required', 'exists:financial_accounts,id'],
            'disbursement_date' => ['required', 'date'],
        ]);

        try {
            $this->loanService->disburseLoan(
                $loan,
                $validated['financial_account_id'],
                $validated['disbursement_date']
            );

            return back()->with('success', 'Loan disbursed successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel loan.
     */
    public function cancel(Loan $loan)
    {
        try {
            $this->loanService->cancelLoan($loan);

            return back()->with('success', 'Loan cancelled successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark as defaulted.
     */
    public function markDefaulted(Loan $loan)
    {
        try {
            $this->loanService->markDefaulted($loan);

            return back()->with('success', 'Loan marked as defaulted.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function exportCsv(Request $request)
    {
        $loans = Loan::with(['customer', 'loanProduct'])->get();

        $filename = 'loans_export_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($loans) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Loan Number',
                'Customer Number',
                'Customer Name',
                'Principal (GHS)',
                'Interest (GHS)',
                'Total Payable (GHS)',
                'Amount Paid (GHS)',
                'Outstanding Balance (GHS)',
                'Loan Date',
                'Due Date',
                'Status',
            ]);

            foreach ($loans as $l) {
                fputcsv($file, [
                    $l->loan_number,
                    $l->customer->customer_number ?? '',
                    $l->customer->full_name ?? '',
                    $l->principal_amount,
                    $l->interest_amount,
                    $l->total_payable,
                    $l->amount_paid,
                    $l->outstanding_balance,
                    $l->loan_date ? $l->loan_date->format('Y-m-d') : '',
                    $l->maturity_date ? $l->maturity_date->format('Y-m-d') : '',
                    $l->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
