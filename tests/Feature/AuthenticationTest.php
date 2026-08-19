<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_user_can_authenticate_and_redirect_to_dashboard(): void
    {
        $role = Role::create(['name' => 'Administrateur', 'display_name' => 'Admin']);
        $user = User::factory()->create([
            'email' => 'admin@pfpt.cfpt.sn',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@pfpt.cfpt.sn',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $role = Role::create(['name' => 'Administrateur', 'display_name' => 'Admin']);
        $user = User::factory()->create([
            'email' => 'admin@pfpt.cfpt.sn',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
        ]);

        $this->post('/login', [
            'email' => 'admin@pfpt.cfpt.sn',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
