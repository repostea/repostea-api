<?php

namespace Tests\Feature\Api;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\WithApiKeyHeaders;
use Tests\Traits\WithTenantContext;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithApiKeyHeaders, WithFaker, WithTenantContext;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTenant();
    }

    public function test_user_can_register()
    {
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJsonWithKey('/api/v1/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'karma',
                ],
                'access_token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'tenant_id' => $this->tenant->uuid,
        ]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJsonWithKey('/api/v1/auth/register', [
            'username' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'not-matching',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'email', 'password']);
    }

    public function test_user_can_login()
    {

        $user = User::factory()->forTenant($this->tenant)->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJsonWithKey('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'karma',
                ],
                'access_token',
                'token_type',
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->forTenant($this->tenant)->create();

        $response = $this->postJsonWithKey('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->forTenant($this->tenant)->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders(
            $this->withAuthHeaders($token)
        )->postJson('/api/v1/auth/logout');

        $response->assertOk()
            ->assertJson([
                'message' => 'Logged out successfully',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_user_can_get_profile()
    {
        $user = User::factory()->forTenant($this->tenant)->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders(
            $this->withAuthHeaders($token)
        )->getJson('/api/v1/auth/user');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'karma',
                    'created_at',
                ],
            ]);
    }

    public function test_user_from_another_tenant_cannot_login()
    {
        $otherTenant = $this->setupAnotherTenant();

        $otherUser = User::factory()->forTenant($otherTenant)->create([
            'email' => 'other@example.com',
            'password' => Hash::make('password123'),
        ]);

        Tenant::setCurrentTenant($this->tenant);

        $response = $this->postJsonWithKey('/api/v1/auth/login', [
            'email' => 'other@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }
}
