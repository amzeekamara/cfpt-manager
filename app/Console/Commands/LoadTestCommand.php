<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Registration;
use App\Models\CandidateProfile;
use App\Models\Role;
use App\Models\Seminar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LoadTestCommand extends Command
{
    protected $signature = 'app:load-test {count=50}';
    protected $description = 'Simule une charge d\'inscriptions simultanées';

    public function handle()
    {
        $count = (int) $this->argument('count');
        $this->info("DÉBUT DU TEST DE CHARGE : Simulation de $count inscriptions...");
        
        $seminar = Seminar::first();
        if (!$seminar) {
            $this->error("Aucun séminaire trouvé pour le test.");
            return;
        }

        $role = Role::where('name', 'Candidat')->first();
        $startTime = microtime(true);

        $this->withProgressBar($count, function ($i) use ($seminar, $role) {
            DB::transaction(function () use ($seminar, $role) {
                $email = "loadtest_" . Str::random(8) . "@example.com";
                $user = User::create([
                    'name' => "Tester " . Str::random(5),
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role_id' => $role?->id,
                ]);

                CandidateProfile::create([
                    'user_id' => $user->id,
                    'first_names' => 'Load',
                    'last_name' => 'Tester',
                    'birth_date' => '1990-01-01',
                    'birth_place' => 'Dakar',
                    'gender' => 'M',
                    'nationality' => 'Senegalese',
                    'residence_country' => 'Senegal',
                    'address' => 'Test Address, Dakar',
                    'phone' => '770000000',
                    'emergency_contact_name' => 'Emergency Contact',
                    'emergency_contact_phone' => '780000000',
                    'emergency_contact_relation' => 'Relative',
                    'accommodation_preference' => 'none',
                ]);

                Registration::create([
                    'user_id' => $user->id,
                    'seminar_id' => $seminar->id,
                    'status' => 'pending',
                ]);
            });
        });

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        $avgTime = $totalTime / $count;

        $this->line("");
        $this->info("RÉSULTATS DU TEST DE CHARGE :");
        $this->line("- Temps total : " . number_format($totalTime, 2) . " secondes");
        $this->line("- Temps moyen par inscription : " . number_format($avgTime, 3) . " secondes");
        $this->line("- Inscriptions par seconde : " . number_format(1 / $avgTime, 1));
        
        if ($avgTime < 0.2) {
            $this->info("✔ Performance Excellente");
        } elseif ($avgTime < 0.5) {
            $this->info("✔ Performance Bonne");
        } else {
            $this->warn("! Performance lente, optimisations possibles.");
        }

        // Nettoyage automatique
        User::where('email', 'like', 'loadtest_%')->delete();
        $this->info("Données de test nettoyées automatiquement.");
    }
}
