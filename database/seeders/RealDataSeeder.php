<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Department;
use App\Models\FormTemplate;
use Illuminate\Support\Str;

class RealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cohort and Seminar for Côte d'Ivoire
        $cohortCI = Cohort::create([
            'title' => 'Édition 2026 - Côte d\'Ivoire',
            'start_date' => '2026-07-27',
            'end_date' => '2026-08-21',
            'status' => 'active'
        ]);

        $seminarCI = Seminar::create([
            'title' => 'Renforcement de capacités des formateurs de la Côte d\'Ivoire en FP&T',
            'description' => 'Séminaires de Formation Professionnelle & Technique pour la Côte d\'Ivoire, organisés avec l\'appui de la JICA au CFPT Sénégal-Japon.',
            'cohort_id' => $cohortCI->id,
            'start_date' => '2026-07-27',
            'end_date' => '2026-08-21',
            'status' => 'active'
        ]);

        $deptCIFroid = Department::create([
            'name' => 'Froid industriel et climatisation'
        ]);
        
        $deptCIStruct = Department::create([
            'name' => 'Structure métallique'
        ]);

        $seminarCI->departments()->attach([$deptCIFroid->id, $deptCIStruct->id]);

        $admin = \App\Models\User::first();

        $formCI = FormTemplate::create([
            'title' => 'Formulaire d\'inscription - Côte d\'Ivoire (2026)',
            'slug' => 'inscription-ci-2026',
            'description' => 'Formulaire pour l\'inscription aux séminaires de l\'Édition 2026 de la Côte d\'Ivoire.',
            'seminar_id' => $seminarCI->id,
            'created_by' => $admin->id ?? 1,
            'is_active' => true,
            'share_token' => Str::random(32),
            'fields_json' => [
                ['key' => 'nom', 'label' => 'Nom', 'type' => 'text', 'required' => true],
                ['key' => 'prenom', 'label' => 'Prénom', 'type' => 'text', 'required' => true],
                ['key' => 'email', 'label' => 'Adresse Email', 'type' => 'email', 'required' => true],
                ['key' => 'sexe', 'label' => 'Sexe', 'type' => 'radio', 'required' => true, 'options' => ['Masculin', 'Féminin']],
                ['key' => 'date_naissance', 'label' => 'Date de Naissance', 'type' => 'date', 'required' => true],
                ['key' => 'lieu_naissance', 'label' => 'Lieu de Naissance', 'type' => 'text', 'required' => true],
                ['key' => 'nationalite', 'label' => 'Nationalité', 'type' => 'text', 'required' => true],
                ['key' => 'situation_fam', 'label' => 'Situation Familiale', 'type' => 'select', 'required' => true, 'options' => ['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf/Veuve']],
                ['key' => 'whatsapp', 'label' => 'Numéro WhatsApp', 'type' => 'text', 'required' => true],
                ['key' => 'pays', 'label' => 'Pays de résidence', 'type' => 'text', 'required' => true],
                ['key' => 'adresse', 'label' => 'Adresse de Domicile', 'type' => 'textarea', 'required' => true],
                ['key' => 'urgence_nom', 'label' => 'Personne à contacter (Nom complet)', 'type' => 'text', 'required' => true],
                ['key' => 'urgence', 'label' => 'Contact d\'Urgence (Téléphone)', 'type' => 'text', 'required' => true],
                ['key' => 'profession', 'label' => 'Profession actuelle', 'type' => 'text', 'required' => true],
                ['key' => 'employeur', 'label' => 'Employeur actuel', 'type' => 'text', 'required' => true],
                ['key' => 'fonction', 'label' => 'Fonction / Poste', 'type' => 'text', 'required' => true],
                ['key' => 'diplomas', 'label' => 'Diplômes (Historique Académique)', 'type' => 'group_file', 'required' => false],
                ['key' => 'photo', 'label' => 'Photo d\'identité (Passeport)', 'type' => 'file', 'required' => false],
            ]
        ]);

        // 2. Cohort and Seminar for Benin
        $cohortBenin = Cohort::create([
            'title' => 'Édition 2026 - Bénin',
            'start_date' => '2026-08-24',
            'end_date' => '2026-09-18',
            'status' => 'active'
        ]);

        $seminarBenin = Seminar::create([
            'title' => 'Renforcement de capacités des formateurs du Bénin en FP&T',
            'description' => 'Séminaires de Formation Professionnelle & Technique pour le Bénin, organisés avec l\'appui de la JICA au CFPT Sénégal-Japon.',
            'cohort_id' => $cohortBenin->id,
            'start_date' => '2026-08-24',
            'end_date' => '2026-09-18',
            'status' => 'active'
        ]);

        $deptBeninFroid = Department::create([
            'name' => 'Froid et climatisation (Bénin)'
        ]);
        
        $deptBeninInfo = Department::create([
            'name' => 'Informatique industrielle'
        ]);

        $deptBeninEngins = Department::create([
            'name' => 'Engins lourds'
        ]);

        $seminarBenin->departments()->attach([$deptBeninFroid->id, $deptBeninInfo->id, $deptBeninEngins->id]);

        $formBenin = FormTemplate::create([
            'title' => 'Formulaire d\'inscription - Bénin (2026)',
            'slug' => 'inscription-benin-2026',
            'description' => 'Formulaire pour l\'inscription aux séminaires de l\'Édition 2026 du Bénin.',
            'seminar_id' => $seminarBenin->id,
            'created_by' => $admin->id ?? 1,
            'is_active' => true,
            'share_token' => Str::random(32),
            'fields_json' => $formCI->fields_json
        ]);
    }
}
