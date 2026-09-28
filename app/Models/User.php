<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'customer_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isCustomer(): bool
    {
        return ! is_null($this->customer_id);
    }

    public function isAdmin(): bool
    {
        return is_null($this->customer_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Loan Management Relationships
    |--------------------------------------------------------------------------
    */

    public function approvedLoans()
    {
        return $this->hasMany(Loan::class, 'approved_by');
    }

    public function disbursedLoans()
    {
        return $this->hasMany(Loan::class, 'disbursed_by');
    }

    public function receivedPayments()
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    public function financialTransactions()
    {
        return $this->hasMany(
            FinancialTransaction::class,
            'created_by'
        );
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
