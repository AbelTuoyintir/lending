<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv($request);
        }

        $customers = Customer::query()
            ->withCount('loans')
            ->withSum(['loans as outstanding_balance' => function ($q) {
                $q->whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue']);
            }], 'outstanding_balance')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('customer_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($request->sort, function ($query, $sort) {
                if ($sort === 'oldest') {
                    $query->oldest();
                } elseif ($sort === 'name_asc') {
                    $query->orderBy('first_name', 'asc');
                } elseif ($sort === 'name_desc') {
                    $query->orderBy('first_name', 'desc');
                } else {
                    $query->latest();
                }
            }, function ($query) {
                $query->latest();
            })
            ->paginate(20)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'id_type' => ['nullable', 'string', 'max:50'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:100'],
            'digital_address' => ['nullable', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'employer' => ['nullable', 'string', 'max:255'],
            'employment_address' => ['nullable', 'string'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'emergency_contact_address' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,blacklisted'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['customer_number'] = 'CUS-'.strtoupper(Str::random(8));

        $customer = Customer::create($validated);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer profile created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'loans.loanProduct',
            'payments.loan',
        ]);

        $totalLoansCount = $customer->loans->count();
        $activeLoansCount = $customer->loans->whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue'])->count();
        $totalBorrowed = $customer->loans->sum('principal_amount');
        $totalRepaid = $customer->payments->sum('amount');
        $totalInterest = $customer->loans->sum('interest_amount');
        $currentOutstanding = $customer->loans->whereIn('status', ['disbursed', 'active', 'partially_paid', 'overdue'])->sum('outstanding_balance');

        return view('customers.show', compact(
            'customer',
            'totalLoansCount',
            'activeLoansCount',
            'totalBorrowed',
            'totalRepaid',
            'totalInterest',
            'currentOutstanding'
        ));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'id_type' => ['nullable', 'string', 'max:50'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:100'],
            'digital_address' => ['nullable', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'employer' => ['nullable', 'string', 'max:255'],
            'employment_address' => ['nullable', 'string'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'emergency_contact_address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive,blacklisted'],
            'notes' => ['nullable', 'string'],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer profile updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->loans()->exists()) {
            return back()->with(
                'error',
                'This customer cannot be deleted because they have existing loan records.'
            );
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer profile deleted successfully.');
    }

    private function exportCsv(Request $request)
    {
        $customers = Customer::query()
            ->withCount('loans')
            ->get();

        $filename = 'customers_export_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Customer Number',
                'First Name',
                'Last Name',
                'Phone',
                'Email',
                'Occupation',
                'Employer',
                'Monthly Income (GHS)',
                'Loans Count',
                'Status',
                'Date Registered',
            ]);

            foreach ($customers as $c) {
                fputcsv($file, [
                    $c->customer_number,
                    $c->first_name,
                    $c->last_name,
                    $c->phone,
                    $c->email,
                    $c->occupation,
                    $c->employer,
                    $c->monthly_income,
                    $c->loans_count,
                    $c->status,
                    $c->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
