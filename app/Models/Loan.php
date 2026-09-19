<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'loan_number',
        'customer_id',
        'loan_product_id',
        'principal_amount',
        'interest_rate',
        'interest_type',
        'interest_amount',
        'fees_amount',
        'penalty_amount',
        'total_payable',
        'amount_paid',
        'outstanding_balance',
        'duration',
        'repayment_frequency',
        'loan_date',
        'disbursement_date',
        'first_payment_date',
        'maturity_date',
        'status',
        'approved_by',
        'disbursed_by',
        'notes',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:4',
        'interest_amount' => 'decimal:2',
        'fees_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',

        'loan_date' => 'date',
        'disbursement_date' => 'date',
        'first_payment_date' => 'date',
        'maturity_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function loanFees()
    {
        return $this->hasMany(LoanFee::class);
    }

    public function agreement()
    {
        return $this->hasOne(LoanAgreement::class);
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function disbursedBy()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function interestCycles()
    {
        return $this->hasMany(LoanInterestCycle::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            'active',
            'partially_paid',
            'overdue',
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('status', [
            'overdue',
            'defaulted',
        ]);
    }

    public function scopeFullyPaid($query)
    {
        return $query->where('status', 'fully_paid');
    }
}
