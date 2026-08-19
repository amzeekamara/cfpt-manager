<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Department;
use App\Models\Topic;
use App\Models\FormTemplate;
use App\Models\User;
use Illuminate\Support\Str;

// 1. Get current active cohort
$activeCohort = Cohort::where('status', 'active')->first() ?? Cohort::first();

// 2. Create the Seminar in DRAFT mode
$seminar = Seminar::create([
    'cohort_id' => $activeCohort?->id ?? 1,
    'title' => "Renforcement de capacités des formateurs de la Côte d'Ivoire en FP&T - Édition 2026",
    'description' => "Séminaires de Formation Professionnelle & Technique pour la Côte d'Ivoire organisés au CFPT Sénégal-Japon avec l'appui de la JICA. Domaines : (I) Froid Industriel et Climatisation (Étude et réalisation d'une unité photovoltaïque de conservation de fruits et légumes) ; (II) Structure Métallique (Conception et réalisation d'une trancheuse électrique de fruits et légumes).",
    'start_date' => '2026-07-27 08:00:00',
    'end_date' => '2026-08-21 17:00:00',
    'max_participants' => 20,
    'status' => 'draft', // Placé en attente dans les brouillons
    'image' => null,
]);

// 3. Attach Departments (Froid industriel et climatisation + Structure métallique)
$deptFroid = Department::firstOrCreate(['name' => 'Froid industriel et climatisation'], ['is_active' => true]);
$deptStructure = Department::firstOrCreate(['name' => 'Structure métallique'], ['is_active' => true]);
$seminar->departments()->syncWithoutDetaching([$deptFroid->id, $deptStructure->id]);

// 4. Create Topics for this Seminar
$topic1 = Topic::create([
    'seminar_id' => $seminar->id,
    'name' => "Thème 1 : Étude et réalisation d'une unité photovoltaïque de conservation de fruits et légumes",
    'is_active' => true,
]);

$topic2 = Topic::create([
    'seminar_id' => $seminar->id,
    'name' => "Thème 2 : Conception et réalisation d'une trancheuse électrique de fruits et légumes",
    'is_active' => true,
]);

// 5. Create Dedicated Form Template for this Seminar
$adminUser = User::whereHas('role', function($q) { $q->where('name', 'Admin'); })->first() ?? User::first();

$form = FormTemplate::create([
    'title' => "Formulaire de Candidature — Renforcement des Formateurs Côte d'Ivoire (2026)",
    'slug' => "candidature-formateurs-ci-2026",
    'description' => "Formulaire officiel de candidature aux séminaires de renforcement de capacités en Froid Industriel & Structure Métallique au CFPT Sénégal-Japon (JICA).",
    'seminar_id' => $seminar->id,
    'created_by' => $adminUser?->id ?? 1,
    'is_active' => true,
    'share_token' => Str::random(32),
    'fields_json' => [
        ['key' => 'theme_choisi', 'label' => 'Thème de Formation souhaité', 'type' => 'select', 'required' => true, 'options' => [
            "Froid Industriel : Étude et réalisation d'une unité photovoltaïque de conservation de fruits et légumes",
            "Structure Métallique : Conception et réalisation d'une trancheuse électrique de fruits et légumes"
        ]],
        ['key' => 'nom', 'label' => 'Nom de famille', 'type' => 'text', 'required' => true],
        ['key' => 'prenom', 'label' => 'Prénom(s)', 'type' => 'text', 'required' => true],
        ['key' => 'email', 'label' => 'Adresse Email officielle', 'type' => 'email', 'required' => true],
        ['key' => 'sexe', 'label' => 'Sexe', 'type' => 'radio', 'required' => true, 'options' => ['Masculin', 'Féminin']],
        ['key' => 'date_naissance', 'label' => 'Date de Naissance', 'type' => 'date', 'required' => true],
        ['key' => 'lieu_naissance', 'label' => 'Lieu de Naissance', 'type' => 'text', 'required' => true],
        ['key' => 'nationalite', 'label' => 'Nationalité', 'type' => 'text', 'required' => true],
        ['key' => 'situation_fam', 'label' => 'Situation Familiale', 'type' => 'select', 'required' => true, 'options' => ['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf/Veuve']],
        ['key' => 'whatsapp', 'label' => 'Numéro de Téléphone / WhatsApp', 'type' => 'text', 'required' => true],
        ['key' => 'pays', 'label' => 'Pays de résidence', 'type' => 'text', 'required' => true],
        ['key' => 'adresse', 'label' => 'Adresse de Domicile', 'type' => 'textarea', 'required' => true],
        ['key' => 'urgence_nom', 'label' => 'Personne à contacter en cas d\'urgence (Nom complet)', 'type' => 'text', 'required' => true],
        ['key' => 'urgence', 'label' => 'Téléphone d\'Urgence', 'type' => 'text', 'required' => true],
        ['key' => 'profession', 'label' => 'Profession actuelle', 'type' => 'text', 'required' => true],
        ['key' => 'employeur', 'label' => 'Ministère / Établissement de rattachement (Côte d\'Ivoire)', 'type' => 'text', 'required' => true],
        ['key' => 'fonction', 'label' => 'Fonction / Poste occupé', 'type' => 'text', 'required' => true],
        ['key' => 'diplomas', 'label' => 'Dernier Diplôme d\'études supérieures (Bac + 2 minimum)', 'type' => 'group_file', 'required' => false],
        ['key' => 'photo', 'label' => 'Photo d\'identité récente (Passeport)', 'type' => 'file', 'required' => false],
    ]
]);

echo "SUCCESS:\n";
echo "- Seminar ID: {$seminar->id} (Status: {$seminar->status}, Cohort ID: {$seminar->cohort_id})\n";
echo "- Topics Created: Topic #{$topic1->id}, Topic #{$topic2->id}\n";
echo "- Form Template ID: {$form->id} (Token: {$form->share_token}, URL: /f/{$form->share_token})\n";
