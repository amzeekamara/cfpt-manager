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
use App\Models\FormTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        // 1. Année Académique active
        $cohort = Cohort::create([
            'title' => 'Année Académique 2026 - 2027',
            'start_date' => '2026-10-01',
            'end_date' => '2027-07-31',
            'status' => 'active',
        ]);

        // 2. Chefs de Département & Départements du CFPT Sénégal-Japon
        $headInfo = User::firstOrCreate(
            ['email' => 'chef.info@cfptsj.sn'],
            [
                'name' => 'Dr. Moussa Ndiaye',
                'password' => Hash::make('password'),
                'role_id' => $deptHeadRole->id,
                'position' => 'Chef du Département Informatique & Réseaux',
            ]
        );

        $headElectro = User::firstOrCreate(
            ['email' => 'chef.electro@cfptsj.sn'],
            [
                'name' => 'Ing. Ibrahima Fall',
                'password' => Hash::make('password'),
                'role_id' => $deptHeadRole->id,
                'position' => 'Chef du Département Électrotechnique & Automatisme',
            ]
        );

        $headAuto = User::firstOrCreate(
            ['email' => 'chef.auto@cfptsj.sn'],
            [
                'name' => 'M. Cheikh Diop',
                'password' => Hash::make('password'),
                'role_id' => $deptHeadRole->id,
                'position' => 'Chef du Département Maintenance Automobile & Engins',
            ]
        );

        $deptInfo = Department::create([
            'name' => 'Informatique Industrielle & Réseaux',
            'head_user_id' => $headInfo->id,
            'description' => 'BTS Informatique Industrielle, Réseaux, Systèmes Embarqués et Développement',
            'is_active' => true,
        ]);
        $headInfo->update(['department_id' => $deptInfo->id]);

        $deptElectro = Department::create([
            'name' => 'Électrotechnique, Froid & Automatisme',
            'head_user_id' => $headElectro->id,
            'description' => 'BTS Électrotechnique, Automatisme Industriel, Froid et Climatisation',
            'is_active' => true,
        ]);
        $headElectro->update(['department_id' => $deptElectro->id]);

        $deptAuto = Department::create([
            'name' => 'Mécanique Automobile & Engins Lourds',
            'head_user_id' => $headAuto->id,
            'description' => 'BTS Maintenance des Véhicules Moteurs, Engins Lourds et Structure Métallique',
            'is_active' => true,
        ]);
        $headAuto->update(['department_id' => $deptAuto->id]);

        // 3. Salles de cours, Ateliers & Laboratoires par département
        $roomInfo1 = Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Labo Cisco & Réseaux B101', 'capacity' => 28, 'type' => 'labo']);
        $roomInfo2 = Classroom::create(['department_id' => $deptInfo->id, 'name' => 'Salle Informatique B102', 'capacity' => 35, 'type' => 'cours']);

        $roomElec1 = Classroom::create(['department_id' => $deptElectro->id, 'name' => 'Atelier Automatisme & API A201', 'capacity' => 24, 'type' => 'atelier']);
        $roomElec2 = Classroom::create(['department_id' => $deptElectro->id, 'name' => 'Salle Électrotechnique A202', 'capacity' => 32, 'type' => 'cours']);

        $roomAuto1 = Classroom::create(['department_id' => $deptAuto->id, 'name' => 'Hangar Diagnostic Auto C101', 'capacity' => 25, 'type' => 'atelier']);
        $roomAuto2 = Classroom::create(['department_id' => $deptAuto->id, 'name' => 'Salle Mécanique C102', 'capacity' => 30, 'type' => 'cours']);

        // 4. Filières & Cours (Seminars) et Matières / Modules (Topics)
        $filiereInfo = Seminar::create([
            'title' => 'BTS Informatique Industrielle & Réseaux (2ème Année)',
            'description' => 'Formation technique supérieure en administration réseaux, programmation embarquée, bases de données et supervision industrielle au CFPT Sénégal-Japon.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 30,
            'status' => 'active',
        ]);
        $filiereInfo->departments()->attach([$deptInfo->id]);

        $topReseaux = Topic::create(['seminar_id' => $filiereInfo->id, 'name' => 'Architecture Réseaux & Sécurité', 'is_active' => true]);
        $topEmbarque = Topic::create(['seminar_id' => $filiereInfo->id, 'name' => 'Systèmes Embarqués & Microcontrôleurs', 'is_active' => true]);
        $topDevWeb = Topic::create(['seminar_id' => $filiereInfo->id, 'name' => 'Génie Logiciel & Bases de Données', 'is_active' => true]);

        $filiereElectro = Seminar::create([
            'title' => 'BTS Automatisme & Électrotechnique (1ère Année)',
            'description' => 'Maîtrise des automates programmables industriels (API), installations électriques, électronique de puissance et régulation.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereElectro->departments()->attach([$deptElectro->id]);

        $topApi = Topic::create(['seminar_id' => $filiereElectro->id, 'name' => 'Automates Programmables (API Siemens/Omron)', 'is_active' => true]);
        $topPuissance = Topic::create(['seminar_id' => $filiereElectro->id, 'name' => 'Électronique de Puissance & Schémas', 'is_active' => true]);

        $filiereAuto = Seminar::create([
            'title' => 'BT Maintenance Véhicules & Engins Lourds',
            'description' => 'Diagnostic électronique moteur, hydraulique, transmission et maintenance préventive selon la méthode Kaizen.',
            'cohort_id' => $cohort->id,
            'start_date' => '2026-10-05',
            'end_date' => '2027-06-30',
            'max_participants' => 25,
            'status' => 'active',
        ]);
        $filiereAuto->departments()->attach([$deptAuto->id]);

        $topDiag = Topic::create(['seminar_id' => $filiereAuto->id, 'name' => 'Diagnostic Électronique & Injection', 'is_active' => true]);

        // 5. Formateurs
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
            'seminar_id' => $filiereInfo->id,
            'remuneration' => 0,
            'payment_status' => 'paid',
        ]);
        $trainer1->seminars()->attach($filiereInfo->id, ['remuneration' => 0, 'payment_status' => 'paid']);

        $trUser2 = User::firstOrCreate(
            ['email' => 'fatou.gueye@cfptsj.sn'],
            [
                'name' => 'Mme Fatou Gueye',
                'password' => Hash::make('password'),
                'role_id' => $trainerRole->id,
                'department_id' => $deptElectro->id,
                'specialty' => 'Automatisme Industriel & API',
            ]
        );
        $trainer2 = Trainer::create([
            'name' => $trUser2->name,
            'email' => $trUser2->email,
            'phone' => '+221 77 654 21 10',
            'seminar_id' => $filiereElectro->id,
            'remuneration' => 0,
            'payment_status' => 'paid',
        ]);
        $trainer2->seminars()->attach($filiereElectro->id, ['remuneration' => 0, 'payment_status' => 'paid']);

        // 6. Étudiants inscrits
        $studentsData = [
            ['name' => 'Ousmane Diallo', 'email' => 'ousmane.diallo@etu.cfptsj.sn', 'matricule' => 'CFPT-2026-001', 'seminar' => $filiereInfo, 'topic' => $topReseaux, 'score1' => 16.5, 'score2' => 15.0],
            ['name' => 'Aïssatou Ba', 'email' => 'aissatou.ba@etu.cfptsj.sn', 'matricule' => 'CFPT-2026-002', 'seminar' => $filiereInfo, 'topic' => $topReseaux, 'score1' => 18.0, 'score2' => 17.5],
            ['name' => 'Mamadou Lamine Sy', 'email' => 'mamadou.sy@etu.cfptsj.sn', 'matricule' => 'CFPT-2026-003', 'seminar' => $filiereInfo, 'topic' => $topEmbarque, 'score1' => 13.5, 'score2' => 14.0],
            ['name' => 'Mariama Sarr', 'email' => 'mariama.sarr@etu.cfptsj.sn', 'matricule' => 'CFPT-2026-004', 'seminar' => $filiereInfo, 'topic' => $topDevWeb, 'score1' => 15.0, 'score2' => 16.0],
            ['name' => 'Abdoulaye Wade', 'email' => 'abdoulaye.wade@etu.cfptsj.sn', 'matricule' => 'CFPT-2026-005', 'seminar' => $filiereElectro, 'topic' => $topApi, 'score1' => 14.5, 'score2' => 13.0],
            ['name' => 'Khadija Thiam', 'email' => 'khadija.thiam@etu.cfptsj.sn', 'matricule' => 'CFPT-2026-006', 'seminar' => $filiereElectro, 'topic' => $topPuissance, 'score1' => 17.0, 'score2' => 16.5],
        ];

        $evalInfo1 = Evaluation::create([
            'seminar_id' => $filiereInfo->id,
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
            'seminar_id' => $filiereInfo->id,
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
                    'current_profession' => 'Étudiant Technicien Supérieur',
                    'current_employer' => 'CFPT Sénégal-Japon',
                ]
            );
            $reg = Registration::create([
                'user_id' => $stuUser->id,
                'seminar_id' => $s['seminar']->id,
                'topic_id' => $s['topic']->id,
                'status' => 'approved',
            ]);

            if ($s['seminar']->id === $filiereInfo->id) {
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

        // 7. Emplois du temps réalistes (Lundi à Samedi)
        $slots = [
            ['department_id' => $deptInfo->id, 'seminar_id' => $filiereInfo->id, 'topic_id' => $topReseaux->id, 'trainer_id' => $trainer1->id, 'classroom_id' => $roomInfo1->id, 'day_of_week' => 'Lundi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
            ['department_id' => $deptInfo->id, 'seminar_id' => $filiereInfo->id, 'topic_id' => $topEmbarque->id, 'trainer_id' => $trainer1->id, 'classroom_id' => $roomInfo2->id, 'day_of_week' => 'Mardi', 'start_time' => '14:00', 'end_time' => '17:00', 'planned_hours' => 3],
            ['department_id' => $deptInfo->id, 'seminar_id' => $filiereInfo->id, 'topic_id' => $topDevWeb->id, 'trainer_id' => $trainer1->id, 'classroom_id' => $roomInfo2->id, 'day_of_week' => 'Jeudi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
            ['department_id' => $deptElectro->id, 'seminar_id' => $filiereElectro->id, 'topic_id' => $topApi->id, 'trainer_id' => $trainer2->id, 'classroom_id' => $roomElec1->id, 'day_of_week' => 'Mercredi', 'start_time' => '08:00', 'end_time' => '12:00', 'planned_hours' => 4],
            ['department_id' => $deptElectro->id, 'seminar_id' => $filiereElectro->id, 'topic_id' => $topPuissance->id, 'trainer_id' => $trainer2->id, 'classroom_id' => $roomElec2->id, 'day_of_week' => 'Vendredi', 'start_time' => '09:00', 'end_time' => '12:00', 'planned_hours' => 3],
        ];

        foreach ($slots as $slot) {
            Timetable::create($slot + ['is_active' => true]);
        }
    }
}
