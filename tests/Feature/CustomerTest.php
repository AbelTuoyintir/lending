<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customers(): void
    {
        $user = User::factory()->create();
        Customer::create([
            'customer_number' => 'CUS-12345678',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '0240000000',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertSee('John');
    }

    public function test_can_create_customer(): void
    {
        $user = User::factory()->create();

        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '0241112233',
            'status' => 'active',
        ];

        $response = $this->actingAs($user)->post(route('customers.store'), $data);

        $customer = Customer::where('first_name', 'Jane')->first();
        $this->assertNotNull($customer);
        $response->assertRedirect(route('customers.show', $customer));
    }
}
