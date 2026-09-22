<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use Illuminate\Http\Request;

class FinancialAccountController extends Controller
{
    public function index()
    {
        $financialAccounts = FinancialAccount::query()->latest()->paginate(20);

        return view('financial-accounts.index', compact('financialAccounts'));
    }

    public function create()
    {
        return view('financial-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255', 'unique:financial_accounts,account_number'],
            'type' => ['required', 'in:cash,bank,mobile_money,equity,revenue,expense,asset,liability'],
            'current_balance' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        FinancialAccount::create($validated);

        return redirect()->route('financial-accounts.index')->with('success', 'Financial account created successfully.');
    }
}
