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
use App\Models\CourseChapter;
use App\Models\CourseDiscussion;
use App\Models\EvaluationSubmission;
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

        // 1. Années Académiques
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
            'description' => 'Filières en Informatique Industrielle, Réseaux, Télécommunications, Systèmes Embarqués, Développement Logiciel et Cloud.',
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

        // =========================================================================
        // 3. SALLES DU DÉPARTEMENT INFORMATIQUE (8 Salles / Labos) + Autres Dpts
        // =========================================================================
        $sallesInfo = [
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Labo Réseaux & Cisco Info-1', 'capacity' => 25, 'type' => 'labo']),
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Labo Développement Web & Mobile Info-2', 'capacity' => 25, 'type' => 'cours']),
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Labo Systèmes Embarqués & IoT Info-3', 'capacity' => 25, 'type' => 'labo']),
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Salle Multimédia & Infographie Info-4', 'capacity' => 25, 'type' => 'cours']),
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Labo Cybersécurité & Serveurs Info-5', 'capacity' => 25, 'type' => 'labo']),
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Salle de Cours Magistral Info-6', 'capacity' => 30, 'type' => 'cours']),
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Salle Programmation & Bases de Données Info-7', 'capacity' => 25, 'type' => 'cours']),
            Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Labo Maintenance & Architecture Matérielle Info-8', 'capacity' => 25, 'type' => 'atelier']),
        ];

        // Salles Mécanique
        $roomMeca1 = Classroom::create(['department_id' => $deptMeca->id, 'name' => 'Atelier Moteurs & Engins Méca-1', 'capacity' => 25, 'type' => 'atelier']);
        $roomMeca2 = Classroom::create(['department_id' => $deptMeca->id, 'name' => 'Salle de Cours Méca-2', 'capacity' => 32, 'type' => 'cours']);

        // Salles Électrique
        $roomElec1 = Classroom::create(['department_id' => $deptElec->id, 'name' => 'Atelier Automatisme & API Élec-1', 'capacity' => 24, 'type' => 'atelier']);
        $roomElec2 = Classroom::create(['department_id' => $deptElec->id, 'name' => 'Salle Électrotechnique Élec-2', 'capacity' => 32, 'type' => 'cours']);

        // =========================================================================
        // 4. LES 3 FILIÈRES DU DÉPARTEMENT INFO (Chacune avec Classe 1 et Classe 2)
        //    Total = 6 Classes au Département Informatique
        // =========================================================================

        // Filière 1 : BTS Informatique Industrielle & Réseaux (IIR)
        $filiereIIR_C1 = Seminar::create([
            'title' => 'BTS Informatique Industrielle & Réseaux — Classe 1',
            'description' => 'Filière 1 / Classe 1 : Administration réseaux Cisco, protocoles industriels et systèmes temps réel.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereIIR_C1->departments()->attach([$deptInfo->id]);
        $topIIR_C1_Reseaux = Topic::create(['seminar_id' => $filiereIIR_C1->id, 'name' => 'Architecture Réseaux & Routage Cisco', 'is_active' => true]);
        $topIIR_C1_Embarque = Topic::create(['seminar_id' => $filiereIIR_C1->id, 'name' => 'Systèmes Embarqués & Microcontrôleurs', 'is_active' => true]);

        $filiereIIR_C2 = Seminar::create([
            'title' => 'BTS Informatique Industrielle & Réseaux — Classe 2',
            'description' => 'Filière 1 / Classe 2 : Administration réseaux Cisco, protocoles industriels et supervision SCADA.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereIIR_C2->departments()->attach([$deptInfo->id]);
        $topIIR_C2_Reseaux = Topic::create(['seminar_id' => $filiereIIR_C2->id, 'name' => 'Architecture Réseaux & Routage Cisco', 'is_active' => true]);
        $topIIR_C2_Embarque = Topic::create(['seminar_id' => $filiereIIR_C2->id, 'name' => 'Systèmes Embarqués & Microcontrôleurs', 'is_active' => true]);

        // Filière 2 : BTS Développement d'Applications & Systèmes (DAS)
        $filiereDAS_C1 = Seminar::create([
            'title' => 'BTS Développement d\'Applications & Systèmes — Classe 1',
            'description' => 'Filière 2 / Classe 1 : Conception logicielle, bases de données, applications web et mobiles.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereDAS_C1->departments()->attach([$deptInfo->id]);
        $topDAS_C1_Dev = Topic::create(['seminar_id' => $filiereDAS_C1->id, 'name' => 'Développement Web & Frameworks PHP/JS', 'is_active' => true]);
        $topDAS_C1_Bdd = Topic::create(['seminar_id' => $filiereDAS_C1->id, 'name' => 'Bases de Données & Modélisation UML', 'is_active' => true]);

        $filiereDAS_C2 = Seminar::create([
            'title' => 'BTS Développement d\'Applications & Systèmes — Classe 2',
            'description' => 'Filière 2 / Classe 2 : Conception logicielle, algorithmique avancée et développement cloud.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereDAS_C2->departments()->attach([$deptInfo->id]);
        $topDAS_C2_Dev = Topic::create(['seminar_id' => $filiereDAS_C2->id, 'name' => 'Développement Web & Frameworks PHP/JS', 'is_active' => true]);
        $topDAS_C2_Bdd = Topic::create(['seminar_id' => $filiereDAS_C2->id, 'name' => 'Bases de Données & Modélisation UML', 'is_active' => true]);

        // Filière 3 : BTS Systèmes Réseaux, Télécoms & Cloud (SRTC)
        $filiereSRTC_C1 = Seminar::create([
            'title' => 'BTS Systèmes Réseaux, Télécoms & Cloud — Classe 1',
            'description' => 'Filière 3 / Classe 1 : Administration serveurs Linux/Windows, virtualisation et sécurité cloud.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereSRTC_C1->departments()->attach([$deptInfo->id]);
        $topSRTC_C1_Sys = Topic::create(['seminar_id' => $filiereSRTC_C1->id, 'name' => 'Administration Systèmes & Sécurité Linux', 'is_active' => true]);
        $topSRTC_C1_Cloud = Topic::create(['seminar_id' => $filiereSRTC_C1->id, 'name' => 'Cloud Computing & Virtualisation', 'is_active' => true]);

        $filiereSRTC_C2 = Seminar::create([
            'title' => 'BTS Systèmes Réseaux, Télécoms & Cloud — Classe 2',
            'description' => 'Filière 3 / Classe 2 : Télécommunications optiques, réseaux sans fil et architecture cloud.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereSRTC_C2->departments()->attach([$deptInfo->id]);
        $topSRTC_C2_Sys = Topic::create(['seminar_id' => $filiereSRTC_C2->id, 'name' => 'Administration Systèmes & Sécurité Linux', 'is_active' => true]);
        $topSRTC_C2_Cloud = Topic::create(['seminar_id' => $filiereSRTC_C2->id, 'name' => 'Cloud Computing & Virtualisation', 'is_active' => true]);

        $classesInfo = [
            ['seminar' => $filiereIIR_C1, 'topic' => $topIIR_C1_Reseaux, 'prefix' => 'IIR1'],
            ['seminar' => $filiereIIR_C2, 'topic' => $topIIR_C2_Reseaux, 'prefix' => 'IIR2'],
            ['seminar' => $filiereDAS_C1, 'topic' => $topDAS_C1_Dev, 'prefix' => 'DAS1'],
            ['seminar' => $filiereDAS_C2, 'topic' => $topDAS_C2_Dev, 'prefix' => 'DAS2'],
            ['seminar' => $filiereSRTC_C1, 'topic' => $topSRTC_C1_Sys, 'prefix' => 'SRTC1'],
            ['seminar' => $filiereSRTC_C2, 'topic' => $topSRTC_C2_Sys, 'prefix' => 'SRTC2'],
        ];

        // --- Autres Filières : Département Mécanique (2 filières) ---
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

        // --- Autres Filières : Département Électrique (2 filières) ---
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

        // =========================================================================
        // 5. LES 11 FORMATEURS DU DÉPARTEMENT INFORMATIQUE
        // =========================================================================
        $trainersInfoData = [
            [
                'name' => 'M. Amadou Sow',
                'email' => 'amadou.sow@cfptsj.sn',
                'phone' => '+221 77 412 88 90',
                'specialty' => 'Architecture Réseaux & Routage Cisco CCNA/CCNP',
                'seminar' => $filiereIIR_C1,
            ],
            [
                'name' => 'M. Mamadou Lamine Ndiaye',
                'email' => 'mamadou.ndiaye@cfptsj.sn',
                'phone' => '+221 77 520 14 33',
                'specialty' => 'Développement Web Fullstack (Laravel & Vue.js)',
                'seminar' => $filiereDAS_C1,
            ],
            [
                'name' => 'Mme Astou Diallo',
                'email' => 'astou.diallo@cfptsj.sn',
                'phone' => '+221 77 633 45 12',
                'specialty' => 'Bases de Données Relationnelles & NoSQL',
                'seminar' => $filiereDAS_C1,
            ],
            [
                'name' => 'M. Ousmane Diop',
                'email' => 'ousmane.diop@cfptsj.sn',
                'phone' => '+221 77 210 99 87',
                'specialty' => 'Systèmes Embarqués, IoT & Microcontrôleurs',
                'seminar' => $filiereIIR_C1,
            ],
            [
                'name' => 'M. Abdoulaye Ba',
                'email' => 'abdoulaye.ba@cfptsj.sn',
                'phone' => '+221 77 340 77 55',
                'specialty' => 'Cybersécurité, Pare-feu & Administration Linux',
                'seminar' => $filiereSRTC_C1,
            ],
            [
                'name' => 'Mme Fatou Kiné Fall',
                'email' => 'fatou.fall@cfptsj.sn',
                'phone' => '+221 77 888 22 11',
                'specialty' => 'Génie Logiciel, Modélisation UML & Méthodes Agiles',
                'seminar' => $filiereDAS_C2,
            ],
            [
                'name' => 'M. Babacar Faye',
                'email' => 'babacar.faye@cfptsj.sn',
                'phone' => '+221 77 910 66 44',
                'specialty' => 'Cloud Computing, Virtualisation Docker & Kubernetes',
                'seminar' => $filiereSRTC_C1,
            ],
            [
                'name' => 'M. Cheikh Tidiane Cissé',
                'email' => 'cheikh.cisse@cfptsj.sn',
                'phone' => '+221 77 125 36 47',
                'specialty' => 'Algorithmique & Programmation Python / IA',
                'seminar' => $filiereDAS_C2,
            ],
            [
                'name' => 'Mme Mariama Badji',
                'email' => 'mariama.badji@cfptsj.sn',
                'phone' => '+221 77 450 18 29',
                'specialty' => 'Architecture Matérielle & Maintenance Informatique',
                'seminar' => $filiereIIR_C2,
            ],
            [
                'name' => 'M. Ibrahima Sarr',
                'email' => 'ibrahima.sarr@cfptsj.sn',
                'phone' => '+221 77 789 44 20',
                'specialty' => 'Télécommunications, Fibre Optique & Réseaux Mobiles',
                'seminar' => $filiereSRTC_C2,
            ],
            [
                'name' => 'M. Modou Touré',
                'email' => 'modou.toure@cfptsj.sn',
                'phone' => '+221 77 601 23 88',
                'specialty' => 'DevOps, Intégration Continue & Automatisation',
                'seminar' => $filiereSRTC_C2,
            ],
        ];

        $trainersInfo = [];
        $allInfoSeminarIds = [$filiereIIR_C1->id, $filiereIIR_C2->id, $filiereDAS_C1->id, $filiereDAS_C2->id, $filiereSRTC_C1->id, $filiereSRTC_C2->id];

        foreach ($trainersInfoData as $tData) {
            $user = User::firstOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $trainerRole->id,
                    'department_id' => $deptInfo->id,
                    'specialty' => $tData['specialty'],
                ]
            );

            $trainer = Trainer::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $tData['phone'],
                'seminar_id' => $tData['seminar']->id,
                'remuneration' => 0,
                'payment_status' => 'paid',
            ]);
            // Attacher le formateur aux filières de son département
            $trainer->seminars()->attach($allInfoSeminarIds, ['remuneration' => 0, 'payment_status' => 'paid']);
            $trainersInfo[] = $trainer;
        }

        // Formateur Électrique
        $trUserElec = User::firstOrCreate(
            ['email' => 'fatou.gueye@cfptsj.sn'],
            [
                'name' => 'Mme Fatou Gueye',
                'password' => Hash::make('password'),
                'role_id' => $trainerRole->id,
                'department_id' => $deptElec->id,
                'specialty' => 'Automatisme Industriel & Électrotechnique',
            ]
        );
        $trainerElec = Trainer::create([
            'name' => $trUserElec->name,
            'email' => $trUserElec->email,
            'phone' => '+221 77 654 21 10',
            'seminar_id' => $filiereElec1->id,
            'remuneration' => 0,
            'payment_status' => 'paid',
        ]);
        $trainerElec->seminars()->attach([$filiereElec1->id, $filiereElec2->id], ['remuneration' => 0, 'payment_status' => 'paid']);

        // Formateur Mécanique
        $trUserMeca = User::firstOrCreate(
            ['email' => 'babacar.ndour@cfptsj.sn'],
            [
                'name' => 'M. Babacar Ndour',
                'password' => Hash::make('password'),
                'role_id' => $trainerRole->id,
                'department_id' => $deptMeca->id,
                'specialty' => 'Diagnostic Moteur & Mécanique',
            ]
        );
        $trainerMeca = Trainer::create([
            'name' => $trUserMeca->name,
            'email' => $trUserMeca->email,
            'phone' => '+221 77 321 45 67',
            'seminar_id' => $filiereMeca1->id,
            'remuneration' => 0,
            'payment_status' => 'paid',
        ]);
        $trainerMeca->seminars()->attach([$filiereMeca1->id, $filiereMeca2->id], ['remuneration' => 0, 'payment_status' => 'paid']);

        // =========================================================================
        // 6. LES ÉTUDIANTS DU DÉPARTEMENT INFO (~20 APPRENANTS PAR CLASSE x 6 = 120)
        // =========================================================================
        $senegaleseNames = [
            // Prénoms et Noms variés pour les 120 apprenants
            'Ousmane Diallo', 'Aïssatou Ba', 'Mamadou Lamine Sy', 'Fatou Kiné Diop', 'Cheikh Bamba Seck',
            'Khady Fall', 'Amadou Tidiane Ndiaye', 'Mariama Sow', 'Ibrahima Sarr', 'Aminata Cissé',
            'Abdoulaye Touré', 'Ramatoulaye Gueye', 'Babacar Kane', 'Mame Diarra Bousso', 'Mouhamadou Faye',
            'Seynabou Mbaye', 'Alioune Badara Diagne', 'Ndèye Coumba Thiam', 'Papa Demba Camara', 'Khadidiatou Samb',

            'Mansour Samb', 'Ndeye Fatou Diouf', 'Pape Momar Sene', 'Astou Ndour', 'Serigne Fallou Seck',
            'Salimata Kane', 'Alassane Ly', 'Maimouna Traore', 'Boubacar Baldé', 'Sokhna Mai Diop',
            'Daouda Traore', 'Diarra Ndao', 'El Hadji Malick Wade', 'Khadija Gomis', 'Moustapha Niang',
            'Awa Badiane', 'Tidiane Coly', 'Fatimata Sakho', 'Oumar Diallo', 'Adja Astou Diop',

            'Cheikh Ahmadou Bamba Fall', 'Binta Ndiaye', 'Souleymane Ba', 'Dieynaba Sow', 'Mamadou Dieng',
            'Aïda Thiaw', 'Modou Kara Mboup', 'Rokhaya Diouf', 'Lamine Gassama', 'Yaye Fatou Sarr',
            'Mamadou Faye', 'Ndeye Marie Ciss', 'Abdourahmane Dia', 'Coumba Diakhaté', 'Ibrahima Khalil Thiam',
            'Marie Madeleine Mendy', 'Alpha Oumar Diallo', 'Sokhna Anta Fall', 'Pape Amadou Seck', 'Nafissatou Niang',

            'Babacar Thiaw', 'Fatoumata Binetou Ba', 'Demba Sow', 'Ndèye Yacine Diop', 'Assane Ndiaye',
            'Aminata Diatta', 'Mouhamed Lamine Faye', 'Aissatou Diallo', 'Idrissa Manga', 'Marieme Sy',
            'Ousseynou Diagne', 'Fatou Bintou Sarr', 'Assane Cissé', 'Khady Diouf', 'Samba Ka',
            'Fama Ndao', 'El Hadji Oumar Ndiaye', 'Mame Fama Gueye', 'Lamine Sonko', 'Dior Mbaye',

            'Gorgui Ndiaye', 'Safietou Ly', 'Youssouph Badji', 'Amy Collé Seck', 'Mamadou Salif Diallo',
            'Mame Bousso Ndao', 'Cheikh Tidiane Diop', 'Marietou Faye', 'Abdou Karim Sané', 'Fatou Diagne',
            'Ibrahima Ndao', 'Khadija Sylla', 'Mamadou Moustapha Ba', 'Ndèye Awa Seck', 'Oumar Farouk Diop',
            'Ramatoulaye Thiam', 'Boucounta Cissé', 'Ndeye Khady Fall', 'Aly Diouf', 'Mame Diarra Dieng',

            'Seydina Oumar Ly', 'Fatou Seck', 'Mouhamadou Moustapha Kane', 'Aïssatou Ndiaye', 'Pape Landing Diatta',
            'Maimouna Diallo', 'Cheikhouna Bousso', 'Khadidiatou Sarr', 'Abdoulaye Diouf', 'Fatimata Binetou Sow',
            'Ibrahima Kalilou Ba', 'Coumba Ndoffene Diouf', 'Mamadou Alpha Diallo', 'Awa Cheikh Fall', 'Babacar Sène',
            'Marie Jeanne Mendy', 'Moussa Camara', 'Aminata Niang', 'Ousmane Sonko Mané', 'Binetou Diop'
        ];

        $globalStudentIndex = 0;
        $evalIIR1 = null;
        $evalIIR2 = null;

        foreach ($classesInfo as $classIndex => $classInfo) {
            $classSeminar = $classInfo['seminar'];
            $classTopic = $classInfo['topic'];
            $prefix = $classInfo['prefix'];

            // Créer une évaluation test pour la classe
            $trainerForClass = $trainersInfo[$classIndex % count($trainersInfo)];
            $evaluation = Evaluation::create([
                'seminar_id' => $classSeminar->id,
                'topic_id' => $classTopic->id,
                'trainer_id' => $trainerForClass->id,
                'title' => 'Évaluation N°1 — ' . $classTopic->name,
                'instructions' => "Directives de l'évaluation :\n1. Lire attentivement les consignes techniques.\n2. Réaliser les configurations et tester la validation des modules.\n3. Rédiger un compte rendu synthétique et téléverser votre livrable (PDF/ZIP) dans votre espace candidat.",
                'type' => 'devoir',
                'coefficient' => 1,
                'max_score' => 20,
                'evaluation_date' => '2026-11-20',
                'is_published' => true,
            ]);

            if ($classIndex === 0) {
                $evalIIR1 = $evaluation;
            }

            // Générer 20 étudiants pour cette classe
            for ($i = 1; $i <= 20; $i++) {
                $studentName = $senegaleseNames[$globalStudentIndex % count($senegaleseNames)];
                $globalStudentIndex++;

                $cleanName = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $studentName));
                $cleanName = preg_replace('/[^a-z0-9]/', '.', $cleanName);
                $email = "etu.{$prefix}.{$i}@etu.cfptsj.sn";
                $matricule = sprintf('CFPT-INF-%s-%02d', $prefix, $i);

                $stuUser = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $studentName,
                        'password' => Hash::make('password'),
                        'role_id' => $studentRole->id,
                        'department_id' => $deptInfo->id,
                    ]
                );

                $parts = explode(' ', $studentName, 2);
                CandidateProfile::updateOrCreate(
                    ['user_id' => $stuUser->id],
                    [
                        'first_names' => $parts[0] ?? $studentName,
                        'last_name' => $parts[1] ?? '',
                        'matricule' => $matricule,
                        'phone' => sprintf('+221 77 %03d %02d %02d', rand(100, 999), rand(10, 99), rand(10, 99)),
                        'nationality' => 'Sénégalaise',
                        'current_profession' => 'Étudiant CFPT',
                        'current_employer' => 'CFPT Sénégal-Japon',
                    ]
                );

                $reg = Registration::create([
                    'user_id' => $stuUser->id,
                    'seminar_id' => $classSeminar->id,
                    'topic_id' => $classTopic->id,
                    'status' => 'approved',
                ]);

                // Ajouter des notes et livrables pour quelques étudiants pour tester
                if ($i <= 5) {
                    $note = rand(12, 19);
                    Grade::create([
                        'evaluation_id' => $evaluation->id,
                        'registration_id' => $reg->id,
                        'score' => $note,
                        'appreciation' => $note >= 16 ? 'Excellent travail technique, très bonne maîtrise.' : 'Travail satisfaisant et sérieux.',
                    ]);

                    EvaluationSubmission::create([
                        'evaluation_id' => $evaluation->id,
                        'registration_id' => $reg->id,
                        'student_comment' => "Bonjour Monsieur, voici mon compte rendu pour l'évaluation N°1 de {$classTopic->name}.",
                        'submitted_at' => now()->subDays(rand(1, 4)),
                        'trainer_feedback' => $note >= 16 ? 'Très bonne rigueur méthodologique, continuez ainsi !' : 'Bon travail, attention à soigner la documentation.',
                    ]);
                }
            }
        }

        // Étudiants Mécanique & Électrique
        $studentsMecaElec = [
            ['name' => 'Mariama Sarr', 'email' => 'mariama.sarr@etu.cfptsj.sn', 'matricule' => 'CFPT-MEC-001', 'seminar' => $filiereMeca1, 'topic' => $topDiag],
            ['name' => 'Abdoulaye Wade', 'email' => 'abdoulaye.wade@etu.cfptsj.sn', 'matricule' => 'CFPT-ELE-001', 'seminar' => $filiereElec1, 'topic' => $topApi],
            ['name' => 'Khadija Thiam', 'email' => 'khadija.thiam@etu.cfptsj.sn', 'matricule' => 'CFPT-ELE-002', 'seminar' => $filiereElec1, 'topic' => $topPuissance],
        ];
        foreach ($studentsMecaElec as $sme) {
            $u = User::firstOrCreate(['email' => $sme['email']], ['name' => $sme['name'], 'password' => Hash::make('password'), 'role_id' => $studentRole->id]);
            $p = explode(' ', $sme['name'], 2);
            CandidateProfile::updateOrCreate(['user_id' => $u->id], ['first_names' => $p[0], 'last_name' => $p[1] ?? '', 'matricule' => $sme['matricule'], 'phone' => '+221 77 111 22 33', 'nationality' => 'Sénégalaise', 'current_profession' => 'Étudiant CFPT', 'current_employer' => 'CFPT Sénégal-Japon']);
            Registration::create(['user_id' => $u->id, 'seminar_id' => $sme['seminar']->id, 'topic_id' => $sme['topic']->id, 'status' => 'approved']);
        }

        // =========================================================================
        // 7. CONTENUS STYLE OPENCLASSROOMS (Chapitres, Discussions)
        // =========================================================================
        CourseChapter::create([
            'seminar_id' => $filiereIIR_C1->id,
            'topic_id' => $topIIR_C1_Reseaux->id,
            'trainer_id' => $trainersInfo[0]->id, // Amadou Sow
            'title' => 'Chapitre 1 — Architecture des Réseaux Industriels & Segmentation VLAN',
            'content' => "Bienvenue dans ce premier chapitre consacré à l'architecture des réseaux locaux industriels au CFPT Sénégal-Japon.\n\n1. Pourquoi segmenter un réseau industriel ?\nDans un environnement de production (usine, centrale électrique, atelier robotisé), la séparation des flux entre le réseau bureautique (IT) et le réseau des automates (OT) est indispensable pour garantir la sécurité et la réactivité temps réel.\n\n2. Configuration standard IEEE 802.1Q (Trunking) :\n- Création des VLANs par zone de production et département technique.\n- Affectation des ports de commutation en mode Access pour les équipements terminaux.\n- Configuration des liens Trunk 802.1Q entre commutateurs fédérateurs Cisco Catalyst.\n\nExercice pratique à préparer pour la prochaine séance au Labo Réseaux Info-1 :\nDéfinissez le plan d'adressage IP /24 pour 3 sous-réseaux (Administration, Automates, Laboratoire).",
            'duration_minutes' => 60,
            'order_index' => 1,
        ]);

        CourseChapter::create([
            'seminar_id' => $filiereDAS_C1->id,
            'topic_id' => $topDAS_C1_Dev->id,
            'trainer_id' => $trainersInfo[1]->id, // Mamadou Lamine Ndiaye
            'title' => 'Chapitre 1 — Conception MVC & Architecture des Applications Web Modernes',
            'content' => "Introduction à l'ingénierie logicielle avec le framework Laravel et l'architecture Modèle-Vue-Contrôleur.\n\nObjectifs d'apprentissage :\n- Structuration des routes, contrôleurs de ressources et requêtes de validation (Form Requests).\n- Modélisation Eloquent ORM : relations One-to-Many, Many-to-Many et pivots personnalisés.\n- Sécurité applicative : protection CSRF, hachage sécurisé bcrypt/argon2, et politiques d'autorisation (Policies).",
            'duration_minutes' => 90,
            'order_index' => 1,
        ]);

        if ($evalIIR1) {
            CourseDiscussion::create([
                'seminar_id' => $filiereIIR_C1->id,
                'topic_id' => $topIIR_C1_Reseaux->id,
                'evaluation_id' => $evalIIR1->id,
                'author_name' => 'Ousmane Diallo',
                'author_role' => 'Étudiant',
                'body' => 'Bonjour Monsieur Sow, pour l\'évaluation sur les VLANs, devons-nous configurer les routes statiques ou activer le protocole OSPF entre les commutateurs ?',
            ]);

            CourseDiscussion::create([
                'seminar_id' => $filiereIIR_C1->id,
                'topic_id' => $topIIR_C1_Reseaux->id,
                'evaluation_id' => $evalIIR1->id,
                'trainer_id' => $trainersInfo[0]->id,
                'author_name' => 'M. Amadou Sow',
                'author_role' => 'Formateur',
                'body' => 'Bonjour Ousmane, commencez par le routage inter-VLAN avec sous-interfaces Router-on-a-Stick, puis activez une route par défaut vers la passerelle. Bon travail !',
            ]);
        }

        // =========================================================================
        // 8. EMPLOIS DU TEMPS ANTI-CONFLIT DU DÉPARTEMENT INFO (8 Salles, 11 Formateurs)
        // =========================================================================
        // Aucun chevauchement : chaque formateur est dans 1 seule salle par créneau,
        // chaque salle n'accueille qu'1 seule classe par créneau.
        $timetableSlots = [
            // LUNDI 08h00 - 12h00 (4h)
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereIIR_C1->id,
                'topic_id' => $topIIR_C1_Reseaux->id,
                'trainer_id' => $trainersInfo[0]->id, // Amadou Sow
                'classroom_id' => $sallesInfo[0]->id, // Labo Réseaux Info-1
                'day_of_week' => 'Lundi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereDAS_C1->id,
                'topic_id' => $topDAS_C1_Dev->id,
                'trainer_id' => $trainersInfo[1]->id, // Mamadou Lamine Ndiaye
                'classroom_id' => $sallesInfo[1]->id, // Labo Dev Web Info-2
                'day_of_week' => 'Lundi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereSRTC_C1->id,
                'topic_id' => $topSRTC_C1_Sys->id,
                'trainer_id' => $trainersInfo[4]->id, // Abdoulaye Ba
                'classroom_id' => $sallesInfo[4]->id, // Labo Cybersécurité Info-5
                'day_of_week' => 'Lundi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],

            // LUNDI 14h00 - 18h00 (4h)
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereIIR_C2->id,
                'topic_id' => $topIIR_C2_Embarque->id,
                'trainer_id' => $trainersInfo[3]->id, // Ousmane Diop
                'classroom_id' => $sallesInfo[2]->id, // Labo Embarqué Info-3
                'day_of_week' => 'Lundi',
                'start_time' => '14:00',
                'end_time' => '18:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereDAS_C2->id,
                'topic_id' => $topDAS_C2_Dev->id,
                'trainer_id' => $trainersInfo[5]->id, // Fatou Kiné Fall
                'classroom_id' => $sallesInfo[5]->id, // Salle Magistrale Info-6
                'day_of_week' => 'Lundi',
                'start_time' => '14:00',
                'end_time' => '18:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereSRTC_C2->id,
                'topic_id' => $topSRTC_C2_Cloud->id,
                'trainer_id' => $trainersInfo[6]->id, // Babacar Faye
                'classroom_id' => $sallesInfo[3]->id, // Salle Multimédia Info-4
                'day_of_week' => 'Lundi',
                'start_time' => '14:00',
                'end_time' => '18:00',
                'planned_hours' => 4,
            ],

            // MARDI 08h00 - 12h00 (4h)
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereIIR_C2->id,
                'topic_id' => $topIIR_C2_Reseaux->id,
                'trainer_id' => $trainersInfo[0]->id, // Amadou Sow
                'classroom_id' => $sallesInfo[0]->id, // Labo Réseaux Info-1
                'day_of_week' => 'Mardi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereDAS_C1->id,
                'topic_id' => $topDAS_C1_Bdd->id,
                'trainer_id' => $trainersInfo[2]->id, // Astou Diallo
                'classroom_id' => $sallesInfo[6]->id, // Salle BDD Info-7
                'day_of_week' => 'Mardi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereSRTC_C1->id,
                'topic_id' => $topSRTC_C1_Cloud->id,
                'trainer_id' => $trainersInfo[6]->id, // Babacar Faye
                'classroom_id' => $sallesInfo[4]->id, // Labo Cybersécurité Info-5
                'day_of_week' => 'Mardi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],

            // MERCREDI 08h00 - 12h00 (4h)
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereIIR_C1->id,
                'topic_id' => $topIIR_C1_Embarque->id,
                'trainer_id' => $trainersInfo[3]->id, // Ousmane Diop
                'classroom_id' => $sallesInfo[2]->id, // Labo Embarqué Info-3
                'day_of_week' => 'Mercredi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereDAS_C2->id,
                'topic_id' => $topDAS_C2_Bdd->id,
                'trainer_id' => $trainersInfo[7]->id, // Cheikh Tidiane Cissé
                'classroom_id' => $sallesInfo[6]->id, // Salle BDD Info-7
                'day_of_week' => 'Mercredi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereSRTC_C2->id,
                'topic_id' => $topSRTC_C2_Sys->id,
                'trainer_id' => $trainersInfo[9]->id, // Ibrahima Sarr
                'classroom_id' => $sallesInfo[7]->id, // Labo Maintenance Info-8
                'day_of_week' => 'Mercredi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],

            // JEUDI 08h00 - 12h00 (4h)
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereDAS_C1->id,
                'topic_id' => $topDAS_C1_Dev->id,
                'trainer_id' => $trainersInfo[1]->id, // Mamadou Lamine Ndiaye
                'classroom_id' => $sallesInfo[1]->id, // Labo Dev Web Info-2
                'day_of_week' => 'Jeudi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereIIR_C2->id,
                'topic_id' => $topIIR_C2_Reseaux->id,
                'trainer_id' => $trainersInfo[8]->id, // Mariama Badji
                'classroom_id' => $sallesInfo[7]->id, // Labo Maintenance Info-8
                'day_of_week' => 'Jeudi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereSRTC_C1->id,
                'topic_id' => $topSRTC_C1_Sys->id,
                'trainer_id' => $trainersInfo[10]->id, // Modou Touré
                'classroom_id' => $sallesInfo[4]->id, // Labo Cybersécurité Info-5
                'day_of_week' => 'Jeudi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],

            // VENDREDI 08h30 - 12h30 (4h)
            [
                'department_id' => $deptInfo->id,
                'seminar_id' => $filiereSRTC_C2->id,
                'topic_id' => $topSRTC_C2_Cloud->id,
                'trainer_id' => $trainersInfo[10]->id, // Modou Touré
                'classroom_id' => $sallesInfo[4]->id, // Labo Cybersécurité Info-5
                'day_of_week' => 'Vendredi',
                'start_time' => '08:30',
                'end_time' => '12:30',
                'planned_hours' => 4,
            ],

            // Salles Mécanique & Électrique
            [
                'department_id' => $deptMeca->id,
                'seminar_id' => $filiereMeca1->id,
                'topic_id' => $topDiag->id,
                'trainer_id' => $trainerMeca->id,
                'classroom_id' => $roomMeca1->id,
                'day_of_week' => 'Mardi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
            [
                'department_id' => $deptElec->id,
                'seminar_id' => $filiereElec1->id,
                'topic_id' => $topApi->id,
                'trainer_id' => $trainerElec->id,
                'classroom_id' => $roomElec1->id,
                'day_of_week' => 'Mercredi',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'planned_hours' => 4,
            ],
        ];

        foreach ($timetableSlots as $slot) {
            Timetable::create($slot + ['is_active' => true]);
        }

        // =========================================================================
        // 9. FORMATIONS CONTINUES (À la demande des entreprises partenaires)
        // =========================================================================
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

        // Inscription des stagiaires professionnels
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
