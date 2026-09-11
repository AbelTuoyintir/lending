<?php

namespace Tests\Feature;

use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_loan_products(): void
    {
        $user = User::factory()->create();
        LoanProduct::create([
            'name' => 'Personal Loan',
            'code' => 'PL-001',
            'min_amount' => 100,
            'max_amount' => 5000,
            'interest_rate' => 5.5,
            'interest_type' => 'flat',
            'repayment_frequency' => 'monthly',
            'min_duration' => 1,
            'max_duration' => 12,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('loan-products.index'));

        $response->assertStatus(200);
        $response->assertSee('Personal Loan');
    }

    public function test_can_create_loan_product(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Business Loan',
            'code' => 'BL-001',
            'min_amount' => 500,
            'max_amount' => 20000,
            'interest_rate' => 10.0,
            'interest_type' => 'reducing_balance',
            'repayment_frequency' => 'monthly',
            'min_duration' => 3,
            'max_duration' => 24,
            'is_active' => '1',
        ];

        $response = $this->actingAs($user)->post(route('loan-products.store'), $data);

        $product = LoanProduct::where('code', 'BL-001')->first();
        $this->assertNotNull($product);
        $response->assertRedirect(route('loan-products.show', $product));
    }
}
