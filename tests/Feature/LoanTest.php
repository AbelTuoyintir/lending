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
        $this->assertEquals(9000.00, (float)$account->current_balance);
    }
}
