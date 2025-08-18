<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user for testing
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
        ]);
    }

    public function test_filament_login_page_accessible(): void
    {
        $response = $this->get('/backend/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get('/backend');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_users_resource(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get('/backend/users');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_todos_resource(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get('/backend/todos');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_invitations_resource(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get('/backend/todo-invitations');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_backend(): void
    {
        $response = $this->get('/backend');

        $response->assertRedirect('/backend/login');
    }

    public function test_guest_cannot_access_resources(): void
    {
        $response = $this->get('/backend/users');
        $response->assertRedirect('/backend/login');

        $response = $this->get('/backend/todos');
        $response->assertRedirect('/backend/login');

        $response = $this->get('/backend/todo-invitations');
        $response->assertRedirect('/backend/login');
    }
}
