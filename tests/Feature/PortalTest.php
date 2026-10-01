<?php

namespace Tests\Feature;

use App\Mail\PaymentReceiptMail;
use App\Models\Customer;
use App\Models\FinancialAccount;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FinancialAccount::create([
            'name' => 'Main Bank Account',
            'account_number' => 'ACC-001',
            'type' => 'bank',
            'bank_name' => 'GCB Bank',
            'current_balance' => 100000.00,
            'is_active' => true,
        ]);
    }

    protected function createCustomer(array $attributes = []): Customer
    {
        return Customer::create(array_merge([
            'customer_number' => 'CUS-'.rand(100000, 999999),
            'first_name' => 'Kwame',
            'last_name' => 'Mensah',
            'phone' => '024'.rand(1000000, 9999999),
            'email' => 'customer'.rand(100, 999).'@fincore.com',
            'status' => 'active',
        ], $attributes));
    }

    public function test_customer_can_register_portal_account(): void
    {
        $response = $this->post(route('register.post'), [
            'first_name' => 'Kwame',
            'last_name' => 'Mensah',
            'email' => 'kwame@example.com',
            'phone' => '0241112233',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'occupation' => 'Software Engineer',
            'monthly_income' => 5000,
            'address' => 'East Legon, Accra',
        ]);

        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticated();

        $customer = Customer::where('email', 'kwame@example.com')->first();
        $this->assertNotNull($customer);
        $this->assertEquals('Kwame Mensah', $customer->full_name);

        $user = User::where('email', 'kwame@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals($customer->id, $user->customer_id);
    }

    public function test_customer_can_view_portal_dashboard(): void
    {
        $customer = $this->createCustomer();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($user)->get(route('portal.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Customer Portal');
        $response->assertSee($customer->first_name);
    }

    public function test_customer_can_apply_for_loan(): void
    {
        $customer = $this->createCustomer();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $product = LoanProduct::create([
            'code' => 'PEL-01',
            'name' => 'Personal Express Loan',
            'min_amount' => 100,
            'max_amount' => 20000,
            'interest_rate' => 15,
            'interest_type' => 'flat',
            'min_duration' => 1,
            'max_duration' => 12,
            'repayment_frequency' => 'monthly',
        ]);

        $response = $this->actingAs($user)->post(route('portal.loans.store'), [
            'loan_product_id' => $product->id,
            'principal_amount' => 2000,
            'duration' => 6,
            'first_payment_date' => now()->addMonth()->format('Y-m-d'),
            'notes' => 'Emergency business facility',
        ]);

        $loan = Loan::where('customer_id', $customer->id)->first();
        $this->assertNotNull($loan);
        $this->assertEquals(2000, $loan->principal_amount);
        $this->assertEquals('pending', $loan->status);

        $response->assertRedirect(route('portal.loans.show', $loan));
    }

    public function test_customer_can_initialize_and_verify_paystack_payment(): void
    {
        Mail::fake();

        $customer = $this->createCustomer(['email' => 'customer@fincore.com']);
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $product = LoanProduct::create([
            'code' => 'QL-01',
            'name' => 'Quick Loan',
            'min_amount' => 100,
            'max_amount' => 50000,
            'interest_rate' => 10,
            'interest_type' => 'flat',
            'min_duration' => 1,
            'max_duration' => 12,
            'repayment_frequency' => 'monthly',
        ]);

        $loan = Loan::create([
            'loan_number' => 'LN-2026-0001',
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 1000,
            'interest_rate' => 10,
            'interest_type' => 'flat',
            'interest_amount' => 100,
            'total_payable' => 1100,
            'amount_paid' => 0,
            'outstanding_balance' => 1100,
            'duration' => 6,
            'repayment_frequency' => 'monthly',
            'loan_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $initResponse = $this->actingAs($user)->post(route('portal.payments.paystack.initialize'), [
            'loan_id' => $loan->id,
            'amount' => 500,
        ]);

        $initResponse->assertRedirect();

        $callbackResponse = $this->actingAs($user)->get(route('portal.payments.paystack.callback', [
            'reference' => session('paystack_latest_ref'),
            'loan_id' => $loan->id,
            'amount' => 500,
        ]));

        $payment = Payment::where('loan_id', $loan->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(500, $payment->amount);

        $loan->refresh();
        $this->assertEquals(600, $loan->outstanding_balance);
        $this->assertEquals('partially_paid', $loan->status);

        $callbackResponse->assertRedirect(route('portal.payments.receipt', $payment));

        Mail::assertSent(PaymentReceiptMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email);
        });
    }

    public function test_customer_can_view_printable_receipt_agreement_and_statement(): void
    {
        $customer = $this->createCustomer();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $product = LoanProduct::create([
            'code' => 'QL-02',
            'name' => 'Quick Loan',
            'min_amount' => 100,
            'max_amount' => 50000,
            'interest_rate' => 10,
            'interest_type' => 'flat',
            'min_duration' => 1,
            'max_duration' => 12,
            'repayment_frequency' => 'monthly',
        ]);

        $loan = Loan::create([
            'loan_number' => 'LN-2026-0002',
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'principal_amount' => 1000,
            'interest_rate' => 10,
            'interest_type' => 'flat',
            'interest_amount' => 100,
            'total_payable' => 1100,
            'amount_paid' => 500,
            'outstanding_balance' => 600,
            'duration' => 6,
            'repayment_frequency' => 'monthly',
            'loan_date' => now()->toDateString(),
            'status' => 'partially_paid',
        ]);

        $payment = Payment::create([
            'payment_number' => 'PAY-2026-0001',
            'loan_id' => $loan->id,
            'customer_id' => $customer->id,
            'amount' => 500,
            'payment_method' => 'card',
            'reference' => 'PAYSTACK_TEST_REF_123',
            'payment_date' => now(),
            'status' => 'completed',
        ]);

        // Test receipt
        $receiptResponse = $this->actingAs($user)->get(route('portal.payments.receipt', $payment));
        $receiptResponse->assertStatus(200);
        $receiptResponse->assertSee('PAY-2026-0001');

        // Test agreement
        $agreementResponse = $this->actingAs($user)->get(route('portal.loans.agreement', $loan));
        $agreementResponse->assertStatus(200);
        $agreementResponse->assertSee('Formal Lending Agreement');

        // Test statement
        $statementResponse = $this->actingAs($user)->get(route('portal.loans.statement', $loan));
        $statementResponse->assertStatus(200);
        $statementResponse->assertSee('Official Comprehensive Loan Account Statement');
    }

    public function test_customer_can_view_make_payment_page(): void
    {
        $customer = $this->createCustomer();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($user)->get(route('portal.payments.create'));
        $response->assertStatus(200);
        $response->assertSee('Initiate Loan Payment');
    }

    public function test_customer_can_view_statements_alias(): void
    {
        $customer = $this->createCustomer();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($user)->get(route('portal.statements'));
        $response->assertStatus(200);
        $response->assertSee('Financial Transaction History');
    }

    public function test_customer_can_view_support_and_notifications(): void
    {
        $customer = $this->createCustomer();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $this->actingAs($user)->get(route('portal.support'))->assertStatus(200)->assertSee('Customer Support');
        $this->actingAs($user)->get(route('portal.notifications'))->assertStatus(200)->assertSee('Notification Center');
    }

    public function test_customer_cannot_access_admin_management_routes(): void
    {
        $customer = $this->createCustomer();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($user)->get(route('customers.index'));
        $response->assertRedirect(route('portal.dashboard'));
    }
}
