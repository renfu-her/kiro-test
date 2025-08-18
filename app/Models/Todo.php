<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'member_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the owner of the todo (User for admin, Member for frontend).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the member owner of the todo.
     */
    public function memberOwner(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Get the collaborators for the todo.
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'todo_collaborators');
    }

    /**
     * Get the member collaborators for the todo.
     */
    public function memberCollaborators(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'todo_collaborators', 'todo_id', 'member_id');
    }

    /**
     * Get the invitations for the todo.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TodoInvitation::class);
    }
}
