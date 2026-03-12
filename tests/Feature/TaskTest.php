<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $payload = [
            'title' => 'Prepare technical test',
            'description' => 'Complete Laravel API project',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => '2026-03-12',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'due_date',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Prepare technical test',
            'status' => 'pending',
            'priority' => 'high',
        ]);
    }

    public function test_authenticated_user_can_list_only_their_tasks(): void
    {
        $authenticatedUser = User::factory()->create();
        $otherUser = User::factory()->create();

        Task::factory()->create([
            'user_id' => $authenticatedUser->id,
            'title' => 'My task',
        ]);

        Task::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Other user task',
        ]);

        Sanctum::actingAs($authenticatedUser);

        $response = $this->getJson('/api/tasks');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'My task',
            ])
            ->assertJsonMissing([
                'title' => 'Other user task',
            ]);
    }

    public function test_authenticated_user_can_update_their_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Initial title',
            'status' => 'pending',
        ]);

        Sanctum::actingAs($user);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated title',
            'status' => 'in_progress',
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'Updated title',
                'status' => 'in_progress',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated title',
            'status' => 'in_progress',
        ]);
    }

    public function test_authenticated_user_can_delete_their_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Task deleted successfully.',
            ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}