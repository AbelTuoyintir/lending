<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_reset_password_without_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'target@example.com',
            'password' => Hash::make('originalPassword123!'),
        ]);

        $response = $this->post('/reset-password', [
            'token' => 'invalid-or-fake-token',
            'email' => 'target@example.com',
            'password' => 'newPassword123!',
            'password_confirmation' => 'newPassword123!',
        ]);

        $response->assertSessionHasErrors('email');

        $this::assertTrue(Hash::check('originalPassword123!', $user->fresh()->password));
    }

    public function test_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('originalPassword123!'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'user@example.com',
            'password' => 'newSecurePassword123!',
            'password_confirmation' => 'newSecurePassword123!',
        ]);

        $response->assertRedirect(route('login'));

        $this::assertTrue(Hash::check('newSecurePassword123!', $user->fresh()->password));
    }
}
