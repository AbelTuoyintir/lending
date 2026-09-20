<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display recorded payments.
     */
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with(['customer', 'loan'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('payment_number', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->payment_method, function ($query, $paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            })
            ->latest('payment_date')
            ->paginate(20)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    /**
     * Display a payment receipt.
     */
    public function show(Payment $payment)
    {
        $payment->load(['customer', 'loan']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the admin form for recording a payment without a loan page.
     */
    public function manualCreate()
    {
        $loans = Loan::query()
            ->with('customer')
            ->whereIn('status', [
                'disbursed',
                'active',
                'partially_paid',
                'overdue',
                'defaulted',
            ])
            ->where('outstanding_balance', '>', 0)
            ->orderBy('loan_number')
            ->get();

        return view('payments.manual-create', compact('loans'));
    }

    /**
     * Record a payment from the admin payment form.
     */
    public function manualStore(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,mobile_money,bank_transfer,card,other'],
            'reference' => ['required', 'string', 'max:255', 'unique:payments,reference'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $loan = Loan::findOrFail($validated['loan_id']);
            $payment = $this->paymentService->recordPayment($loan, $validated);

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Payment recorded successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show payment form.
     */
    public function create(Loan $loan)
    {
        $loan->load('customer');

        return view('payments.create', compact('loan'));
    }

    /**
     * Record payment.
     */
    public function store(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_method' => [
                'required',
                'in:cash,mobile_money,bank_transfer,card,other',
            ],

            'reference' => [
                'required',
                'string',
                'max:255',
                'unique:payments,reference',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'financial_account_id' => [
                'nullable',
                'exists:financial_accounts,id',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        try {
            $payment = $this->paymentService->recordPayment(
                $loan,
                $validated
            );

            return redirect()
                ->route('loans.show', $loan)
                ->with(
                    'success',
                    'Payment of GHS '.
                    number_format($payment->amount, 2).
                    ' recorded successfully.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Reverse a payment.
     */
    public function reverse(Request $request, $payment)
    {
        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'min:5',
            ],
        ]);

        try {
            $this->paymentService->reversePayment(
                $payment,
                $validated['reason']
            );

            return back()->with(
                'success',
                'Payment reversed successfully.'
            );
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
