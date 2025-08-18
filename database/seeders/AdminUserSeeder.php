<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => '系統管理員',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Create some test users and todos for demonstration
        $users = User::factory(5)->create();
        
        foreach ($users as $user) {
            // Create some todos for each user
            $todos = \App\Models\Todo::factory(rand(2, 5))->create([
                'user_id' => $user->id,
            ]);

            // Create some collaborations
            foreach ($todos as $todo) {
                if (rand(0, 1)) {
                    $collaborator = $users->where('id', '!=', $user->id)->random();
                    $todo->collaborators()->attach($collaborator->id);
                }
            }
        }

        // Create some invitations
        $allTodos = \App\Models\Todo::all();
        foreach ($allTodos->take(10) as $todo) {
            $invitee = $users->where('id', '!=', $todo->user_id)->random();
            
            if (!$todo->collaborators->contains($invitee)) {
                \App\Models\TodoInvitation::create([
                    'todo_id' => $todo->id,
                    'inviter_id' => $todo->user_id,
                    'invitee_id' => $invitee->id,
                    'status' => collect(['pending', 'accepted', 'rejected'])->random(),
                ]);
            }
        }
    }
}
