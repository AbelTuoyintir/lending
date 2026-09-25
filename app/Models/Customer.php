<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_number',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'alternate_phone',
        'email',
        'date_of_birth',
        'gender',
        'id_type',
        'id_number',
        'address',
        'city',
        'region',
        'digital_address',
        'occupation',
        'employer',
        'employment_address',
        'monthly_income',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'emergency_contact_address',
        'status',
        'notes',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
        'date_of_birth' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name.' '.
            ($this->middle_name ? $this->middle_name.' ' : '').
            $this->last_name
        );
    }
}
