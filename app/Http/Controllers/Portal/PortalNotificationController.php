<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PortalNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = $user->customer;

        /*
         * Generate customer notifications based on real loan events
         */
        $notifications = collect();

        foreach ($customer->loans as $loan) {
            // 1. Loan Approved / Pending
            if ($loan->status === 'approved' || $loan->approved_at) {
                $notifications->push([
                    'title' => 'Loan Approved',
                    'message' => "Your loan application {$loan->loan_number} for GHS ".number_format($loan->principal_amount, 2).' has been approved.',
                    'type' => 'success',
                    'date' => $loan->approved_at ?? $loan->updated_at,
                ]);
            }

            // 2. Loan Disbursed
            if (in_array($loan->status, ['active', 'disbursed', 'partially_paid', 'overdue', 'defaulted', 'fully_paid']) && $loan->disbursement_date) {
                $notifications->push([
                    'title' => 'Loan Disbursed',
                    'message' => "Your loan {$loan->loan_number} for GHS ".number_format($loan->principal_amount, 2).' has been disbursed and is now active.',
                    'type' => 'success',
                    'date' => $loan->disbursement_date,
                ]);
            }

            // 3. Due Date Reminders (Before, On, After)
            if (in_array($loan->status, ['active', 'disbursed', 'partially_paid', 'overdue']) && $loan->maturity_date) {
                $dueDate = Carbon::parse($loan->maturity_date);
                $today = Carbon::today();

                if ($dueDate->isFuture()) {
                    $notifications->push([
                        'title' => 'Upcoming Due Date',
                        'message' => "Your loan payment for {$loan->loan_number} is due on ".$dueDate->format('d F Y').'.',
                        'type' => 'warning',
                        'date' => $loan->updated_at,
                    ]);
                } elseif ($dueDate->isToday()) {
                    $notifications->push([
                        'title' => 'Due Date Today',
                        'message' => "Your loan payment for {$loan->loan_number} is due today.",
                        'type' => 'warning',
                        'date' => $today->toDateTimeString(),
                    ]);
                } else {
                    $notifications->push([
                        'title' => 'Loan Overdue',
                        'message' => "Your loan {$loan->loan_number} remains outstanding. Additional interest may apply according to your loan agreement.",
                        'type' => 'danger',
                        'date' => $dueDate->toDateTimeString(),
                    ]);
                }
            }

            // 4. Compound Interest Added
            foreach ($loan->interestCycles as $cycle) {
                $notifications->push([
                    'title' => 'Compound Interest Added',
                    'message' => 'Monthly compound interest of GHS '.number_format($cycle->interest_amount, 2)." was added to loan {$loan->loan_number}. New balance: GHS ".number_format($cycle->closing_balance, 2).'.',
                    'type' => 'warning',
                    'date' => $cycle->cycle_date ?? $cycle->created_at,
                ]);
            }

            // 5. Loan Fully Paid
            if ($loan->status === 'fully_paid') {
                $notifications->push([
                    'title' => 'Loan Fully Paid',
                    'message' => "Congratulations! Your loan {$loan->loan_number} has been fully settled.",
                    'type' => 'success',
                    'date' => $loan->updated_at,
                ]);
            }
        }

        // 6. Payments Received / Failed
        foreach ($customer->payments as $payment) {
            if ($payment->status === 'completed') {
                $notifications->push([
                    'title' => 'Payment Received',
                    'message' => 'Your payment of GHS '.number_format($payment->amount, 2).' has been successfully recorded. Your remaining balance is GHS '.number_format($payment->loan->outstanding_balance, 2).'.',
                    'type' => 'info',
                    'date' => $payment->payment_date ?? $payment->created_at,
                ]);
            } elseif ($payment->status === 'failed') {
                $notifications->push([
                    'title' => 'Payment Failed',
                    'message' => 'Your payment attempt of GHS '.number_format($payment->amount, 2)." for loan {$payment->loan->loan_number} failed. Please try again.",
                    'type' => 'danger',
                    'date' => $payment->payment_date ?? $payment->created_at,
                ]);
            }
        }

        $notifications = $notifications->sortByDesc('date')->values();

        return view('portal.notifications', compact('customer', 'notifications'));
    }
}
