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

    public function test_cannot_create_administrator_with_existing_customer_email(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Customer::create([
            'customer_number' => 'CUS-87654321',
            'first_name' => 'Existing',
            'last_name' => 'Borrower',
            'phone' => '0249998877',
            'email' => 'borrower@example.com',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('administrators.store'), [
            'name' => 'New Admin',
            'email' => 'borrower@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', [
            'name' => 'New Admin',
            'email' => 'borrower@example.com',
        ]);
    }
}
