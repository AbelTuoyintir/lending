<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\LoanCalculationService;
use Illuminate\Http\Request;

class InterestCycleController extends Controller
{
    public function __construct(
        protected LoanCalculationService $loanCalculationService
    ) {
    }

    /**
     * Show the current month's interest calculation.
     */
    public function preview(Loan $loan)
    {
        $calculation = $this->loanCalculationService
            ->calculateMonthlyInterest($loan);

        return view(
            'loans.interest-preview',
            compact('loan', 'calculation')
        );
    }

    /**
     * Apply monthly compound interest.
     */
    public function apply(Loan $loan)
    {
        try {
            $cycle = $this->loanCalculationService
                ->applyMonthlyInterest($loan);

            return back()->with(
                'success',
                'Monthly compound interest of GHS ' .
                number_format($cycle->interest_amount, 2) .
                ' has been applied.'
            );
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}