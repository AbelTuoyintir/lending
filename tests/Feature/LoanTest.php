<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\FinancialAccount;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_loan_and_approve_and_disburse(): void
    {
        $user = User::factory()->create();

        $customer = Customer::create([
            'customer_number' => 'CUS-10000001',
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'phone' => '0501112233',
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

        $account = FinancialAccount::create([
            'name' => 'Main Cash Vault',
            'account_number' => 'ACC-001',
            'type' => 'cash',
            'current_balance' => 10000.00,
            'is_active' => true,
        ]);

        $createResponse = $this->actingAs($user)->post(route('loans.store'), [
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 1000.00,
            'duration' => 6,
            'loan_date' => now()->toDateString(),
        ]);

        $loan = Loan::first();
        $this->assertNotNull($loan);
        $this->assertEquals('pending', $loan->status);

        // Approve
        $approveResponse = $this->actingAs($user)->post(route('loans.approve', $loan));
        $loan->refresh();
        $this->assertEquals('approved', $loan->status);

        // Disburse
        $disburseResponse = $this->actingAs($user)->post(route('loans.disburse', $loan), [
            'financial_account_id' => $account->id,
            'disbursement_date' => now()->toDateString(),
        ]);

        $loan->refresh();
        $account->refresh();
        $this->assertEquals('active', $loan->status);
        $this->assertEquals(9000.00, (float) $account->current_balance);
    }

    public function test_cannot_create_loan_if_customer_has_outstanding_loan(): void
    {
        $user = User::factory()->create();

        $customer = Customer::create([
            'customer_number' => 'CUS-10000002',
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'phone' => '0502223344',
            'status' => 'active',
        ]);

        $product = LoanProduct::create([
            'name' => 'Quick Cash',
            'code' => 'QC-002',
            'min_amount' => 100,
            'max_amount' => 5000,
            'interest_rate' => 5.0,
            'interest_type' => 'flat',
            'repayment_frequency' => 'monthly',
            'min_duration' => 1,
            'max_duration' => 12,
            'is_active' => true,
        ]);

        Loan::create([
            'loan_number' => 'LN-EXISTING-01',
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

        $response = $this->actingAs($user)->post(route('loans.store'), [
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 500.00,
            'duration' => 3,
            'loan_date' => now()->toDateString(),
        ]);

        $response->assertSessionHas('error', 'Cannot create a new loan for a customer with an unresolved outstanding loan.');
        $this->assertEquals(1, Loan::where('customer_id', $customer->id)->count());
    }

    public function test_can_create_loan_if_previous_loan_is_fully_paid_or_cancelled(): void
    {
        $user = User::factory()->create();

        $customer = Customer::create([
            'customer_number' => 'CUS-10000003',
            'first_name' => 'Charlie',
            'last_name' => 'Brown',
            'phone' => '0503334455',
            'status' => 'active',
        ]);

        $product = LoanProduct::create([
            'name' => 'Quick Cash',
            'code' => 'QC-003',
            'min_amount' => 100,
            'max_amount' => 5000,
            'interest_rate' => 5.0,
            'interest_type' => 'flat',
            'repayment_frequency' => 'monthly',
            'min_duration' => 1,
            'max_duration' => 12,
            'is_active' => true,
        ]);

        Loan::create([
            'loan_number' => 'LN-RESOLVED-01',
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 1000,
            'interest_rate' => 5,
            'interest_type' => 'flat',
            'interest_amount' => 50,
            'total_payable' => 1050,
            'amount_paid' => 1050,
            'outstanding_balance' => 0,
            'duration' => 6,
            'repayment_frequency' => 'monthly',
            'loan_date' => now()->subMonths(2)->toDateString(),
            'status' => 'fully_paid',
        ]);

        $response = $this->actingAs($user)->post(route('loans.store'), [
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 800.00,
            'duration' => 4,
            'loan_date' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, Loan::where('customer_id', $customer->id)->count());
    }
}
