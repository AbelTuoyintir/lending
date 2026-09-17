<?php

namespace App\Http\Controllers;

use App\Models\LoanProduct;
use Illuminate\Http\Request;

class LoanProductController extends Controller
{
    /**
     * Display a listing of the loan products.
     */
    public function index(Request $request)
    {
        $loanProducts = LoanProduct::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->has('is_active') && $request->is_active !== null && $request->is_active !== '', function ($query) use ($request) {
                $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('loan-products.index', compact('loanProducts'));
    }

    /**
     * Show the form for creating a new loan product.
     */
    public function create()
    {
        return view('loan-products.create');
    }

    /**
     * Store a newly created loan product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:loan_products,code'],
            'description' => ['nullable', 'string'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['required', 'numeric', 'gte:min_amount'],
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'interest_type' => ['required', 'in:flat,reducing_balance'],
            'repayment_frequency' => ['required', 'in:daily,weekly,biweekly,monthly'],
            'min_duration' => ['required', 'integer', 'min:1'],
            'max_duration' => ['required', 'integer', 'gte:min_duration'],
            'is_active' => ['nullable'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        $loanProduct = LoanProduct::create($validated);

        return redirect()
            ->route('loan-products.show', $loanProduct)
            ->with('success', 'Loan product created successfully.');
    }

    /**
     * Display the specified loan product.
     */
    public function show(LoanProduct $loanProduct)
    {
        $loanProduct->loadCount('loans');

        return view('loan-products.show', compact('loanProduct'));
    }

    /**
     * Show the form for editing the specified loan product.
     */
    public function edit(LoanProduct $loanProduct)
    {
        return view('loan-products.edit', compact('loanProduct'));
    }

    /**
     * Update the specified loan product in storage.
     */
    public function update(Request $request, LoanProduct $loanProduct)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:loan_products,code,'.$loanProduct->id],
            'description' => ['nullable', 'string'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['required', 'numeric', 'gte:min_amount'],
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'interest_type' => ['required', 'in:flat,reducing_balance'],
            'repayment_frequency' => ['required', 'in:daily,weekly,biweekly,monthly'],
            'min_duration' => ['required', 'integer', 'min:1'],
            'max_duration' => ['required', 'integer', 'gte:min_duration'],
            'is_active' => ['nullable'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $loanProduct->update($validated);

        return redirect()
            ->route('loan-products.show', $loanProduct)
            ->with('success', 'Loan product updated successfully.');
    }

    /**
     * Remove the specified loan product from storage.
     */
    public function destroy(LoanProduct $loanProduct)
    {
        if ($loanProduct->loans()->exists()) {
            return back()->with(
                'error',
                'This loan product cannot be deleted because it has associated loans.'
            );
        }

        $loanProduct->delete();

        return redirect()
            ->route('loan-products.index')
            ->with('success', 'Loan product deleted successfully.');
    }
}
