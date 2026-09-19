<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_record_payment(): void
    {
        $user = User::factory()->create();

        $customer = Customer::create([
            'customer_number' => 'CUS-20000001',
            'first_name' => 'Bob',
            'last_name' => 'Marley',
            'phone' => '0200000000',
            'status' => 'active',
        ]);

        $product = LoanProduct::create([
            'name' => 'Micro Loan',
            'code' => 'ML-001',
            'min_amount' => 50,
            'max_amount' => 1000,
            'interest_rate' => 2.0,
            'interest_type' => 'flat',
            'repayment_frequency' => 'monthly',
            'min_duration' => 1,
            'max_duration' => 6,
            'is_active' => true,
        ]);

        $loan = Loan::create([
            'loan_number' => 'LN-20260101-00001',
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 500.00,
            'interest_rate' => 2.0,
            'interest_type' => 'flat',
            'total_payable' => 500.00,
            'amount_paid' => 0.00,
            'outstanding_balance' => 500.00,
            'duration' => 3,
            'repayment_frequency' => 'monthly',
            'loan_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post(route('payments.store', $loan), [
            'amount' => 200.00,
            'payment_method' => 'cash',
            'reference' => 'REF-PAY-001',
            'payment_date' => now()->toDateString(),
        ]);

        $loan->refresh();
        $this->assertEquals(200.00, (float) $loan->amount_paid);
        $this->assertEquals(300.00, (float) $loan->outstanding_balance);
        $this->assertEquals('partially_paid', $loan->status);
    }
}
