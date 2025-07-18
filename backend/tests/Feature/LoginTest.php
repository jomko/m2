<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ensure /api/login authenticates with valid credentials.
     */
    public function test_login_endpoint_authenticates_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret'),
        ]);

        $this->get('/sanctum/csrf-cookie');

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret',
        ], ['Referer' => 'http://localhost:5173']);

        $response->assertOk()
                 ->assertJsonStructure([
                     'user' => ['id', 'email'],
                 ])
                 ->assertJsonCount(1);

        $this->assertAuthenticated();
    }
}
