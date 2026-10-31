<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/login', [
                'email' => 'nobody@example.com',
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(302);
        }

        $response = $this->post('/login', [
            'email' => 'nobody@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }

    public function test_forgot_password_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/forgot-password', [
                'email' => 'nobody@example.com',
            ]);
            $response->assertStatus(302);
        }

        $response = $this->post('/forgot-password', [
            'email' => 'nobody@example.com',
        ]);

        $response->assertStatus(429);
    }
}
