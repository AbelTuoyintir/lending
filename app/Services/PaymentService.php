<?php

namespace App\Services;

use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    /**
     * Record a payment against a loan.
     */
    public function recordPayment(
        Loan $loan,
        array $data
    ): Payment {
        return DB::transaction(function () use (
            $loan,
            $data
        ) {
            /*
             * Lock the loan so two payments cannot
             * modify the balance at the same time.
             */
            $loan = Loan::query()
                ->lockForUpdate()
                ->findOrFail($loan->id);

            if (!in_array($loan->status, [
                'disbursed',
                'active',
                'partially_paid',
                'overdue',
                'defaulted',
            ])) {
                throw new RuntimeException(
                    'Payment cannot be recorded for this loan.'
                );
            }

            if ($loan->outstanding_balance <= 0) {
                throw new RuntimeException(
                    'This loan has no outstanding balance.'
                );
            }

            $paymentAmount = round(
                (float) $data['amount'],
                2
            );

            if ($paymentAmount <= 0) {
                throw new RuntimeException(
                    'Payment amount must be greater than zero.'
                );
            }

            /*
             * Do not allow payment greater than outstanding balance
             * for now.
             */
            if ($paymentAmount > (float) $loan->outstanding_balance) {
                throw new RuntimeException(
                    'Payment cannot be greater than the outstanding balance.'
                );
            }

            /*
             * Generate unique payment number.
             */
            $paymentNumber = $this->generatePaymentNumber();

            /*
             * Create payment.
             */
            $payment = Payment::create([
                'payment_number' => $paymentNumber,

                'loan_id' => $loan->id,

                'customer_id' => $loan->customer_id,

                'amount' => $paymentAmount,

                'payment_method' =>
                    $data['payment_method'],

                'reference' =>
                    $data['reference'],

                'payment_date' =>
                    $data['payment_date'],

                'status' => 'completed',

                'received_by' =>
                    auth()->id(),

                'notes' =>
                    $data['notes'] ?? null,
            ]);

            /*
             * Reduce the current outstanding balance.
             *
             * Example:
             *
             * Outstanding = 1,300
             * Payment = 500
             *
             * New balance = 800
             */
            $newOutstandingBalance = round(
                (float) $loan->outstanding_balance -
                $paymentAmount,
                2
            );

            /*
             * Update amount paid.
             */
            $newAmountPaid = round(
                (float) $loan->amount_paid +
                $paymentAmount,
                2
            );

            /*
             * Determine loan status.
             */
            if ($newOutstandingBalance <= 0) {
                $newOutstandingBalance = 0;

                $status = 'fully_paid';
            } else {
                $status = 'partially_paid';
            }

            /*
             * Update loan.
             */
            $loan->update([
                'amount_paid' => $newAmountPaid,

                'outstanding_balance' =>
                    $newOutstandingBalance,

                'status' => $status,
            ]);

            /*
             * Create allocation.
             *
             * For this business rule, the payment reduces
             * the outstanding balance directly.
             */
            $payment->allocations()->create([
                'loan_repayment_id' => null,

                'principal_amount' => $paymentAmount,

                'interest_amount' => 0,

                'fee_amount' => 0,

                'penalty_amount' => 0,

                'total_amount' => $paymentAmount,
            ]);

            /*
             * Update financial account if supplied.
             */
            if (!empty($data['financial_account_id'])) {
                $this->recordFinancialTransaction(
                    $payment,
                    $loan,
                    $data['financial_account_id'],
                    $paymentAmount
                );
            }

            /*
             * Update the most recent interest cycle's
             * payment amount.
             */
            $this->updateCurrentInterestCycle(
                $loan,
                $paymentAmount
            );

            return $payment;
        });
    }

    /**
     * Reverse a payment.
     */
    public function reversePayment(
        Payment $payment,
        string $reason
    ): void {
        DB::transaction(function () use (
            $payment,
            $reason
        ) {
            $payment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($payment->id);

            if ($payment->status === 'reversed') {
                throw new RuntimeException(
                    'This payment has already been reversed.'
                );
            }

            $loan = Loan::query()
                ->lockForUpdate()
                ->findOrFail($payment->loan_id);

            $paymentAmount = (float) $payment->amount;

            /*
             * Restore the loan balance.
             */
            $loan->amount_paid = max(
                0,
                round(
                    (float) $loan->amount_paid -
                    $paymentAmount,
                    2
                )
            );

            $loan->outstanding_balance = round(
                (float) $loan->outstanding_balance +
                $paymentAmount,
                2
            );

            if ($loan->outstanding_balance > 0) {
                $loan->status = 'partially_paid';
            }

            $loan->save();

            /*
             * Mark payment as reversed.
             */
            $payment->update([
                'status' => 'reversed',

                'notes' => trim(
                    ($payment->notes ?? '') .
                    "\nReversed: " .
                    $reason
                ),
            ]);
        });
    }

    /**
     * Record money received in financial account.
     */
    protected function recordFinancialTransaction(
        Payment $payment,
        Loan $loan,
        int $financialAccountId,
        float $amount
    ): FinancialTransaction {
        $account = FinancialAccount::query()
            ->lockForUpdate()
            ->findOrFail($financialAccountId);

        $newBalance = round(
            (float) $account->current_balance +
            $amount,
            2
        );

        $account->update([
            'current_balance' => $newBalance,
        ]);

        return FinancialTransaction::create([
            'transaction_number' =>
                'TXN-' . strtoupper(Str::random(12)),

            'financial_account_id' =>
                $account->id,

            'loan_id' =>
                $loan->id,

            'customer_id' =>
                $loan->customer_id,

            'type' =>
                'principal_repayment',

            'debit' => 0,

            'credit' => $amount,

            'balance_after' => $newBalance,

            'reference' =>
                $payment->reference,

            'description' =>
                'Loan repayment',

            'created_by' =>
                auth()->id(),

            'transaction_date' =>
                $payment->payment_date,
        ]);
    }

    /**
     * Update the latest interest cycle.
     */
    protected function updateCurrentInterestCycle(
        Loan $loan,
        float $paymentAmount
    ): void {
        $cycle = $loan->interestCycles()
            ->latest('cycle_date')
            ->first();

        if (!$cycle) {
            return;
        }

        $cycle->update([
            'payment_amount' => round(
                (float) $cycle->payment_amount +
                $paymentAmount,
                2
            ),

            'closing_balance' =>
                $loan->outstanding_balance,
        ]);
    }

    /**
     * Generate unique payment number.
     */
    protected function generatePaymentNumber(): string
    {
        do {
            $paymentNumber =
                'PAY-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(Str::random(5));

        } while (
            Payment::where(
                'payment_number',
                $paymentNumber
            )->exists()
        );

        return $paymentNumber;
    }
}