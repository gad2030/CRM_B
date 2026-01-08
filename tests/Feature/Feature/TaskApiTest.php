<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_requires_auth(): void
    {
        $this->getJson('/api/tasks')
            ->assertStatus(401);
    }

    public function test_create_task_validation(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->postJson('/api/tasks', [], [
            'Authorization' => "Bearer {$token}"
        ])->assertStatus(422);
    }

    public function test_create_task_success(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->postJson('/api/tasks', [
            'title' => 'My Task',
            'status' => 'pending'
        ], [
            'Authorization' => "Bearer {$token}"
        ])->assertStatus(201)
          ->assertJsonPath('success', true);
    }
}