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
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv($request);
        }

        $query = Payment::query()
            ->with(['customer', 'loan'])
            ->where('status', 'completed')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('payment_number', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('loan', function ($query) use ($search) {
                            $query->where('loan_number', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->payment_method, function ($query, $paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                $query->whereDate('payment_date', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                $query->whereDate('payment_date', '<=', $dateTo);
            })
            ->when($request->customer_id, function ($query, $customerId) {
                $query->where('customer_id', $customerId);
            });

        $totalPaymentsCount = (clone $query)->count();
        $todayCollections = Payment::where('status', 'completed')->whereDate('payment_date', today())->sum('amount');
        $thisMonthCollections = Payment::where('status', 'completed')->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount');
        $totalCollected = Payment::where('status', 'completed')->sum('amount');

        $payments = $query->latest('payment_date')->paginate(20)->withQueryString();

        return view('payments.index', compact(
            'payments',
            'totalPaymentsCount',
            'todayCollections',
            'thisMonthCollections',
            'totalCollected'
        ));
    }

    /**
     * Display a payment receipt.
     */
    public function show(Payment $payment)
    {
        $payment->load(['customer', 'loan', 'allocations']);

        $principalPortion = $payment->allocations->where('type', 'principal')->sum('amount');
        $interestPortion = $payment->allocations->where('type', 'interest')->sum('amount');
        $compoundInterestPortion = $payment->allocations->where('type', 'compound_interest')->sum('amount');

        if ($payment->allocations->isEmpty()) {
            $principalPortion = $payment->amount;
            $interestPortion = 0;
            $compoundInterestPortion = 0;
        }

        return view('payments.show', compact('payment', 'principalPortion', 'interestPortion', 'compoundInterestPortion'));
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
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,mobile_money,bank_transfer,card,other'],
            'reference' => ['required', 'string', 'max:255', 'unique:payments,reference'],
            'payment_date' => ['required', 'date'],
            'financial_account_id' => ['nullable', 'exists:financial_accounts,id'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $payment = $this->paymentService->recordPayment($loan, $validated);

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Payment of GHS '.number_format($payment->amount, 2).' recorded successfully.');
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
            'reason' => ['required', 'string', 'min:5'],
        ]);

        try {
            $this->paymentService->reversePayment($payment, $validated['reason']);

            return back()->with('success', 'Payment reversed successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function exportCsv(Request $request)
    {
        $payments = Payment::with(['customer', 'loan'])->where('status', 'completed')->get();

        $filename = 'payments_export_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Payment Number',
                'Customer',
                'Loan Number',
                'Amount (GHS)',
                'Payment Method',
                'Reference',
                'Payment Date',
                'Status',
            ]);

            foreach ($payments as $p) {
                fputcsv($file, [
                    $p->payment_number,
                    $p->customer->full_name ?? '',
                    $p->loan->loan_number ?? '',
                    $p->amount,
                    $p->payment_method,
                    $p->reference,
                    $p->payment_date->format('Y-m-d'),
                    $p->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
