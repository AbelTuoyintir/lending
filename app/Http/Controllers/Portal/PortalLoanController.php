<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalLoanController extends Controller
{
    public function __construct(protected LoanService $loanService) {}

    public function index(Request $request)
    {
        $customer = Auth::user()->customer;
        $tab = $request->query('tab', 'active');

        $query = $customer->loans()->with('loanProduct')->latest();

        if ($tab === 'completed') {
            $query->whereIn('status', ['fully_paid', 'cancelled']);
        } else {
            $query->whereIn('status', ['active', 'disbursed', 'partially_paid', 'pending', 'overdue', 'defaulted']);
        }

        $loans = $query->paginate(10);

        return view('portal.loans.index', compact('customer', 'loans', 'tab'));
    }

    public function create()
    {
        $loanProducts = LoanProduct::all();

        return view('portal.loans.create', compact('loanProducts'));
    }

    public function store(Request $request)
    {
        $customer = Auth::user()->customer;

        $validated = $request->validate([
            'loan_product_id' => ['required', 'exists:loan_products,id'],
            'principal_amount' => ['required', 'numeric', 'min:1'],
            'duration' => ['required', 'integer', 'min:1'],
            'first_payment_date' => ['nullable', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $loanData = array_merge($validated, [
                'customer_id' => $customer->id,
                'loan_date' => now()->toDateString(),
            ]);

            $loan = $this->loanService->createLoan($loanData);

            return redirect()->route('portal.loans.show', $loan)
                ->with('success', 'Your loan application (#'.$loan->loan_number.') has been submitted successfully and is pending review.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Loan $loan)
    {
        $customer = Auth::user()->customer;

        if ($loan->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to loan details.');
        }

        $loan->load(['loanProduct', 'interestCycles', 'payments']);

        return view('portal.loans.show', compact('loan', 'customer'));
    }

    public function agreement(Loan $loan)
    {
        $customer = Auth::user()->customer;

        if ($loan->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to loan agreement.');
        }

        $loan->load(['loanProduct', 'customer']);

        return view('portal.loans.agreement', compact('loan', 'customer'));
    }

    public function statement(Loan $loan)
    {
        $customer = Auth::user()->customer;

        if ($loan->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to loan statement.');
        }

        $loan->load(['loanProduct', 'interestCycles', 'payments']);

        return view('portal.loans.statement', compact('loan', 'customer'));
    }
}
