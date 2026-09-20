<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanInterestCycle extends Model
{
    protected $fillable = [
        'loan_id',
        'cycle_number',
        'cycle_date',
        'opening_balance',
        'interest_rate',
        'interest_amount',
        'payment_amount',
        'closing_balance',
        'status',
    ];

    protected $casts = [
        'cycle_date' => 'date',

        'opening_balance' => 'decimal:2',

        'interest_rate' => 'decimal:4',

        'interest_amount' => 'decimal:2',

        'payment_amount' => 'decimal:2',

        'closing_balance' => 'decimal:2',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function getInterestChargedAttribute(): float
    {
        return (float) ($this->attributes['interest_amount'] ?? 0);
    }

    public function getYearMonthAttribute(): string
    {
        if ($this->cycle_date) {
            return $this->cycle_date->format('F Y');
        }

        return 'Cycle #'.$this->cycle_number;
    }
}
