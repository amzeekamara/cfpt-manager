<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\FormTemplate;
use App\Models\User;
use App\Models\Role;
use App\Models\CandidateProfile;
use App\Models\Seminar;
use App\Models\Cohort;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class PublicFormSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_form_does_not_overwrite_existing_candidate_profile_anonymously(): void
    {
        $role = Role::create(['name' => 'Candidat', 'display_name' => 'Candidat']);
        $victim = User::factory()->create([
            'email' => 'victim@example.com',
            'name' => 'Original Name',
            'role_id' => $role->id,
        ]);

        $profile = CandidateProfile::create([
            'user_id' => $victim->id,
            'first_names' => 'Original',
            'last_name' => 'Victim',
            'phone' => '+221770000000',
        ]);

        $cohort = Cohort::create([
            'title' => 'Session 2026',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);

        $seminar = Seminar::create([
            'cohort_id' => $cohort->id,
            'title' => 'Séminaire Électromécanique',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-15',
            'capacity' => 20,
            'status' => 'planned',
        ]);

        $admin = User::factory()->create();

        $form = FormTemplate::create([
            'title' => 'Formulaire d\'inscription',
            'share_token' => Str::random(32),
            'seminar_id' => $seminar->id,
            'is_active' => true,
            'fields_json' => [],
            'created_by' => $admin->id,
        ]);

        // Un attaquant soumet le formulaire avec l'e-mail de la victime
        $response = $this->post(route('public.form.store', ['token' => $form->share_token]), [
            'answers' => [
                'email' => 'victim@example.com',
                'prenom' => 'HackedFirstName',
                'nom' => 'HackedLastName',
                'sexe' => 'Masculin',
                'whatsapp' => '+9999999999',
            ],
        ]);

        $response->assertStatus(200);

        // Vérification : Les données personnelles de la victime restent INTACTES
        $profile->refresh();
        $this->assertEquals('Original', $profile->first_names);
        $this->assertEquals('Victim', $profile->last_name);
        $this->assertEquals('+221770000000', $profile->phone);
    }
}
