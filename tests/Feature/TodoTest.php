<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_todos_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/todos');

        $response->assertStatus(200);
        $response->assertViewIs('todos.index');
    }

    public function test_guest_cannot_view_todos(): void
    {
        $response = $this->get('/todos');

        $response->assertRedirect('/login');
    }

    public function test_user_can_create_todo(): void
    {
        $user = User::factory()->create();

        $todoData = [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'status' => 'pending',
        ];

        $response = $this->actingAs($user)->post('/todos', $todoData);

        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('/todos');
        $response->assertSessionHas('success');
    }

    public function test_user_can_view_own_todo(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/todos/{$todo->id}");

        $response->assertStatus(200);
        $response->assertViewIs('todos.show');
        $response->assertViewHas('todo', $todo);
    }

    public function test_user_can_update_own_todo(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'title' => 'Updated Todo',
            'description' => 'Updated Description',
            'status' => 'completed',
        ];

        $response = $this->actingAs($user)->put("/todos/{$todo->id}", $updateData);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Todo',
            'description' => 'Updated Description',
            'status' => 'completed',
        ]);

        $response->assertRedirect('/todos');
        $response->assertSessionHas('success');
    }

    public function test_user_can_delete_own_todo(): void
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/todos/{$todo->id}");

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
        $response->assertRedirect('/todos');
        $response->assertSessionHas('success');
    }

    public function test_user_cannot_view_others_todo(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get("/todos/{$todo->id}");

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_others_todo(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->put("/todos/{$todo->id}", [
            'title' => 'Hacked Todo',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_others_todo(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->delete("/todos/{$todo->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('todos', ['id' => $todo->id]);
    }
}
