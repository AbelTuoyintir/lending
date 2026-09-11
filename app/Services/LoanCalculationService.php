<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanInterestCycle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LoanCalculationService
{
    /**
     * Calculate the interest that should be applied
     * at the end of the current calendar month.
     */
    public function calculateMonthlyInterest(Loan $loan): array
    {
        if (!in_array($loan->status, [
            'disbursed',
            'active',
            'partially_paid',
            'overdue',
        ])) {
            throw new RuntimeException(
                'Interest cannot be calculated for this loan.'
            );
        }

        if ($loan->outstanding_balance <= 0) {
            return [
                'opening_balance' => 0,
                'interest_rate' => (float) $loan->interest_rate,
                'interest_amount' => 0,
                'closing_balance' => 0,
                'cycle_date' => now()->endOfMonth(),
            ];
        }

        $openingBalance = (float) $loan->outstanding_balance;

        $interestRate = (float) $loan->interest_rate;

        $interestAmount = round(
            $openingBalance * ($interestRate / 100),
            2
        );

        $closingBalance = round(
            $openingBalance + $interestAmount,
            2
        );

        return [
            'opening_balance' => $openingBalance,

            'interest_rate' => $interestRate,

            'interest_amount' => $interestAmount,

            'closing_balance' => $closingBalance,

            'cycle_date' => now()->endOfMonth(),
        ];
    }

    /**
     * Apply the current month's compound interest.
     */
    public function applyMonthlyInterest(
        Loan $loan,
        ?Carbon $cycleDate = null
    ): LoanInterestCycle {
        return DB::transaction(function () use (
            $loan,
            $cycleDate
        ) {
            $loan = Loan::query()
                ->lockForUpdate()
                ->findOrFail($loan->id);

            $cycleDate ??= now()->endOfMonth();

            /*
             * Do not apply interest twice for the same month.
             */
            $existingCycle = LoanInterestCycle::where(
                'loan_id',
                $loan->id
            )
                ->whereDate(
                    'cycle_date',
                    $cycleDate->toDateString()
                )
                ->where(
                    'status',
                    'applied'
                )
                ->first();

            if ($existingCycle) {
                return $existingCycle;
            }

            if (!in_array($loan->status, [
                'disbursed',
                'active',
                'partially_paid',
                'overdue',
            ])) {
                throw new RuntimeException(
                    'Interest cannot be applied to this loan.'
                );
            }

            if ($loan->outstanding_balance <= 0) {
                throw new RuntimeException(
                    'This loan has no outstanding balance.'
                );
            }

            $openingBalance = round(
                (float) $loan->outstanding_balance,
                2
            );

            $interestRate = round(
                (float) $loan->interest_rate,
                4
            );

            /*
             * Example:
             *
             * 1000 × 30% = 300
             *
             * New balance:
             *
             * 1000 + 300 = 1300
             */
            $interestAmount = round(
                $openingBalance *
                ($interestRate / 100),
                2
            );

            $closingBalance = round(
                $openingBalance +
                $interestAmount,
                2
            );

            $lastCycle = LoanInterestCycle::where(
                'loan_id',
                $loan->id
            )
                ->orderByDesc('cycle_number')
                ->first();

            $cycleNumber = $lastCycle
                ? $lastCycle->cycle_number + 1
                : 1;

            $cycle = LoanInterestCycle::create([
                'loan_id' => $loan->id,

                'cycle_number' => $cycleNumber,

                'cycle_date' => $cycleDate->toDateString(),

                'opening_balance' => $openingBalance,

                'interest_rate' => $interestRate,

                'interest_amount' => $interestAmount,

                'payment_amount' => 0,

                'closing_balance' => $closingBalance,

                'status' => 'applied',
            ]);

            /*
             * Update loan balance.
             */
            $loan->update([
                'interest_amount' => round(
                    (float) $loan->interest_amount +
                    $interestAmount,
                    2
                ),

                'total_payable' => $closingBalance,

                'outstanding_balance' => $closingBalance,

                'status' => 'overdue',
            ]);

            return $cycle;
        });
    }
}