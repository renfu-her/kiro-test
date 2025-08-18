<?php

namespace App\Services;

use App\Models\Todo;
use App\Models\TodoInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class InvitationService
{
    /**
     * Send an invitation to collaborate on a todo.
     */
    public function sendInvitation(Todo $todo, string $email): TodoInvitation
    {
        // Find the user by email
        $invitee = User::where('email', $email)->first();
        
        if (!$invitee) {
            throw new \Exception('找不到此電子郵件的用戶');
        }

        // Check if user is trying to invite themselves
        if ($invitee->id === $todo->user_id) {
            throw new \Exception('不能邀請自己');
        }

        // Check if user is already a collaborator
        if ($todo->collaborators->contains($invitee)) {
            throw new \Exception('此用戶已經是協作者');
        }

        // Check if there's already a pending invitation
        $existingInvitation = TodoInvitation::where('todo_id', $todo->id)
            ->where('invitee_id', $invitee->id)
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation) {
            throw new \Exception('已經向此用戶發送過邀請');
        }

        // Create the invitation
        return TodoInvitation::create([
            'todo_id' => $todo->id,
            'inviter_id' => $todo->user_id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Accept an invitation.
     */
    public function acceptInvitation(TodoInvitation $invitation): bool
    {
        if ($invitation->status !== 'pending') {
            throw new \Exception('此邀請已經被處理過');
        }

        // Update invitation status
        $invitation->update(['status' => 'accepted']);

        // Add user as collaborator
        $invitation->todo->collaborators()->attach($invitation->invitee_id);

        return true;
    }

    /**
     * Reject an invitation.
     */
    public function rejectInvitation(TodoInvitation $invitation): bool
    {
        if ($invitation->status !== 'pending') {
            throw new \Exception('此邀請已經被處理過');
        }

        $invitation->update(['status' => 'rejected']);

        return true;
    }

    /**
     * Get pending invitations for a user.
     */
    public function getPendingInvitations(User $user): Collection
    {
        return TodoInvitation::where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->with(['todo', 'inviter'])
            ->latest()
            ->get();
    }

    /**
     * Get sent invitations for a todo.
     */
    public function getSentInvitations(Todo $todo): Collection
    {
        return TodoInvitation::where('todo_id', $todo->id)
            ->with(['invitee'])
            ->latest()
            ->get();
    }

    /**
     * Cancel a pending invitation.
     */
    public function cancelInvitation(TodoInvitation $invitation): bool
    {
        if ($invitation->status !== 'pending') {
            throw new \Exception('只能取消待處理的邀請');
        }

        $invitation->delete();

        return true;
    }

    /**
     * Remove a collaborator from a todo.
     */
    public function removeCollaborator(Todo $todo, User $collaborator): bool
    {
        // Check if user is actually a collaborator
        if (!$todo->collaborators->contains($collaborator)) {
            throw new \Exception('此用戶不是協作者');
        }

        // Remove from collaborators
        $todo->collaborators()->detach($collaborator->id);

        return true;
    }
}