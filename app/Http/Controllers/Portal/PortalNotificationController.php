<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
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
            if (in_array($loan->status, ['active', 'disbursed', 'partially_paid'])) {
                $notifications->push([
                    'title' => 'Loan Disbursed & Active',
                    'message' => "Your loan {$loan->loan_number} for GHS ".number_format($loan->principal_amount, 2).' is active.',
                    'type' => 'success',
                    'date' => $loan->disbursement_date ?? $loan->created_at,
                ]);
            }

            if ($loan->status === 'fully_paid') {
                $notifications->push([
                    'title' => 'Loan Fully Paid',
                    'message' => "Congratulations! Loan {$loan->loan_number} has been fully settled.",
                    'type' => 'success',
                    'date' => $loan->updated_at,
                ]);
            }
        }

        foreach ($customer->payments as $payment) {
            $notifications->push([
                'title' => 'Payment Received',
                'message' => 'Your payment of GHS '.number_format($payment->amount, 2)." for loan {$payment->loan->loan_number} was successfully recorded. Remaining balance: GHS ".number_format($payment->loan->outstanding_balance, 2).'.',
                'type' => 'info',
                'date' => $payment->payment_date,
            ]);
        }

        $notifications = $notifications->sortByDesc('date')->values();

        return view('portal.notifications', compact('customer', 'notifications'));
    }
}
