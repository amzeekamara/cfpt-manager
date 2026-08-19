<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_role(): void
    {
        $role = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrateur',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->assertEquals('admin', $user->role->name);
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('candidate'));
    }
}
