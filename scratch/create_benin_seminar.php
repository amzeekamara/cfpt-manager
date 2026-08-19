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

// 1. Get active cohort
$activeCohort = Cohort::where('status', 'active')->first() ?? Cohort::first();

// 2. Create the Benin Seminar in DRAFT mode
$seminar = Seminar::create([
    'cohort_id' => $activeCohort?->id ?? 1,
    'title' => "Renforcement de capacités des formateurs du Bénin en FP&T - Édition 2026",
    'description' => "Séminaires de Formation Professionnelle & Technique pour le Bénin, organisés au CFPT Sénégal-Japon de Dakar avec l'appui de la JICA. Portant sur trois domaines : (I) Froid sanitaire et conditionnement d'air, (II) Maintenance et administration des réseaux informatiques, (III) Maintenance et conduite d'engins lourds.",
    'start_date' => '2026-08-24 08:00:00',
    'end_date' => '2026-09-18 17:00:00',
    'max_participants' => 30, // 10 + 10 + 10
    'status' => 'draft', // En attente dans les brouillons
    'image' => null,
]);

// 3. Departments associated
$deptFroid = Department::firstOrCreate(['name' => 'Froid et climatisation (Bénin)'], ['is_active' => true]);
$deptInfo = Department::firstOrCreate(['name' => 'Informatique industrielle'], ['is_active' => true]);
$deptEngins = Department::firstOrCreate(['name' => 'Engins lourds'], ['is_active' => true]);

$seminar->departments()->syncWithoutDetaching([$deptFroid->id, $deptInfo->id, $deptEngins->id]);

// 4. Create the 3 Topics
$topic1 = Topic::create([
    'seminar_id' => $seminar->id,
    'name' => "Thème 1 : Étude et réalisation d'un système de froid sanitaire et de conditionnement d'air",
    'is_active' => true,
]);

$topic2 = Topic::create([
    'seminar_id' => $seminar->id,
    'name' => "Thème 2 : Maintenance et administration des réseaux informatiques",
    'is_active' => true,
]);

$topic3 = Topic::create([
    'seminar_id' => $seminar->id,
    'name' => "Thème 3 : Maintenance et conduite d'engins lourds",
    'is_active' => true,
]);

// 5. Create Dedicated Form Template for Benin
$adminUser = User::whereHas('role', function($q) { $q->where('name', 'Admin'); })->first() ?? User::first();

$form = FormTemplate::create([
    'title' => "Formulaire de Candidature — Renforcement des Formateurs du Bénin (2026)",
    'slug' => "candidature-formateurs-benin-2026",
    'description' => "Formulaire officiel de candidature aux séminaires de renforcement de capacités pour les formateurs du Bénin (Froid, Réseaux Informatiques & Engins Lourds) au CFPT Sénégal-Japon (JICA).",
    'seminar_id' => $seminar->id,
    'created_by' => $adminUser?->id ?? 1,
    'is_active' => true,
    'share_token' => Str::random(32),
    'fields_json' => [
        ['key' => 'theme_choisi', 'label' => 'Domaine / Thème de Stage choisi', 'type' => 'select', 'required' => true, 'options' => [
            "Froid et climatisation : Étude et réalisation d'un système de froid sanitaire et de conditionnement d'air",
            "Informatique industrielle : Maintenance et administration des réseaux informatiques",
            "Engins lourds : Maintenance et conduite d'engins lourds"
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
        ['key' => 'employeur', 'label' => 'Ministère / Établissement de rattachement (Bénin)', 'type' => 'text', 'required' => true],
        ['key' => 'fonction', 'label' => 'Fonction / Poste occupé', 'type' => 'text', 'required' => true],
        ['key' => 'diplomas', 'label' => 'Diplôme d\'études supérieures (Bac + 2 minimum)', 'type' => 'group_file', 'required' => false],
        ['key' => 'photo', 'label' => 'Photo d\'identité récente (Passeport)', 'type' => 'file', 'required' => false],
    ]
]);

echo "BENIN SEMINAR CREATED SUCCESSFULLY:\n";
echo "- Seminar ID: {$seminar->id} (Title: {$seminar->title}, Status: {$seminar->status})\n";
echo "- Departments: {$deptFroid->name}, {$deptInfo->name}, {$deptEngins->name}\n";
echo "- Topics Created: Topic #{$topic1->id}, Topic #{$topic2->id}, Topic #{$topic3->id}\n";
echo "- Form Template: ID #{$form->id} (Token: {$form->share_token}, URL: /f/{$form->share_token})\n";
