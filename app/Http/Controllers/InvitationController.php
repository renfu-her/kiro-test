<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\TodoInvitation;
use App\Services\InvitationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    use AuthorizesRequests;

    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    /**
     * Display pending invitations for the authenticated user.
     */
    public function index()
    {
        $pendingInvitations = $this->invitationService->getPendingInvitations(Auth::user());
        
        return view('invitations.index', compact('pendingInvitations'));
    }

    /**
     * Send an invitation to collaborate on a todo.
     */
    public function store(Request $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $invitation = $this->invitationService->sendInvitation($todo, $request->email);
            
            return redirect()->back()->with('success', '邀請已發送成功！');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Accept an invitation.
     */
    public function accept(TodoInvitation $invitation)
    {
        // Check if the invitation is for the authenticated user
        if ($invitation->invitee_id !== Auth::id()) {
            abort(403, '您沒有權限處理此邀請');
        }

        try {
            $this->invitationService->acceptInvitation($invitation);
            
            return redirect()->route('todos.show', $invitation->todo)
                ->with('success', '您已成功加入協作！');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject an invitation.
     */
    public function reject(TodoInvitation $invitation)
    {
        // Check if the invitation is for the authenticated user
        if ($invitation->invitee_id !== Auth::id()) {
            abort(403, '您沒有權限處理此邀請');
        }

        try {
            $this->invitationService->rejectInvitation($invitation);
            
            return redirect()->back()->with('success', '邀請已拒絕');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a pending invitation.
     */
    public function cancel(TodoInvitation $invitation)
    {
        // Check if the invitation was sent by the authenticated user
        if ($invitation->inviter_id !== Auth::id()) {
            abort(403, '您沒有權限取消此邀請');
        }

        try {
            $this->invitationService->cancelInvitation($invitation);
            
            return redirect()->back()->with('success', '邀請已取消');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove a collaborator from a todo.
     */
    public function removeCollaborator(Todo $todo, Request $request)
    {
        $this->authorize('delete', $todo); // Only owner can remove collaborators

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $collaborator = \App\Models\User::findOrFail($request->user_id);

        try {
            $this->invitationService->removeCollaborator($todo, $collaborator);
            
            return redirect()->back()->with('success', '協作者已移除');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
