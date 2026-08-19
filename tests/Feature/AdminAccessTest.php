<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_candidate_cannot_access_admin_dashboard(): void
    {
        $role = Role::create(['name' => 'Candidat', 'display_name' => 'Candidat']);
        $candidate = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($candidate)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $role = Role::create(['name' => 'Administrateur', 'display_name' => 'Admin']);
        $admin = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }
}
