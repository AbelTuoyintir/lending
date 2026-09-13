<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // Mock audit logs or query from database if table exists
        $logs = collect([
            [
                'date' => now()->subMinutes(12)->format('d M Y, H:i'),
                'admin' => auth()->user()->name ?? 'Administrator',
                'action' => 'Admin recorded payment',
                'module' => 'Payments',
                'record' => 'PMT-001092',
                'ip_address' => '127.0.0.1',
                'description' => 'Recorded repayment of GHS 500.00 for loan LN-000001'
            ],
            [
                'date' => now()->subHours(2)->format('d M Y, H:i'),
                'admin' => auth()->user()->name ?? 'Administrator',
                'action' => 'Admin disbursed loan',
                'module' => 'Loans',
                'record' => 'LN-000001',
                'ip_address' => '127.0.0.1',
                'description' => 'Disbursed principal of GHS 1,000.00 to borrower John Doe'
            ],
            [
                'date' => now()->subHours(5)->format('d M Y, H:i'),
                'admin' => auth()->user()->name ?? 'Administrator',
                'action' => 'Admin approved loan',
                'module' => 'Loans',
                'record' => 'LN-000001',
                'ip_address' => '127.0.0.1',
                'description' => 'Approved loan application after credit review'
            ],
            [
                'date' => now()->subDays(1)->format('d M Y, H:i'),
                'admin' => auth()->user()->name ?? 'Administrator',
                'action' => 'Admin created customer',
                'module' => 'Customers',
                'record' => 'CUST-0001',
                'ip_address' => '127.0.0.1',
                'description' => 'Registered new customer John Doe'
            ],
        ]);

        return view('audit-logs.index', compact('logs'));
    }
}