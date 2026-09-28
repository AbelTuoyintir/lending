<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\PaymentReceiptMail;
use App\Models\FinancialAccount;
use App\Models\Loan;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\PaystackService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PortalPaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected PaystackService $paystackService
    ) {}

    public function index()
    {
        $customer = Auth::user()->customer;
        $payments = $customer->payments()->with('loan')->latest()->paginate(15);

        return view('portal.payments.index', compact('customer', 'payments'));
    }

    public function receipt(Payment $payment)
    {
        $customer = Auth::user()->customer;

        if ($payment->customer_id !== $customer->id) {
            abort(403, 'Unauthorized access to payment receipt.');
        }

        $payment->load(['customer', 'loan.loanProduct']);

        return view('portal.payments.receipt', compact('payment', 'customer'));
    }

    public function initializePaystack(Request $request)
    {
        $customer = Auth::user()->customer;

        $validated = $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $loan = Loan::where('id', $validated['loan_id'])
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $amount = round((float) $validated['amount'], 2);

        if ($amount > (float) $loan->outstanding_balance) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed the outstanding balance of GHS '.number_format($loan->outstanding_balance, 2)]);
        }

        $reference = 'PAYSTACK_'.now()->format('YmdHis').'_'.strtoupper(Str::random(6));

        session([
            'paystack_pending_'.$reference => [
                'loan_id' => $loan->id,
                'customer_id' => $customer->id,
                'amount' => $amount,
                'reference' => $reference,
            ],
            'paystack_latest_ref' => $reference,
        ]);

        try {
            $response = $this->paystackService->initializeTransaction([
                'email' => $customer->email ?? Auth::user()->email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => route('portal.payments.paystack.callback'),
                'metadata' => [
                    'loan_id' => $loan->id,
                    'customer_id' => $customer->id,
                    'amount' => $amount,
                ],
            ]);

            if ($response['status'] && ! empty($response['authorization_url'])) {
                return redirect()->away($response['authorization_url']);
            }

            return back()->withErrors(['error' => 'Unable to connect to Paystack payment gateway. Please try again.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function paystackCallback(Request $request)
    {
        $reference = $request->query('reference') ?? $request->query('trxref') ?? session('paystack_latest_ref');

        if (! $reference) {
            return redirect()->route('portal.payments.index')->with('error', 'No transaction reference returned from Paystack.');
        }

        $sessionKey = 'paystack_pending_'.$reference;
        $pendingData = session($sessionKey);

        try {
            $verification = $this->paystackService->verifyTransaction($reference);

            if (! $verification['status']) {
                return redirect()->route('portal.payments.index')->with('error', 'Payment verification failed: '.($verification['message'] ?? 'Transaction incomplete'));
            }

            $loanId = $pendingData['loan_id'] ?? $request->query('loan_id') ?? ($verification['metadata']['loan_id'] ?? null);
            $amount = $pendingData['amount'] ?? $request->query('amount') ?? ($verification['amount'] ?? null);

            if (! $loanId || ! $amount) {
                return redirect()->route('portal.payments.index')->with('error', 'Invalid transaction payload metadata.');
            }

            $loan = Loan::findOrFail($loanId);
            $financialAccount = FinancialAccount::first();

            $payment = $this->paymentService->recordPayment($loan, [
                'amount' => $amount,
                'payment_method' => 'card',
                'reference' => $reference,
                'payment_date' => now()->toDateTimeString(),
                'financial_account_id' => $financialAccount?->id,
                'notes' => 'Paystack Online Gateway Payment',
            ]);

            session()->forget([$sessionKey, 'paystack_latest_ref']);

            $recipientEmail = $loan->customer->email ?? Auth::user()->email;
            if ($recipientEmail) {
                try {
                    Mail::to($recipientEmail)->send(new PaymentReceiptMail($payment));
                } catch (Exception $mailEx) {
                    Log::warning('Failed sending payment receipt email: '.$mailEx->getMessage());
                }
            }

            return redirect()->route('portal.payments.receipt', $payment)
                ->with('success', 'Payment of GHS '.number_format($payment->amount, 2).' processed successfully via Paystack!');
        } catch (Exception $e) {
            Log::error('Paystack callback error: '.$e->getMessage());

            return redirect()->route('portal.payments.index')->with('error', 'Payment processing error: '.$e->getMessage());
        }
    }
}
