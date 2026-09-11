<?php

namespace App\Services;

use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use App\Models\Loan;
use App\Models\LoanProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class LoanService
{
    /**
     * Create a loan.
     */
    public function createLoan(array $data): Loan
    {
        return DB::transaction(function () use ($data) {

            $loanProduct = LoanProduct::findOrFail(
                $data['loan_product_id']
            );

            $principalAmount = round(
                (float) $data['principal_amount'],
                2
            );

            /*
             * Validate product limits.
             */
            if (
                $principalAmount <
                (float) $loanProduct->min_amount
            ) {
                throw new RuntimeException(
                    'Loan amount is below the minimum allowed amount.'
                );
            }

            if (
                $principalAmount >
                (float) $loanProduct->max_amount
            ) {
                throw new RuntimeException(
                    'Loan amount is above the maximum allowed amount.'
                );
            }

            /*
             * Validate duration.
             */
            if (
                $data['duration'] <
                $loanProduct->min_duration
            ) {
                throw new RuntimeException(
                    'Loan duration is below the minimum allowed duration.'
                );
            }

            if (
                $data['duration'] >
                $loanProduct->max_duration
            ) {
                throw new RuntimeException(
                    'Loan duration exceeds the maximum allowed duration.'
                );
            }

            /*
             * The interest rate is copied from the product
             * at the time the loan is created.
             *
             * This is important because changing the product
             * later should NOT change existing loans.
             */
            $interestRate =
                (float) $loanProduct->interest_rate;

            /*
             * Initial balance is the principal.
             *
             * Interest is NOT added here.
             *
             * It will be added at calendar month-end.
             */
            $loan = Loan::create([
                'loan_number' =>
                    $this->generateLoanNumber(),

                'customer_id' =>
                    $data['customer_id'],

                'loan_product_id' =>
                    $data['loan_product_id'],

                'principal_amount' =>
                    $principalAmount,

                'interest_rate' =>
                    $interestRate,

                'interest_type' =>
                    $loanProduct->interest_type,

                'interest_amount' => 0,

                'fees_amount' => 0,

                'penalty_amount' => 0,

                'total_payable' =>
                    $principalAmount,

                'amount_paid' => 0,

                'outstanding_balance' =>
                    $principalAmount,

                'duration' =>
                    $data['duration'],

                'repayment_frequency' =>
                    $loanProduct->repayment_frequency,

                'loan_date' =>
                    $data['loan_date'],

                'disbursement_date' => null,

                'first_payment_date' =>
                    $data['first_payment_date'] ?? null,

                /*
                 * Calendar month-end is used.
                 */
                'maturity_date' =>
                    \Carbon\Carbon::parse(
                        $data['loan_date']
                    )->endOfMonth(),

                'status' => 'pending',

                'approved_by' => null,

                'disbursed_by' => null,

                'notes' =>
                    $data['notes'] ?? null,
            ]);

            return $loan;
        });
    }

    /**
     * Approve a loan.
     */
    public function approveLoan(Loan $loan): Loan
    {
        return DB::transaction(function () use ($loan) {

            $loan = Loan::query()
                ->lockForUpdate()
                ->findOrFail($loan->id);

            if ($loan->status !== 'pending') {
                throw new RuntimeException(
                    'Only pending loans can be approved.'
                );
            }

            $loan->update([
                'status' => 'approved',

                'approved_by' =>
                    auth()->id(),
            ]);

            return $loan;
        });
    }

    /**
     * Disburse a loan.
     */
    public function disburseLoan(
        Loan $loan,
        int $financialAccountId,
        string $disbursementDate
    ): Loan {
        return DB::transaction(function () use (
            $loan,
            $financialAccountId,
            $disbursementDate
        ) {

            $loan = Loan::query()
                ->lockForUpdate()
                ->findOrFail($loan->id);

            if ($loan->status !== 'approved') {
                throw new RuntimeException(
                    'Only approved loans can be disbursed.'
                );
            }

            $account = FinancialAccount::query()
                ->lockForUpdate()
                ->findOrFail($financialAccountId);

            $principalAmount =
                (float) $loan->principal_amount;

            if (
                (float) $account->current_balance
                < $principalAmount
            ) {
                throw new RuntimeException(
                    'Insufficient funds in the selected financial account.'
                );
            }

            /*
             * Remove money from financial account.
             */
            $newAccountBalance = round(
                (float) $account->current_balance -
                $principalAmount,
                2
            );

            $account->update([
                'current_balance' =>
                    $newAccountBalance,
            ]);

            /*
             * Update loan.
             */
            $loan->update([
                'status' => 'active',

                'disbursement_date' =>
                    $disbursementDate,

                'disbursed_by' =>
                    auth()->id(),
            ]);

            /*
             * Record financial transaction.
             */
            FinancialTransaction::create([
                'transaction_number' =>
                    'TXN-' . strtoupper(Str::random(12)),

                'financial_account_id' =>
                    $account->id,

                'loan_id' =>
                    $loan->id,

                'customer_id' =>
                    $loan->customer_id,

                'type' =>
                    'loan_disbursement',

                'debit' =>
                    $principalAmount,

                'credit' => 0,

                'balance_after' =>
                    $newAccountBalance,

                'reference' =>
                    $loan->loan_number,

                'description' =>
                    'Loan disbursement',

                'created_by' =>
                    auth()->id(),

                'transaction_date' =>
                    $disbursementDate,
            ]);

            return $loan;
        });
    }

    /**
     * Cancel loan.
     */
    public function cancelLoan(Loan $loan): Loan
    {
        return DB::transaction(function () use ($loan) {

            $loan = Loan::query()
                ->lockForUpdate()
                ->findOrFail($loan->id);

            if (in_array($loan->status, [
                'active',
                'partially_paid',
                'overdue',
                'fully_paid',
            ])) {
                throw new RuntimeException(
                    'An active or paid loan cannot be cancelled.'
                );
            }

            $loan->update([
                'status' => 'cancelled',
            ]);

            return $loan;
        });
    }

    /**
     * Generate unique loan number.
     */
    protected function generateLoanNumber(): string
    {
        do {
            $loanNumber =
                'LN-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(Str::random(5));

        } while (
            Loan::where(
                'loan_number',
                $loanNumber
            )->exists()
        );

        return $loanNumber;
    }
}