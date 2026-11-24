<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$forms = \App\Models\FormTemplate::all();
echo "Nombre de formulaires dynamiques existants : " . $forms->count() . "\n";
foreach ($forms as $form) {
    echo "---------------------------------------------------------\n";
    echo "ID          : " . $form->id . "\n";
    echo "Titre       : " . $form->title . "\n";
    echo "Statut      : " . ($form->is_active ? 'ACTIF (En ligne)' : 'INACTIF (Brouillon/Désactivé)') . "\n";
    echo "Lien Public : http://127.0.0.1:8000/f/" . $form->share_token . "\n";
    echo "Lien Admin  : http://127.0.0.1:8000/admin/forms/" . $form->id . "/edit\n";
    echo "Réponses    : " . $form->submissions()->count() . " soumission(s)\n";
}
