<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function show(Loan $loan)
    {
        $loan->load(['customer', 'payments', 'interestCycles']);
        return view('loans.statement', compact('loan'));
    }
}