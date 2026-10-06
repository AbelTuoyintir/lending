<?php

namespace Tests\Feature;

use App\Mail\CustomerWelcomeMail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customers(): void
    {
        $admin = User::factory()->create();
        Customer::create([
            'customer_number' => 'CUS-12345678',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '0240000000',
            'email' => 'john.doe@example.com',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertSee('John');
    }

    public function test_can_create_customer_creates_user_and_sends_credentials(): void
    {
        Mail::fake();

        $admin = User::factory()->create();

        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '0241112233',
            'email' => 'jane.smith@example.com',
            'status' => 'active',
        ];

        $response = $this->actingAs($admin)->post(route('customers.store'), $data);

        $customer = Customer::where('email', 'jane.smith@example.com')->first();
        $this->assertNotNull($customer);
        $response->assertRedirect(route('customers.show', $customer));

        // Assert user account was created and linked to customer
        $customerUser = User::where('email', 'jane.smith@example.com')->first();
        $this->assertNotNull($customerUser);
        $this->assertEquals($customer->id, $customerUser->customer_id);
        $this->assertEquals('Jane Smith', $customerUser->name);

        // Assert CustomerWelcomeMail was dispatched to the customer's email
        Mail::assertSent(CustomerWelcomeMail::class, function ($mail) use ($customerUser) {
            return $mail->hasTo($customerUser->email) && ! empty($mail->plainTextPassword);
        });

        // Extract plain password from sent mail to verify customer can log in
        $sentMail = Mail::sent(CustomerWelcomeMail::class)->first();
        $generatedPassword = $sentMail->plainTextPassword;

        // Logout admin session before attempting login as customer
        $this->post('/logout');

        // Verify customer can log in using their email and the generated password
        $loginResponse = $this->post('/login', [
            'email' => 'jane.smith@example.com',
            'password' => $generatedPassword,
        ]);

        $loginResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($customerUser);
    }
}
