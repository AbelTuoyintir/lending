<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_customers_loans_and_payments(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $customer = Customer::create([
            'customer_number' => 'CUS-ALICE123',
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'phone' => '0240000000',
            'email' => 'alice@example.com',
            'status' => 'active',
        ]);

        $product = LoanProduct::create([
            'name' => 'Quick Cash',
            'code' => 'QC-001',
            'min_amount' => 100,
            'max_amount' => 5000,
            'interest_rate' => 5.0,
            'interest_type' => 'flat',
            'repayment_frequency' => 'monthly',
            'min_duration' => 1,
            'max_duration' => 12,
            'is_active' => true,
        ]);

        $loan = Loan::create([
            'loan_number' => 'LN-ALICE999',
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 1000,
            'interest_rate' => 5,
            'interest_type' => 'flat',
            'interest_amount' => 50,
            'total_payable' => 1050,
            'amount_paid' => 0,
            'outstanding_balance' => 1050,
            'duration' => 6,
            'repayment_frequency' => 'monthly',
            'loan_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'payment_number' => 'PMT-ALICE555',
            'loan_id' => $loan->id,
            'customer_id' => $customer->id,
            'amount' => 100,
            'payment_method' => 'cash',
            'reference' => 'REF-ALICE-123',
            'payment_date' => now()->toDateString(),
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('search', ['q' => 'ALICE']));

        $response->assertOk()
            ->assertViewHas('customers', function ($customers) use ($customer) {
                return $customers->contains('id', $customer->id);
            })
            ->assertViewHas('loans', function ($loans) use ($loan) {
                return $loans->contains('id', $loan->id);
            })
            ->assertViewHas('payments', function ($payments) use ($payment) {
                return $payments->contains('id', $payment->id);
            });
    }
}
