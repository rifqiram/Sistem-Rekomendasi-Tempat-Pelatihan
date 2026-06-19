<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_receive_token(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email', 'role'],
                ],
            ]);
    }

    public function test_admin_can_logout_and_token_is_invalidated(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Logout berhasil');
    }
}
