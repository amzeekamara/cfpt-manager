<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\User;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Cohorts
        $cohorts = Cohort::factory()->count(3)->create();

        // 2. Create Seminars linked to Cohorts
        foreach ($cohorts as $cohort) {
            $seminars = Seminar::factory()->count(5)->create([
                'cohort_id' => $cohort->id,
                'status' => 'active'
            ]);

            foreach ($seminars as $seminar) {
                // Add 1-2 Trainers per seminar
                Trainer::factory()->count(rand(1, 2))->create([
                    'seminar_id' => $seminar->id
                ]);

                // Add some expenses per seminar
                Expense::factory()->count(rand(1, 4))->create([
                    'cohort_id' => $cohort->id,
                    'seminar_id' => $seminar->id
                ]);
            }

            // General cohort expenses
            Expense::factory()->count(3)->create([
                'cohort_id' => $cohort->id
            ]);
        }

        // 3. Create standalone seminars
        Seminar::factory()->count(5)->create(['cohort_id' => null, 'status' => 'active']);

        // 4. Create Users (Candidates)
        $candidateRole = Role::where('name', 'Candidate')->first();
        if (!$candidateRole) {
            $candidateRole = Role::firstOrCreate(['name' => 'Candidate']);
        }

        $users = User::factory()->count(50)->create([
            'role_id' => $candidateRole->id
        ]);

        // 5. Create Registrations
        $allSeminars = Seminar::all();
        foreach ($users as $user) {
            $participatingSeminars = $allSeminars->random(rand(0, 3));
            foreach ($participatingSeminars as $seminar) {
                Registration::create([
                    'user_id' => $user->id,
                    'seminar_id' => $seminar->id,
                    'status' => $this->getRandomRegistrationStatus($seminar),
                ]);
            }
        }
    }

    private function getRandomRegistrationStatus($seminar)
    {
        if ($seminar->status === 'completed') return 'approved';
        return fake()->randomElement(['pending', 'approved', 'rejected', 'waiting_list']);
    }
}
