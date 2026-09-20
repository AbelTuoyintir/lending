<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = collect([
            [
                'id' => 1,
                'title' => 'Loan Overdue Alert',
                'message' => 'Loan LN-000001 for John Doe has passed month-end repayment date and is marked overdue.',
                'time' => '10 minutes ago',
                'type' => 'overdue',
                'read' => false,
            ],
            [
                'id' => 2,
                'title' => 'Repayment Received',
                'message' => 'Payment of GHS 500.00 recorded for loan LN-000001.',
                'time' => '1 hour ago',
                'type' => 'payment',
                'read' => true,
            ],
            [
                'id' => 3,
                'title' => 'New Customer Registered',
                'message' => 'New borrower profile created for Sarah Mensah.',
                'time' => '1 day ago',
                'type' => 'customer',
                'read' => true,
            ],
        ]);

        return view('notifications.index', compact('notifications'));
    }
}
