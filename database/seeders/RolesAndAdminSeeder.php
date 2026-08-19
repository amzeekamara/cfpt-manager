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
            'description' => 'Administrateur système'
        ]);

        $coordinatorRole = Role::firstOrCreate(['name' => 'Coordinateur'], [
            'description' => 'Coordinateur de programme'
        ]);

        $candidateRole = Role::firstOrCreate(['name' => 'Candidat'], [
            'description' => 'Candidat aux séminaires'
        ]);

        Role::firstOrCreate(['name' => 'Formateur'], [
            'description' => 'Formateur du programme'
        ]);

        Role::firstOrCreate(['name' => 'Agent JICA'], [
            'description' => 'Représentant de la JICA'
        ]);

        // 2. Admin account
        User::firstOrCreate(
            ['email' => 'admin@cfptsj.sn'],
            [
                'name' => 'Administrateur PFPT',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );
    }
}
