<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\TodoInvitation;
use App\Models\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    protected $invitationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invitationService = new InvitationService();
    }

    public function test_user_can_send_invitation(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);

        $invitation = $this->invitationService->sendInvitation($todo, $invitee->email);

        $this->assertDatabaseHas('todo_invitations', [
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);

        $this->assertEquals('pending', $invitation->status);
    }

    public function test_cannot_invite_nonexistent_user(): void
    {
        $owner = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('找不到此電子郵件的用戶');

        $this->invitationService->sendInvitation($todo, 'nonexistent@example.com');
    }

    public function test_cannot_invite_self(): void
    {
        $owner = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('不能邀請自己');

        $this->invitationService->sendInvitation($todo, $owner->email);
    }

    public function test_cannot_invite_existing_collaborator(): void
    {
        $owner = User::factory()->create();
        $collaborator = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        // Add as collaborator first
        $todo->collaborators()->attach($collaborator->id);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('此用戶已經是協作者');

        $this->invitationService->sendInvitation($todo, $collaborator->email);
    }

    public function test_cannot_send_duplicate_invitation(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);

        // Send first invitation
        $this->invitationService->sendInvitation($todo, $invitee->email);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('已經向此用戶發送過邀請');

        // Try to send again
        $this->invitationService->sendInvitation($todo, $invitee->email);
    }

    public function test_user_can_accept_invitation(): void
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

        $result = $this->invitationService->acceptInvitation($invitation);

        $this->assertTrue($result);
        $this->assertEquals('accepted', $invitation->fresh()->status);
        $this->assertTrue($todo->collaborators->contains($invitee));
    }

    public function test_user_can_reject_invitation(): void
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

        $result = $this->invitationService->rejectInvitation($invitation);

        $this->assertTrue($result);
        $this->assertEquals('rejected', $invitation->fresh()->status);
        $this->assertFalse($todo->collaborators->contains($invitee));
    }

    public function test_cannot_process_non_pending_invitation(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        $invitation = TodoInvitation::create([
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'accepted',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('此邀請已經被處理過');

        $this->invitationService->acceptInvitation($invitation);
    }

    public function test_can_remove_collaborator(): void
    {
        $owner = User::factory()->create();
        $collaborator = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        // Add as collaborator
        $todo->collaborators()->attach($collaborator->id);
        $this->assertTrue($todo->collaborators->contains($collaborator));

        $result = $this->invitationService->removeCollaborator($todo, $collaborator);

        $this->assertTrue($result);
        $this->assertFalse($todo->fresh()->collaborators->contains($collaborator));
    }

    public function test_get_pending_invitations(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $owner->id]);
        
        TodoInvitation::create([
            'todo_id' => $todo->id,
            'inviter_id' => $owner->id,
            'invitee_id' => $invitee->id,
            'status' => 'pending',
        ]);

        $pendingInvitations = $this->invitationService->getPendingInvitations($invitee);

        $this->assertCount(1, $pendingInvitations);
        $this->assertEquals('pending', $pendingInvitations->first()->status);
    }
}
