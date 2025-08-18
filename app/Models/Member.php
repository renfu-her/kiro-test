<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the todos owned by the member.
     */
    public function ownedTodos(): HasMany
    {
        return $this->hasMany(Todo::class, 'member_id');
    }

    /**
     * Get the todos the member collaborates on.
     */
    public function collaborativeTodos(): BelongsToMany
    {
        return $this->belongsToMany(Todo::class, 'todo_collaborators', 'member_id', 'todo_id');
    }

    /**
     * Get the invitations sent by the member.
     */
    public function sentInvitations(): HasMany
    {
        return $this->hasMany(TodoInvitation::class, 'inviter_id');
    }

    /**
     * Get the invitations received by the member.
     */
    public function receivedInvitations(): HasMany
    {
        return $this->hasMany(TodoInvitation::class, 'invitee_id');
    }
}
