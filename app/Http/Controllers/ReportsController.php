<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanInterestCycle;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Reports Overview Hub.
     */
    public function index()
    {
        $totalLoansCount = Loan::count();
        $totalDisbursed = Loan::whereNotNull('disbursement_date')->sum('principal_amount');
        $totalCollected = Payment::where('status', 'completed')->sum('amount');
        $totalOutstanding = Loan::whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue'])->sum('outstanding_balance');

        return view('reports.index', compact(
            'totalLoansCount',
            'totalDisbursed',
            'totalCollected',
            'totalOutstanding'
        ));
    }

    public function loans(Request $request)
    {
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportLoansCsv($request);
        }

        $loans = Loan::with(['customer', 'loanProduct'])
            ->when($request->from, fn ($query, $from) => $query->whereDate('loan_date', '>=', $from))
            ->when($request->to, fn ($query, $to) => $query->whereDate('loan_date', '<=', $to))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest('loan_date')
            ->paginate(50)
            ->withQueryString();

        return view('reports.loans', compact('loans'));
    }

    public function payments(Request $request)
    {
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportPaymentsCsv($request);
        }

        $query = Payment::with(['customer', 'loan'])
            ->where('status', 'completed')
            ->when($request->from, fn ($query, $from) => $query->whereDate('payment_date', '>=', $from))
            ->when($request->to, fn ($query, $to) => $query->whereDate('payment_date', '<=', $to));

        $totalCollectedSum = (clone $query)->sum('amount');
        $totalPaymentsCount = (clone $query)->count();
        $avgPayment = $totalPaymentsCount > 0 ? round($totalCollectedSum / $totalPaymentsCount, 2) : 0;

        $payments = $query->latest('payment_date')->paginate(50)->withQueryString();

        return view('reports.payments', compact('payments', 'totalCollectedSum', 'totalPaymentsCount', 'avgPayment'));
    }

    public function customers(Request $request)
    {
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCustomersCsv();
        }

        $customers = Customer::withCount('loans')
            ->withSum('loans', 'principal_amount')
            ->withSum('loans', 'outstanding_balance')
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(50)
            ->withQueryString();

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'active')->count();
        $blacklistedCustomers = Customer::where('status', 'blacklisted')->count();

        return view('reports.customers', compact('customers', 'totalCustomers', 'activeCustomers', 'blacklistedCustomers'));
    }

    public function interest()
    {
        $loans = Loan::with(['customer', 'interestCycles'])
            ->where(function ($q) {
                $q->where('interest_amount', '>', 0)
                    ->orWhereHas('interestCycles');
            })
            ->paginate(50);

        $initialInterestSum = Loan::sum('interest_amount');
        $compoundInterestSum = LoanInterestCycle::sum('interest_amount');
        $totalInterestSum = $initialInterestSum + $compoundInterestSum;

        return view('reports.interest', compact(
            'loans',
            'initialInterestSum',
            'compoundInterestSum',
            'totalInterestSum'
        ));
    }

    public function outstanding()
    {
        $loans = Loan::with(['customer', 'loanProduct'])
            ->whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue', 'defaulted'])
            ->where('outstanding_balance', '>', 0)
            ->orderByDesc('outstanding_balance')
            ->paginate(50);

        $totalOutstandingSum = Loan::whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue', 'defaulted'])->sum('outstanding_balance');
        $activeOutstandingSum = Loan::whereIn('status', ['disbursed', 'active', 'partially_paid'])->sum('outstanding_balance');
        $overdueOutstandingSum = Loan::whereIn('status', ['overdue', 'defaulted'])->sum('outstanding_balance');

        return view('reports.outstanding', compact(
            'loans',
            'totalOutstandingSum',
            'activeOutstandingSum',
            'overdueOutstandingSum'
        ));
    }

    private function exportLoansCsv(Request $request)
    {
        $loans = Loan::with(['customer', 'loanProduct'])->get();
        $filename = 'loans_report_'.date('Y-m-d').'.csv';

        return response()->stream(function () use ($loans) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Loan #', 'Customer', 'Principal (GHS)', 'Interest (GHS)', 'Total Payable (GHS)', 'Paid (GHS)', 'Outstanding (GHS)', 'Due Date', 'Status']);
            foreach ($loans as $l) {
                fputcsv($file, [
                    $l->loan_number,
                    $l->customer->full_name ?? '',
                    $l->principal_amount,
                    $l->interest_amount,
                    $l->total_payable,
                    $l->amount_paid,
                    $l->outstanding_balance,
                    $l->maturity_date ? $l->maturity_date->format('Y-m-d') : '',
                    $l->status,
                ]);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function exportPaymentsCsv(Request $request)
    {
        $payments = Payment::with(['customer', 'loan'])->where('status', 'completed')->get();
        $filename = 'payments_report_'.date('Y-m-d').'.csv';

        return response()->stream(function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Payment #', 'Customer', 'Loan #', 'Amount (GHS)', 'Method', 'Date', 'Reference']);
            foreach ($payments as $p) {
                fputcsv($file, [
                    $p->payment_number,
                    $p->customer->full_name ?? '',
                    $p->loan->loan_number ?? '',
                    $p->amount,
                    $p->payment_method,
                    $p->payment_date->format('Y-m-d'),
                    $p->reference,
                ]);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function exportCustomersCsv()
    {
        $customers = Customer::withCount('loans')->get();
        $filename = 'customers_report_'.date('Y-m-d').'.csv';

        return response()->stream(function () use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer #', 'First Name', 'Last Name', 'Phone', 'Email', 'Status', 'Date Registered']);
            foreach ($customers as $c) {
                fputcsv($file, [
                    $c->customer_number,
                    $c->first_name,
                    $c->last_name,
                    $c->phone,
                    $c->email,
                    $c->status,
                    $c->created_at->format('Y-m-d'),
                ]);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
