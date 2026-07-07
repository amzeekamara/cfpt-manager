<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FormTemplate;
use App\Models\FormSubmission;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\Enrollment;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FormController extends Controller
{
    /**
     * Display the public shareable form
     */
    public function show(string $token)
    {
        $form = FormTemplate::where('share_token', $token)
            ->where('is_active', true)
            ->with('seminar')
            ->firstOrFail();

        // Resolve data_source options for select/radio/checkbox fields
        $resolvedOptions = [];
        foreach ($form->fields_json ?? [] as $field) {
            $ds = $field['data_source'] ?? '';
            if (!$ds) continue;

            if ($ds === 'seminars') {
                $resolvedOptions[$field['key']] = \App\Models\Seminar::where('status', '!=', 'completed')
                    ->pluck('title')->toArray();
            } elseif ($ds === 'editions') {
                $resolvedOptions[$field['key']] = \App\Models\Edition::orderByDesc('start_date')
                    ->pluck('title')->toArray();
            } elseif ($ds === 'countries') {
                $resolvedOptions[$field['key']] = [
                    'Afghanistan','Afrique du Sud','Albanie','Algérie','Allemagne','Angola','Arabie Saoudite','Argentine',
                    'Australie','Autriche','Azerbaïdjan','Bahreïn','Bangladesh','Belgique','Bénin','Bolivie','Brésil',
                    'Bulgarie','Burkina Faso','Burundi','Cambodge','Cameroun','Canada','Chili','Chine','Colombie',
                    'Comores','Congo','Corée du Sud','Côte d\'Ivoire','Croatie','Cuba','Danemark','Djibouti','Égypte',
                    'Émirats Arabes Unis','Équateur','Espagne','Éthiopie','Finlande','France','Gabon','Gambie','Ghana',
                    'Grèce','Guatemala','Guinée','Guinée-Bissau','Haïti','Honduras','Hongrie','Inde','Indonésie','Irak',
                    'Iran','Irlande','Islande','Israël','Italie','Jamaïque','Japon','Jordanie','Kazakhstan','Kenya',
                    'Koweït','Laos','Liban','Liberia','Libye','Luxembourg','Madagascar','Malaisie','Malawi','Mali',
                    'Maroc','Mauritanie','Mexique','Moldavie','Mongolie','Mozambique','Myanmar','Namibie','Népal',
                    'Nicaragua','Niger','Nigeria','Norvège','Nouvelle-Zélande','Oman','Ouganda','Pakistan','Palestine',
                    'Panama','Paraguay','Pays-Bas','Pérou','Philippines','Pologne','Portugal','Qatar','République Centrafricaine',
                    'République Démocratique du Congo','République Dominicaine','République Tchèque','Roumanie',
                    'Royaume-Uni','Russie','Rwanda','Salvador','Sénégal','Serbie','Sierra Leone','Singapour',
                    'Slovaquie','Slovénie','Somalie','Soudan','Soudan du Sud','Sri Lanka','Suède','Suisse','Syrie',
                    'Tanzanie','Tchad','Thaïlande','Togo','Tunisie','Turquie','Ukraine','Uruguay','Venezuela',
                    'Vietnam','Yémen','Zambie','Zimbabwe',
                ];
            }
        }

        return view('public.form', compact('form', 'resolvedOptions'));
    }

    /**
     * Handle submission of the public form (Option B: creates User + Profile + Enrollment)
     */
    public function store(Request $request, string $token)
    {
        $form = FormTemplate::where('share_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        // Build dynamic validation rules from fields_json
        $rules = [];
        foreach ($form->fields_json ?? [] as $field) {
            $key = 'answers.' . $field['key'];
            $rule = $field['required'] ? 'required' : 'nullable';
            if ($field['type'] === 'email') {
                $rule .= '|email';
            }
            $rules[$key] = $rule . '|string|max:500';
        }

        $validated = $request->validate($rules);
        $answers = $validated['answers'] ?? [];

        // Save raw submission
        $submission = FormSubmission::create([
            'form_template_id' => $form->id,
            'answers_json'     => $answers,
            'respondent_ip'    => $request->ip(),
        ]);

        // === Option B: Auto-create User + Profile + Enrollment ===
        $email     = $answers['email'] ?? null;
        $seminarId = $form->seminar_id;

        if ($email && $seminarId) {
            // Avoid duplicate accounts
            $user = User::where('email', $email)->first();

            if (!$user) {
                $candidateRole = Role::where('name', 'Candidate')->first();
                $fullName = trim(($answers['prenom'] ?? '') . ' ' . ($answers['nom'] ?? '')) ?: 'Participant';

                $user = User::create([
                    'name'     => $fullName,
                    'email'    => $email,
                    'password' => Hash::make(Str::random(12)), // Random password, admin resets if needed
                    'role_id'  => $candidateRole?->id,
                ]);

                CandidateProfile::create([
                    'user_id'       => $user->id,
                    'phone'         => $answers['whatsapp'] ?? null,
                    'birth_date'    => !empty($answers['date_naissance']) ? $answers['date_naissance'] : null,
                    'birth_place'   => $answers['lieu_naissance'] ?? null,
                    'residence_country' => $answers['pays'] ?? null,
                    'emergency_contact_phone' => $answers['urgence'] ?? null,
                ]);
            }

            // Create enrollment if not already enrolled
            $enrollment = Enrollment::firstOrCreate(
                ['user_id' => $user->id, 'seminar_id' => $seminarId],
                ['status' => 'pending']
            );

            // Link enrollment to submission
            $submission->update(['enrollment_id' => $enrollment->id]);
        }

        return view('public.form_success', compact('form'));
    }
}
