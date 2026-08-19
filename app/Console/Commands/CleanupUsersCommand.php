<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CleanupUsersCommand extends Command
{
    protected $signature = 'app:cleanup-users';
    protected $description = 'Supprime tous les utilisateurs sauf les administrateurs principaux';

    public function handle()
    {
        $this->info("Nettoyage des utilisateurs en cours...");

        // On identifie l'admin principal (celui qui a l'ID le plus petit, généralement le premier créé)
        $mainAdmin = User::whereHas('role', function($q) {
            $q->where('name', 'Administrateur');
        })->orderBy('id', 'asc')->first();

        if (!$mainAdmin) {
            $this->error("Aucun administrateur trouvé ! Annulation.");
            return;
        }

        $this->info("Administrateur préservé : {$mainAdmin->name} ({$mainAdmin->email})");

        $deletedCount = User::where('id', '!=', $mainAdmin->id)->delete();

        $this->info("Nombre d'utilisateurs supprimés : $deletedCount");
        $this->info("Seul l'administrateur principal reste dans la base.");
    }
}
