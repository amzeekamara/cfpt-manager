<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrateur'], [
            'description' => 'Directeur des Études / Administrateur général'
        ]);

        $deptHeadRole = Role::firstOrCreate(['name' => 'Chef de Département'], [
            'description' => 'Administrateur pédagogique de département'
        ]);

        $coordinatorRole = Role::firstOrCreate(['name' => 'Coordinateur'], [
            'description' => 'Coordinateur pédagogique'
        ]);

        $candidateRole = Role::firstOrCreate(['name' => 'Candidat'], [
            'description' => 'Étudiant inscrit au CFPT'
        ]);

        Role::firstOrCreate(['name' => 'Formateur'], [
            'description' => 'Formateur / Enseignant du CFPT'
        ]);

        // 2. Admin account (Directeur des Études)
        User::firstOrCreate(
            ['email' => 'admin@cfptsj.sn'],
            [
                'name' => 'Directeur des Études CFPT',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );
    }
}
