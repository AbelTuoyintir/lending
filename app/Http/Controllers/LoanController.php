<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FinancialAccount;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function __construct(
        protected LoanService $loanService
    ) {
    }

    /**
     * Display all loans.
     */
    public function index(Request $request)
    {
        $loans = Loan::query()
            ->with(['customer', 'loanProduct'])
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
            ->latest()
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

        try {
            $loan = $this->loanService->createLoan($validated);

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

            return back()->with(
                'success',
                'Loan approved successfully.'
            );
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * Disburse loan.
     */
    public function disburse(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'financial_account_id' => [
                'required',
                'exists:financial_accounts,id'
            ],
            'disbursement_date' => [
                'required',
                'date'
            ],
        ]);

        try {
            $this->loanService->disburseLoan(
                $loan,
                $validated['financial_account_id'],
                $validated['disbursement_date']
            );

            return back()->with(
                'success',
                'Loan disbursed successfully.'
            );
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * Cancel loan.
     */
    public function cancel(Loan $loan)
    {
        try {
            $this->loanService->cancelLoan($loan);

            return back()->with(
                'success',
                'Loan cancelled successfully.'
            );
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}