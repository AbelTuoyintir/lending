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

    public function test_admin_can_search_customers_loans_and_payments_safely(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'customer_id' => null,
        ]);

        $customer = Customer::create([
            'customer_number' => 'CUS-9999',
            'first_name' => 'Kofi',
            'last_name' => 'Mensah',
            'phone' => '0240000000',
            'email' => 'kofi.mensah@example.com',
            'status' => 'active',
        ]);

        $product = LoanProduct::create([
            'code' => 'PEL-SEARCH',
            'name' => 'Search Test Loan Product',
            'min_amount' => 100,
            'max_amount' => 10000,
            'interest_rate' => 10,
            'interest_type' => 'flat',
            'min_duration' => 1,
            'max_duration' => 12,
            'repayment_frequency' => 'monthly',
        ]);

        $loan = Loan::create([
            'loan_number' => 'LN-SEARCH-01',
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 1000,
            'interest_rate' => 10,
            'interest_type' => 'flat',
            'interest_amount' => 100,
            'total_payable' => 1100,
            'amount_paid' => 100,
            'outstanding_balance' => 1000,
            'duration' => 6,
            'repayment_frequency' => 'monthly',
            'loan_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'payment_number' => 'PMT-SEARCH-01',
            'loan_id' => $loan->id,
            'customer_id' => $customer->id,
            'amount' => 100,
            'payment_method' => 'cash',
            'reference' => 'REF-SEARCH-01',
            'payment_date' => now(),
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('search', ['q' => 'Kofi']));

        $response->assertStatus(200);
        $response->assertSee('CUS-9999');
        $response->assertSee('LN-SEARCH-01');

        $responsePmt = $this->actingAs($admin)->get(route('search', ['q' => 'REF-SEARCH-01']));
        $responsePmt->assertStatus(200);
        $responsePmt->assertSee('PMT-SEARCH-01');
    }
}
