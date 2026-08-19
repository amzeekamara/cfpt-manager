<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CandidateStatusNotification;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_submit_registration(): void
    {
        Storage::fake('public');
        Notification::fake();

        $role = Role::create(['name' => 'Candidat', 'display_name' => 'Candidat']);

        $cohort = Cohort::create([
            'title' => 'Édition 2026',
            'start_date' => now()->addDays(30)->toDateString(),
            'end_date' => now()->addDays(45)->toDateString(),
            'status' => 'active',
        ]);

        $seminar = Seminar::create([
            'cohort_id' => $cohort->id,
            'title' => 'Séminaire Informatique Industrielle',
            'start_date' => now()->addDays(30)->toDateString(),
            'end_date' => now()->addDays(45)->toDateString(),
            'capacity' => 25,
            'status' => 'active',
        ]);

        $response = $this->post(route('public.registration.store'), [
            'seminar_id' => $seminar->id,
            'first_names' => 'Mamadou',
            'last_name' => 'Diallo',
            'email' => 'mamadou.diallo@test.sn',
            'birth_date' => '1995-05-12',
            'birth_place' => 'Dakar',
            'gender' => 'M',
            'nationality' => 'Sénégalaise',
            'residence_country' => 'Sénégal',
            'family_status' => 'single',
            'phone' => '+221771234567',
            'address' => 'Dakar Plateau',
            'emergency_name' => 'Aminata Diallo',
            'emergency_phone' => '+221779876543',
            'emergency_relation' => 'Sœur',
            'highest_degree' => 'Master en Informatique',
            'current_profession' => 'Ingénieur Réseaux',
            'current_employer' => 'Entreprise Tech',
            'training_utility' => 'Renforcer mes compétences professionnelles',
            'accommodation_preference' => 'hotel',
            'cv' => UploadedFile::fake()->create('mon_cv.pdf', 200, 'application/pdf'),
            'official_intro' => UploadedFile::fake()->create('intro.pdf', 200, 'application/pdf'),
        ]);

        $response->assertRedirect(route('welcome'));
        $this->assertDatabaseHas('users', [
            'email' => 'mamadou.diallo@test.sn',
            'name' => 'Mamadou Diallo',
        ]);
        $this->assertDatabaseHas('registrations', [
            'seminar_id' => $seminar->id,
            'status' => 'pending',
        ]);

        $user = User::where('email', 'mamadou.diallo@test.sn')->first();
        Notification::assertSentTo($user, CandidateStatusNotification::class);
    }
}
