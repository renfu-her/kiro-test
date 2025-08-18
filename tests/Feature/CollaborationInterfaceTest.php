<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\TodoInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollaborationInterfaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_invitations_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/invitations');

        $response->assertStatus(200);
        $response->assertViewIs('invitations.index');
    }

    public function test_user_can_send_invitation_via_todo_page(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->post("/todos/{$todo->id}/invite", [
            'email' => $invitee->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('todo_invitations', [
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);
    }

    public function test_user_can_accept_invitation_via_interface(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        $invitation = TodoInvitation::create([
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($invitee)->post("/invitations/{$invitation->id}/accept");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertEquals('accepted', $invitation->fresh()->status);
        $this->assertTrue($todo->collaborators->contains($invitee));
    }

    public function test_user_can_reject_invitation_via_interface(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        $invitation = TodoInvitation::create([
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($invitee)->post("/invitations/{$invitation->id}/reject");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertEquals('rejected', $invitation->fresh()->status);
        $this->assertFalse($todo->collaborators->contains($invitee));
    }

    public function test_owner_can_remove_collaborator(): void
    {
        $owner = User::factory()->create();
        $collaborator = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        // Add as collaborator
        $todo->collaborators()->attach($collaborator->id);

        $response = $this->actingAs($owner)->delete("/todos/{$todo->id}/collaborators", [
            'user_id' => $collaborator->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertFalse($todo->fresh()->collaborators->contains($collaborator));
    }

    public function test_owner_can_cancel_pending_invitation(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        $invitation = TodoInvitation::create([
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($owner)->delete("/invitations/{$invitation->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('todo_invitations', ['id' => $invitation->id]);
    }

    public function test_non_owner_cannot_invite_collaborators(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->post("/todos/{$todo->id}/invite", [
            'email' => $invitee->email,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_cannot_process_others_invitations(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $otherUser = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        $invitation = TodoInvitation::create([
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($otherUser)->post("/invitations/{$invitation->id}/accept");

        $response->assertStatus(403);
    }
}
