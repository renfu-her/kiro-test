<?php

namespace App\Services;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TodoService
{
    /**
     * Create a new todo for the user.
     */
    public function createTodo(array $data, User $user): Todo
    {
        return Todo::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Update an existing todo.
     */
    public function updateTodo(Todo $todo, array $data): Todo
    {
        $todo->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? $todo->description,
            'status' => $data['status'] ?? $todo->status,
        ]);

        return $todo->fresh();
    }

    /**
     * Delete a todo.
     */
    public function deleteTodo(Todo $todo): bool
    {
        return $todo->delete();
    }

    /**
     * Get all todos for a user (owned and collaborative).
     */
    public function getUserTodos(User $user): array
    {
        return [
            'owned' => $user->ownedTodos()->latest()->get(),
            'collaborative' => $user->collaborativeTodos()->latest()->get(),
        ];
    }

    /**
     * Toggle todo status between pending and completed.
     */
    public function toggleTodoStatus(Todo $todo): Todo
    {
        $newStatus = $todo->status === 'completed' ? 'pending' : 'completed';
        
        $todo->update(['status' => $newStatus]);
        
        return $todo->fresh();
    }
}