<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeminarAndCohortManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Department $dept;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'Administrateur']);
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->dept = Department::firstOrCreate(['name' => 'Informatique', 'code' => 'INFO', 'is_active' => true]);
    }

    public function test_admin_can_create_seminar_successfully(): void
    {
        $cohort = Cohort::create([
            'title' => 'Édition 2026',
            'total_budget' => 50000000,
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.seminars.store'), [
            'title' => 'Nouveau Séminaire Cloud & DevOps',
            'departments' => [$this->dept->id],
            'cohort_id' => $cohort->id,
            'start_date' => now()->addDays(12)->format('Y-m-d\TH:i'),
            'end_date' => now()->addDays(20)->format('Y-m-d\TH:i'),
            'max_participants' => 25,
            'description' => 'Formation intensive sur AWS et Kubernetes',
            'status' => 'active',
            'topics' => ['Architecture Microservices', 'CI/CD GitLab', 'Sécurité Cloud'],
        ]);

        $response->assertRedirect(route('admin.seminars'));
        $this->assertDatabaseHas('seminars', [
            'title' => 'Nouveau Séminaire Cloud & DevOps',
            'cohort_id' => $cohort->id,
            'max_participants' => 25,
            'status' => 'active',
        ]);

        $seminar = Seminar::where('title', 'Nouveau Séminaire Cloud & DevOps')->first();
        $this->assertCount(1, $seminar->departments);
        $this->assertCount(3, $seminar->topics);
    }

    public function test_admin_can_reactivate_archived_cohort(): void
    {
        $cohort = Cohort::create([
            'title' => 'Édition 2024 Historique',
            'total_budget' => 30000000,
            'start_date' => now()->subYears(2),
            'end_date' => now()->subYears(2)->addDays(20),
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.cohorts.toggle', $cohort));

        $response->assertSessionHas('success');
        $this->assertEquals('active', $cohort->fresh()->status);
    }

    public function test_seminar_creation_fails_without_departments(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.seminars.store'), [
            'title' => 'Séminaire Invalide',
            'departments' => [],
            'start_date' => now()->addDays(5)->format('Y-m-d\TH:i'),
            'end_date' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'max_participants' => 20,
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors(['departments']);
    }
}
