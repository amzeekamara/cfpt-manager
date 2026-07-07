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
        $adminRole = Role::firstOrCreate(['name' => 'Admin'], [
            'description' => 'Administrateur système'
        ]);

        $secretaryRole = Role::firstOrCreate(['name' => 'Secretary'], [
            'description' => 'Secrétaire administratif'
        ]);

        $candidateRole = Role::firstOrCreate(['name' => 'Candidate'], [
            'description' => 'Candidat aux séminaires'
        ]);

        Role::firstOrCreate(['name' => 'Trainer'], [
            'description' => 'Formateur du programme'
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

        // 3. Secretary account (demo)
        User::firstOrCreate(
            ['email' => 'secretary@cfptsj.sn'],
            [
                'name' => 'Secrétaire Alpha',
                'password' => Hash::make('password'),
                'role_id' => $secretaryRole->id,
            ]
        );
    }
}
