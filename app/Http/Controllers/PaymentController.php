<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

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
