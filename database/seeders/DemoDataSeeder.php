<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Edition;
use App\Models\Seminar;
use App\Models\User;
use App\Models\Role;
use App\Models\Intervenant;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Editions
        $editions = Edition::factory()->count(3)->create();

        // 2. Create Seminars linked to Editions
        foreach ($editions as $edition) {
            $seminars = Seminar::factory()->count(5)->create([
                'edition_id' => $edition->id,
                'status' => 'active'
            ]);

            foreach ($seminars as $seminar) {
                // Add 1-2 Intervenants per seminar
                Intervenant::factory()->count(rand(1, 2))->create([
                    'seminar_id' => $seminar->id
                ]);

                // Add some expenses per seminar
                Expense::factory()->count(rand(1, 4))->create([
                    'edition_id' => $edition->id,
                    'seminar_id' => $seminar->id
                ]);
            }

            // General edition expenses
            Expense::factory()->count(3)->create([
                'edition_id' => $edition->id
            ]);
        }

        // 3. Create standalone seminars
        Seminar::factory()->count(5)->create(['edition_id' => null, 'status' => 'active']);

        // 4. Create Users (Candidates)
        $candidateRole = Role::where('name', 'Candidate')->first();
        if (!$candidateRole) {
            $candidateRole = Role::firstOrCreate(['name' => 'Candidate']);
        }

        $users = User::factory()->count(50)->create([
            'role_id' => $candidateRole->id
        ]);

        // 5. Create Enrollments
        $allSeminars = Seminar::all();
        foreach ($users as $user) {
            $participatingSeminars = $allSeminars->random(rand(0, 3));
            foreach ($participatingSeminars as $seminar) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'seminar_id' => $seminar->id,
                    'status' => $this->getRandomEnrollmentStatus($seminar),
                ]);
            }
        }
    }

    private function getRandomEnrollmentStatus($seminar)
    {
        if ($seminar->status === 'completed') return 'approved';
        return fake()->randomElement(['pending', 'approved', 'rejected', 'waiting_list']);
    }
}
