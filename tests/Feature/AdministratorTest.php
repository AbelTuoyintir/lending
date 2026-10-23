<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_index_only_lists_admin_users(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@fincore.com',
        ]);

        $customerRecord = Customer::create([
            'customer_number' => 'CUS-12345678',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '0240000000',
            'email' => 'customer@fincore.com',
            'status' => 'active',
        ]);

        $customerUser = User::factory()->create([
            'role' => 'customer',
            'customer_id' => $customerRecord->id,
            'name' => 'John Doe',
            'email' => 'customer@fincore.com',
        ]);

        $response = $this->actingAs($admin)->get(route('administrators.index'));

        $response->assertStatus(200);
        $response->assertSee('Admin User');
        $response->assertSee('admin@fincore.com');
        $response->assertDontSee('customer@fincore.com');
    }
}
