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

        $loginResponse->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticatedAs($customerUser);
    }

    public function test_customer_update_enforces_unique_email_and_syncs_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $customer1 = Customer::create([
            'customer_number' => 'CUS-10000001',
            'first_name' => 'Alice',
            'last_name' => 'One',
            'phone' => '0240000001',
            'email' => 'alice@example.com',
            'status' => 'active',
        ]);
        $user1 = User::create([
            'name' => 'Alice One',
            'email' => 'alice@example.com',
            'password' => 'password123',
            'role' => 'customer',
            'customer_id' => $customer1->id,
        ]);

        $customer2 = Customer::create([
            'customer_number' => 'CUS-10000002',
            'first_name' => 'Bob',
            'last_name' => 'Two',
            'phone' => '0240000002',
            'email' => 'bob@example.com',
            'status' => 'active',
        ]);

        // Attemping to update customer1 email to customer2's email should fail validation
        $response = $this->actingAs($admin)->put(route('customers.update', $customer1), [
            'first_name' => 'Alice',
            'last_name' => 'One',
            'phone' => '0240000001',
            'email' => 'bob@example.com',
            'status' => 'active',
        ]);
        $response->assertSessionHasErrors('email');

        // Updating customer1 with a valid new unique email should update both customer and user record
        $responseSuccess = $this->actingAs($admin)->put(route('customers.update', $customer1), [
            'first_name' => 'Alice',
            'last_name' => 'Updated',
            'phone' => '0240000001',
            'email' => 'alice.new@example.com',
            'status' => 'active',
        ]);
        $responseSuccess->assertRedirect(route('customers.show', $customer1));

        $this->assertEquals('alice.new@example.com', $customer1->fresh()->email);
        $this->assertEquals('alice.new@example.com', $user1->fresh()->email);
        $this->assertEquals('Alice Updated', $user1->fresh()->name);
    }
}
