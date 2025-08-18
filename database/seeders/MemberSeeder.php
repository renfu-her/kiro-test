<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Todo;
use App\Models\TodoInvitation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test member
        if (!Member::where('email', 'member@example.com')->exists()) {
            Member::create([
                'name' => '測試會員',
                'email' => 'member@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Create some test members and todos for demonstration
        $members = Member::factory(5)->create();
        
        foreach ($members as $member) {
            // Create some todos for each member
            $todos = Todo::factory(rand(2, 5))->create([
                'member_id' => $member->id,
                'user_id' => null, // Clear user_id since we're using member_id
            ]);

            // Create some collaborations
            foreach ($todos as $todo) {
                if (rand(0, 1)) {
                    $collaborator = $members->where('id', '!=', $member->id)->random();
                    $todo->memberCollaborators()->attach($collaborator->id);
                }
            }
        }

        // Create some invitations
        $allTodos = Todo::whereNotNull('member_id')->get();
        foreach ($allTodos->take(10) as $todo) {
            $invitee = $members->where('id', '!=', $todo->member_id)->random();
            
            if (!$todo->memberCollaborators->contains($invitee)) {
                TodoInvitation::create([
                    'todo_id' => $todo->id,
                    'inviter_id' => $todo->member_id,
                    'invitee_id' => $invitee->id,
                    'inviter_member_id' => $todo->member_id,
                    'invitee_member_id' => $invitee->id,
                    'status' => collect(['pending', 'accepted', 'rejected'])->random(),
                ]);
            }
        }
    }
}
