<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Edition;
use App\Models\Seminar;
use App\Models\Enrollment;
use App\Models\Expense;
use App\Models\Intervenant;
use Illuminate\Support\Facades\DB;

class GlobalHealthCheck extends Command
{
    protected $signature = 'app:health-check';
    protected $description = 'Vérification globale de la plateforme PFPT';

    public function handle()
    {
        $this->header("DÉBUT DU TEST DE FONCTIONNALITÉ GLOBALE");

        // 1. Base de données et Migrations
        $this->section("1. Infrastructure");
        try {
            DB::connection()->getPdo();
            $this->info("✔ Connexion base de données : OK");
        } catch (\Exception $e) {
            $this->error("✘ Connexion base de données : ÉCHEC (" . $e->getMessage() . ")");
            return;
        }

        // 2. Gestion des Rôles et Utilisateurs
        $this->section("2. Authentification et Rôles");
        $userCount = User::count();
        $adminCount = User::whereHas('role', function($q){ $q->where('name', 'Admin'); })->count();
        $candidateCount = User::whereHas('role', function($q){ $q->where('name', 'Candidate'); })->count();
        $this->line("- Total Utilisateurs : $userCount");
        $this->line("- Administrateurs : $adminCount");
        $this->line("- Candidats : $candidateCount");
        
        if ($adminCount > 0) $this->info("✔ Système d'utilisateurs : OPÉRATIONNEL");
        else $this->warn("! Aucun administrateur trouvé.");

        // 3. Gestion des Éditions (Cycles)
        $this->section("3. Gestion des Éditions");
        $editions = Edition::count();
        $activeEdition = Edition::where('status', 'active')->first();
        $this->line("- Total Éditions : $editions");
        if ($activeEdition) {
            $this->info("✔ Édition Active : {$activeEdition->title} (Budget: {$activeEdition->budget} CFA)");
        } else {
            $this->warn("! Aucune édition active actuellement.");
        }

        // 4. Séminaires et Candidatures
        $this->section("4. Séminaires et Candidatures");
        $seminarCount = Seminar::count();
        $enrollmentCount = Enrollment::count();
        $pendingEnrollments = Enrollment::where('status', 'pending')->count();
        $this->line("- Total Séminaires : $seminarCount");
        $this->line("- Total Candidatures : $enrollmentCount");
        $this->line("- Candidatures en attente : $pendingEnrollments");
        
        if ($seminarCount > 0) $this->info("✔ Module Séminaire : OPÉRATIONNEL");
        else $this->warn("! Aucun séminaire créé.");

        // 5. Finance
        $this->section("5. Module Financier");
        $expenseTotal = Expense::sum('amount');
        $intervenantPay = Intervenant::sum('payment_amount');
        $this->line("- Total Dépenses Logistiques : " . number_format($expenseTotal, 0, ',', ' ') . " CFA");
        $this->line("- Total Honoraires Intervenants : " . number_format($intervenantPay, 0, ',', ' ') . " CFA");
        $this->info("✔ Module Finance : OPÉRATIONNEL");

        // 6. Système de Notifications (Log)
        $this->section("6. Système de Notifications");
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $lastMails = substr_count(file_get_contents($logFile), 'local.DEBUG: Message-ID:');
            $this->line("- Emails tracés dans les logs : $lastMails");
            $this->info("✔ Notification (Log Driver) : OPÉRATIONNEL");
        }

        $this->header("TEST DE FONCTIONNALITÉ TERMINÉ");
    }

    private function header($text)
    {
        $this->line("");
        $this->line(str_repeat("=", 60));
        $this->info(" $text ");
        $this->line(str_repeat("=", 60));
    }

    private function section($text)
    {
        $this->line("");
        $this->comment("--- $text ---");
    }
}
