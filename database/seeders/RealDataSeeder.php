<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Topic;
use App\Models\Department;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\User;
use App\Models\Trainer;
use App\Models\Registration;
use App\Models\CandidateProfile;
use App\Models\Timetable;
use App\Models\Evaluation;
use App\Models\Grade;
use Illuminate\Support\Facades\Hash;

class RealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deptHeadRole = Role::firstOrCreate(['name' => 'Chef de Département'], ['description' => 'Administrateur pédagogique d\'un département']);
        $trainerRole  = Role::firstOrCreate(['name' => 'Formateur'], ['description' => 'Formateur / Enseignant du CFPT']);
        $studentRole  = Role::firstOrCreate(['name' => 'Candidat'], ['description' => 'Étudiant inscrit au CFPT']);

        // 1. Deux Années Académiques pour illustrer que les filières de chaque département peuvent varier selon les années
        $cohortPrev = Cohort::create([
            'title' => 'Année Académique 2025 - 2026',
            'start_date' => '2025-10-01',
            'end_date' => '2026-07-31',
            'status' => 'completed',
        ]);

        $cohort = Cohort::create([
            'title' => 'Année Académique 2026 - 2027',
            'start_date' => '2026-10-01',
            'end_date' => '2027-07-31',
            'status' => 'active',
        ]);

        // 2. Les 3 Chefs de Département & les 3 Départements Officiels du CFPT Sénégal-Japon
        $headInfo = User::firstOrCreate(
            ['email' => 'chef.informatique@cfptsj.sn'],
            [
                'name' => 'Dr. Moussa Ndiaye',
                'password' => Hash::make('password'),
                'role_id' => $deptHeadRole->id,
                'position' => 'Chef du Département Informatique',
            ]
        );

        $headMeca = User::firstOrCreate(
            ['email' => 'chef.mecanique@cfptsj.sn'],
            [
                'name' => 'M. Cheikh Diop',
                'password' => Hash::make('password'),
                'role_id' => $deptHeadRole->id,
                'position' => 'Chef du Département Mécanique',
            ]
        );

        $headElec = User::firstOrCreate(
            ['email' => 'chef.electrique@cfptsj.sn'],
            [
                'name' => 'Ing. Ibrahima Fall',
                'password' => Hash::make('password'),
                'role_id' => $deptHeadRole->id,
                'position' => 'Chef du Département Électrique',
            ]
        );

        // DÉPARTEMENT 1 : Département Informatique
        $deptInfo = Department::create([
            'name' => 'Département Informatique',
            'head_user_id' => $headInfo->id,
            'description' => 'Filières en Informatique Industrielle, Réseaux, Télécommunications, Systèmes Embarqués et Développement.',
            'is_active' => true,
        ]);
        $headInfo->update(['department_id' => $deptInfo->id]);

        // DÉPARTEMENT 2 : Département Mécanique
        $deptMeca = Department::create([
            'name' => 'Département Mécanique',
            'head_user_id' => $headMeca->id,
            'description' => 'Filières en Maintenance des Véhicules Moteurs, Engins Lourds, Mécanique Industrielle et Structure Métallique.',
            'is_active' => true,
        ]);
        $headMeca->update(['department_id' => $deptMeca->id]);

        // DÉPARTEMENT 3 : Département Électrique
        $deptElec = Department::create([
            'name' => 'Département Électrique',
            'head_user_id' => $headElec->id,
            'description' => 'Filières en Électrotechnique, Automatisme Industriel, Électronique, Froid et Climatisation.',
            'is_active' => true,
        ]);
        $headElec->update(['department_id' => $deptElec->id]);

        // 3. Salles de cours, Ateliers & Laboratoires disponibles par département
        $roomInfo1 = Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Labo Réseaux & Cisco Info-1', 'capacity' => 28, 'type' => 'labo']);
        $roomInfo2 = Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Salle Informatique Info-2', 'capacity' => 35, 'type' => 'cours']);

        $roomMeca1 = Classroom::create(['department_id' => $deptMeca->id, 'name' => 'Atelier Moteurs & Engins Méca-1', 'capacity' => 25, 'type' => 'atelier']);
        $roomMeca2 = Classroom::create(['department_id' => $deptMeca->id, 'name' => 'Salle de Cours Méca-2', 'capacity' => 32, 'type' => 'cours']);

        $roomElec1 = Classroom::create(['department_id' => $deptElec->id, 'name' => 'Atelier Automatisme & API Élec-1', 'capacity' => 24, 'type' => 'atelier']);
        $roomElec2 = Classroom::create(['department_id' => $deptElec->id, 'name' => 'Salle Électrotechnique Élec-2', 'capacity' => 32, 'type' => 'cours']);

        // 4. Filières par Département (Variables selon l'année académique)
        // --- Année 2026-2027 : Département Informatique (2 filières) ---
        $filiereInfo1 = Seminar::create([
            'title' => 'BTS Informatique Industrielle & Réseaux',
            'description' => 'Formation technique supérieure en administration réseaux, programmation embarquée et supervision industrielle.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 28,
            'status' => 'active',
        ]);
        $filiereInfo1->departments()->attach([$deptInfo->id]);

        $topReseaux = Topic::create(['seminar_id' => $filiereInfo1->id, 'name' => 'Architecture Réseaux & Sécurité', 'is_active' => true]);
        $topEmbarque = Topic::create(['seminar_id' => $filiereInfo1->id, 'name' => 'Systèmes Embarqués & Microcontrôleurs', 'is_active' => true]);

        $filiereInfo2 = Seminar::create([
            'title' => 'BTS Développement d\'Applications & Systèmes',
            'description' => 'Conception logicielle, bases de données, développement web/mobile et administration cloud.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 35,
            'status' => 'active',
        ]);
        $filiereInfo2->departments()->attach([$deptInfo->id]);
        $topDevWeb = Topic::create(['seminar_id' => $filiereInfo2->id, 'name' => 'Génie Logiciel & Bases de Données', 'is_active' => true]);

        // --- Année 2026-2027 : Département Mécanique (2 filières) ---
        $filiereMeca1 = Seminar::create([
            'title' => 'BTS Maintenance des Véhicules Moteurs & Engins',
            'description' => 'Diagnostic électronique moteur, hydraulique, transmission et maintenance préventive.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereMeca1->departments()->attach([$deptMeca->id]);
        $topDiag = Topic::create(['seminar_id' => $filiereMeca1->id, 'name' => 'Diagnostic Électronique & Injection', 'is_active' => true]);

        $filiereMeca2 = Seminar::create([
            'title' => 'BT Structure Métallique & Mécanique Industrielle',
            'description' => 'Usinage, soudure industrielle, chaudronnerie et maintenance mécanique.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereMeca2->departments()->attach([$deptMeca->id]);
        $topUsinage = Topic::create(['seminar_id' => $filiereMeca2->id, 'name' => 'Usinage & Dessin Industriel (CAO)', 'is_active' => true]);

        // --- Année 2026-2027 : Département Électrique (2 filières) ---
        $filiereElec1 = Seminar::create([
            'title' => 'BTS Automatisme & Électrotechnique',
            'description' => 'Maîtrise des automates programmables industriels (API), installations électriques et électronique de puissance.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 24,
            'status' => 'active',
        ]);
        $filiereElec1->departments()->attach([$deptElec->id]);
        $topApi = Topic::create(['seminar_id' => $filiereElec1->id, 'name' => 'Automates Programmables (API Siemens/Omron)', 'is_active' => true]);
        $topPuissance = Topic::create(['seminar_id' => $filiereElec1->id, 'name' => 'Électronique de Puissance & Schémas', 'is_active' => true]);

        $filiereElec2 = Seminar::create([
            'title' => 'BT Froid Industriel & Climatisation',
            'description' => 'Installations frigorifiques, régulation thermique et efficacité énergétique.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereElec2->departments()->attach([$deptElec->id]);
        $topFroid = Topic::create(['seminar_id' => $filiereElec2->id, 'name' => 'Thermodynamique & Installations Frigorifiques', 'is_active' => true]);

        // --- Année précédente 2025-2026 (exemple de variation annuelle des filières) ---
        $filierePrev = Seminar::create([
            'title' => 'BT Électromécanique (Promotion 2025-2026)',
            'description' => 'Filière ouverte lors de l\'année académique 2025-2026.',
            'cohort_id' => $cohortPrev->id,
            'start_date' => '2025-10-05',
            'end_date' => '2026-06-30',
            'max_participants' => 25,
            'status' => 'completed',
        ]);
        $filierePrev->departments()->attach([$deptElec->id]);

        // 5. Formateurs des 3 départements
        $trUser1 = User::firstOrCreate(
            ['email' => 'amadou.sow@cfptsj.sn'],
            [
                'name' => 'M. Amadou Sow',
                'password' => Hash::make('password'),
                'role_id' => $trainerRole->id,
                'department_id' => $deptInfo->id,
                'specialty' => 'Réseaux & Systèmes Embarqués',
            ]
        );
        $trainer1 = Trainer::create([
            'name' => $trUser1->name,
            'email' => $trUser1->email,
            'phone' => '+221 77 412 88 90',
            'seminar_id' => $filiereInfo1->id,
            'remuneration' => 0,
            'payment_status' => 'paid',
        ]);
        $trainer1->seminars()->attach([$filiereInfo1->id, $filiereInfo2->id], ['remuneration' => 0, 'payment_status' => 'paid']);

        $trUser2 = User::firstOrCreate(
            ['email' => 'fatou.gueye@cfptsj.sn'],
            [
                'name' => 'Mme Fatou Gueye',
                'password' => Hash::make('password'),
                'role_id' => $trainerRole->id,
                'department_id' => $deptElec->id,
                'specialty' => 'Automatisme Industriel & Électrotechnique',
            ]
        );
        $trainer2 = Trainer::create([
            'name' => $trUser2->name,
            'email' => $trUser2->email,
            'phone' => '+221 77 654 21 10',
            'seminar_id' => $filiereElec1->id,
            'remuneration' => 0,
            'payment_status' => 'paid',
        ]);
        $trainer2->seminars()->attach([$filiereElec1->id, $filiereElec2->id], ['remuneration' => 0, 'payment_status' => 'paid']);

        $trUser3 = User::firstOrCreate(
            ['email' => 'babacar.ndour@cfptsj.sn'],
            [
                'name' => 'M. Babacar Ndour',
                'password' => Hash::make('password'),
                'role_id' => $trainerRole->id,
                'department_id' => $deptMeca->id,
                'specialty' => 'Diagnostic Moteur & Mécanique',
            ]
        );
        $trainer3 = Trainer::create([
            'name' => $trUser3->name,
            'email' => $trUser3->email,
            'phone' => '+221 77 321 45 67',
            'seminar_id' => $filiereMeca1->id,
            'remuneration' => 0,
            'payment_status' => 'paid',
        ]);
        $trainer3->seminars()->attach([$filiereMeca1->id, $filiereMeca2->id], ['remuneration' => 0, 'payment_status' => 'paid']);

        // 6. Étudiants inscrits dans les 3 départements
        $studentsData = [
            ['name' => 'Ousmane Diallo', 'email' => 'ousmane.diallo@etu.cfptsj.sn', 'matricule' => 'CFPT-INF-001', 'seminar' => $filiereInfo1, 'topic' => $topReseaux, 'score1' => 16.5, 'score2' => 15.0],
            ['name' => 'Aïssatou Ba', 'email' => 'aissatou.ba@etu.cfptsj.sn', 'matricule' => 'CFPT-INF-002', 'seminar' => $filiereInfo1, 'topic' => $topReseaux, 'score1' => 18.0, 'score2' => 17.5],
            ['name' => 'Mamadou Lamine Sy', 'email' => 'mamadou.sy@etu.cfptsj.sn', 'matricule' => 'CFPT-INF-003', 'seminar' => $filiereInfo2, 'topic' => $topDevWeb, 'score1' => 14.0, 'score2' => 15.5],
            ['name' => 'Mariama Sarr', 'email' => 'mariama.sarr@etu.cfptsj.sn', 'matricule' => 'CFPT-MEC-001', 'seminar' => $filiereMeca1, 'topic' => $topDiag, 'score1' => 15.0, 'score2' => 16.0],
            ['name' => 'Abdoulaye Wade', 'email' => 'abdoulaye.wade@etu.cfptsj.sn', 'matricule' => 'CFPT-ELE-001', 'seminar' => $filiereElec1, 'topic' => $topApi, 'score1' => 14.5, 'score2' => 13.0],
            ['name' => 'Khadija Thiam', 'email' => 'khadija.thiam@etu.cfptsj.sn', 'matricule' => 'CFPT-ELE-002', 'seminar' => $filiereElec1, 'topic' => $topPuissance, 'score1' => 17.0, 'score2' => 16.5],
        ];

        $evalInfo1 = Evaluation::create([
            'seminar_id' => $filiereInfo1->id,
            'topic_id' => $topReseaux->id,
            'trainer_id' => $trainer1->id,
            'title' => 'Devoir Surveillé N°1 — Routage & VLAN',
            'type' => 'devoir',
            'coefficient' => 1,
            'max_score' => 20,
            'evaluation_date' => '2026-11-10',
            'is_published' => true,
        ]);

        $evalInfo2 = Evaluation::create([
            'seminar_id' => $filiereInfo1->id,
            'topic_id' => $topEmbarque->id,
            'trainer_id' => $trainer1->id,
            'title' => 'TP Laboratoire — Programmation Microcontrôleur',
            'type' => 'tp',
            'coefficient' => 2,
            'max_score' => 20,
            'evaluation_date' => '2026-11-18',
            'is_published' => true,
        ]);

        foreach ($studentsData as $s) {
            $stuUser = User::firstOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $studentRole->id,
                ]
            );
            $parts = explode(' ', $s['name'], 2);
            CandidateProfile::updateOrCreate(
                ['user_id' => $stuUser->id],
                [
                    'first_names' => $parts[0] ?? $s['name'],
                    'last_name' => $parts[1] ?? '',
                    'matricule' => $s['matricule'],
                    'phone' => '+221 77 000 00 00',
                    'nationality' => 'Sénégalaise',
                    'current_profession' => 'Étudiant CFPT',
                    'current_employer' => 'CFPT Sénégal-Japon',
                ]
            );
            $reg = Registration::create([
                'user_id' => $stuUser->id,
                'seminar_id' => $s['seminar']->id,
                'topic_id' => $s['topic']->id,
                'status' => 'approved',
            ]);

            if ($s['seminar']->id === $filiereInfo1->id) {
                Grade::create([
                    'evaluation_id' => $evalInfo1->id,
                    'registration_id' => $reg->id,
                    'score' => $s['score1'],
                    'appreciation' => $s['score1'] >= 16 ? 'Très Bien' : 'Bien',
                ]);
                Grade::create([
                    'evaluation_id' => $evalInfo2->id,
                    'registration_id' => $reg->id,
                    'score' => $s['score2'],
                    'appreciation' => $s['score2'] >= 16 ? 'Très Bien' : 'Bien',
                ]);
            }
        }

        // 7. Emplois du temps des 3 départements
        $slots = [
            ['department_id' => $deptInfo->id, 'seminar_id' => $filiereInfo1->id, 'topic_id' => $topReseaux->id, 'trainer_id' => $trainer1->id, 'classroom_id' => $roomInfo1->id, 'day_of_week' => 'Lundi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
            ['department_id' => $deptInfo->id, 'seminar_id' => $filiereInfo1->id, 'topic_id' => $topEmbarque->id, 'trainer_id' => $trainer1->id, 'classroom_id' => $roomInfo2->id, 'day_of_week' => 'Mardi', 'start_time' => '14:00', 'end_time' => '17:00', 'planned_hours' => 3],
            ['department_id' => $deptInfo->id, 'seminar_id' => $filiereInfo2->id, 'topic_id' => $topDevWeb->id, 'trainer_id' => $trainer1->id, 'classroom_id' => $roomInfo2->id, 'day_of_week' => 'Jeudi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
            ['department_id' => $deptMeca->id, 'seminar_id' => $filiereMeca1->id, 'topic_id' => $topDiag->id, 'trainer_id' => $trainer3->id, 'classroom_id' => $roomMeca1->id, 'day_of_week' => 'Mardi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
            ['department_id' => $deptMeca->id, 'seminar_id' => $filiereMeca2->id, 'topic_id' => $topUsinage->id, 'trainer_id' => $trainer3->id, 'classroom_id' => $roomMeca2->id, 'day_of_week' => 'Jeudi', 'start_time' => '14:00', 'end_time' => '17:00', 'planned_hours' => 3],
            ['department_id' => $deptElec->id, 'seminar_id' => $filiereElec1->id, 'topic_id' => $topApi->id, 'trainer_id' => $trainer2->id, 'classroom_id' => $roomElec1->id, 'day_of_week' => 'Mercredi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
            ['department_id' => $deptElec->id, 'seminar_id' => $filiereElec1->id, 'topic_id' => $topPuissance->id, 'trainer_id' => $trainer2->id, 'classroom_id' => $roomElec2->id, 'day_of_week' => 'Vendredi', 'start_time' => '09:00', 'end_time' => '12:00', 'planned_hours' => 3],
            ['department_id' => $deptElec->id, 'seminar_id' => $filiereElec2->id, 'topic_id' => $topFroid->id, 'trainer_id' => $trainer2->id, 'classroom_id' => $roomElec2->id, 'day_of_week' => 'Samedi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
        ];

        foreach ($slots as $slot) {
            Timetable::create($slot + ['is_active' => true]);
        }

        // 8. Formations Continues (Courte durée : 1, 2 ou 3 mois — Payantes — Entreprises partenaires & Attestations PDF)
        $fc1 = Seminar::create([
            'title' => 'Perfectionnement Automates Programmables Siemens S7-1500 & Supervision SCADA',
            'training_type' => 'continue',
            'partner_company' => 'SENELEC (Société Nationale d\'Électricité du Sénégal)',
            'duration_label' => '2 mois',
            'fee_amount' => 650000,
            'specific_theme' => 'Automatisme Industriel, API Siemens TIA Portal & Télégestion',
            'description' => 'Formation continue intensive à la demande de la SENELEC pour ses techniciens et ingénieurs de maintenance des centrales et postes.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-11-02',
            'end_date' => '2026-12-30',
            'max_participants' => 15,
            'status' => 'active',
        ]);
        $fc1->departments()->attach([$deptElec->id]);
        $fc1Topic = Topic::create(['seminar_id' => $fc1->id, 'name' => 'Programmation TIA Portal & Réseaux Profinet', 'is_active' => true]);

        $fc2 = Seminar::create([
            'title' => 'Diagnostic Électronique Moteur & Maintenance Hydraulique Engins Lourds',
            'training_type' => 'continue',
            'partner_company' => 'Eiffage Sénégal & DP World Dakar',
            'duration_label' => '3 mois',
            'fee_amount' => 850000,
            'specific_theme' => 'Diagnostic Électronique Embarqué, Injection Common Rail & Hydraulique',
            'description' => 'Formation continue sur mesure pour les équipes de maintenance des grues portuaires et engins de chantier.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-15',
            'end_date' => '2027-01-15',
            'max_participants' => 12,
            'status' => 'active',
        ]);
        $fc2->departments()->attach([$deptMeca->id]);
        $fc2Topic = Topic::create(['seminar_id' => $fc2->id, 'name' => 'Valise de Diagnostic & Systèmes Hydrauliques Proportionnels', 'is_active' => true]);

        $fc3 = Seminar::create([
            'title' => 'Cybersécurité des Réseaux Industriels (OT/IT) & Virtualisation',
            'training_type' => 'continue',
            'partner_company' => 'SONATEL / Orange Sénégal',
            'duration_label' => '1 mois',
            'fee_amount' => 500000,
            'specific_theme' => 'Sécurité des Infrastructures Critiques, Pare-feu & Haute Disponibilité',
            'description' => 'Session courte de spécialisation pour les administrateurs systèmes et réseaux d\'entreprise.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-11-15',
            'end_date' => '2026-12-15',
            'max_participants' => 20,
            'status' => 'active',
        ]);
        $fc3->departments()->attach([$deptInfo->id]);
        $fc3Topic = Topic::create(['seminar_id' => $fc3->id, 'name' => 'Sécurisation Réseaux & Supervision SOC', 'is_active' => true]);

        // Inscription de stagiaires professionnels avec numéro d'attestation pour les Formations Continues
        $continuingTrainees = [
            ['name' => 'Moustapha Dieng', 'email' => 'moustapha.dieng@senelec.sn', 'phone' => '+221 77 810 11 22', 'company' => 'SENELEC', 'function' => 'Technicien Supérieur Automatisme', 'fc' => $fc1, 'topic' => $fc1Topic, 'cert' => 'ATT-CFPT-2026-0001'],
            ['name' => 'Ndèye Astou Cissé', 'email' => 'astou.cisse@senelec.sn', 'phone' => '+221 77 810 33 44', 'company' => 'SENELEC', 'function' => 'Ingénieure Contrôle-Commande', 'fc' => $fc1, 'topic' => $fc1Topic, 'cert' => 'ATT-CFPT-2026-0002'],
            ['name' => 'Pape Gorgui Faye', 'email' => 'pape.faye@dpworld.sn', 'phone' => '+221 77 920 55 66', 'company' => 'DP World Dakar', 'function' => 'Chef d\'Équipe Maintenance Engins', 'fc' => $fc2, 'topic' => $fc2Topic, 'cert' => 'ATT-CFPT-2026-0003'],
            ['name' => 'Seynabou Ndiaye', 'email' => 'seynabou.ndiaye@sonatel.sn', 'phone' => '+221 77 500 77 88', 'company' => 'SONATEL', 'function' => 'Ingénieure Sécurité Réseaux', 'fc' => $fc3, 'topic' => $fc3Topic, 'cert' => 'ATT-CFPT-2026-0004'],
        ];

        foreach ($continuingTrainees as $ct) {
            $ctUser = User::firstOrCreate(
                ['email' => $ct['email']],
                [
                    'name' => $ct['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $studentRole->id,
                ]
            );
            $parts = explode(' ', $ct['name'], 2);
            CandidateProfile::updateOrCreate(
                ['user_id' => $ctUser->id],
                [
                    'first_names' => $parts[0] ?? $ct['name'],
                    'last_name' => $parts[1] ?? '',
                    'matricule' => $ct['cert'],
                    'phone' => $ct['phone'],
                    'nationality' => 'Sénégalaise',
                    'current_profession' => $ct['function'],
                    'job_function' => $ct['function'],
                    'current_employer' => $ct['company'],
                ]
            );
            Registration::create([
                'user_id' => $ctUser->id,
                'seminar_id' => $ct['fc']->id,
                'topic_id' => $ct['topic']->id,
                'company_name' => $ct['company'],
                'status' => 'approved',
                'certificate_number' => $ct['cert'],
                'certificate_issued_at' => now(),
            ]);
        }
    }
}
