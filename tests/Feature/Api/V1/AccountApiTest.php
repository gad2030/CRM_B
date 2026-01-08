<?php

namespace Tests\Feature\Api\V1;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        // Login to get token
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->token = $response->json('data.token');
    }

    public function test_can_list_accounts(): void
    {
        Account::factory()->count(3)->create(['owner_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/accounts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'name', 'industry', 'owner_id']
                    ]
                ],
                'message'
            ]);
    }

    public function test_can_create_account(): void
    {
        $accountData = [
            'name' => 'Test Account',
            'industry' => 'Technology',
            'website' => 'https://example.com',
            'phone' => '1234567890',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/accounts', $accountData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Test Account',
                ],
            ]);

        $this->assertDatabaseHas('accounts', [
            'name' => 'Test Account',
            'owner_id' => $this->user->id,
        ]);
    }

    public function test_can_show_account(): void
    {
        $account = Account::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/v1/accounts/{$account->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $account->id,
                    'name' => $account->name,
                ],
            ]);
    }

    public function test_can_update_account(): void
    {
        $account = Account::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/v1/accounts/{$account->id}", [
                'name' => 'Updated Account Name',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Updated Account Name',
                ],
            ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'Updated Account Name',
        ]);
    }

    public function test_can_delete_account(): void
    {
        $account = Account::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/v1/accounts/{$account->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
        ]);
    }

    public function test_cannot_update_other_users_account(): void
    {
        $otherUser = User::factory()->create();
        $account = Account::factory()->create(['owner_id' => $otherUser->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/v1/accounts/{$account->id}", [
                'name' => 'Unauthorized Update',
            ]);

        $response->assertStatus(403);
    }
}

