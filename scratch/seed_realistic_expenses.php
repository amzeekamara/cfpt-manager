<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;

$cohort = Cohort::where('status', 'active')->first() ?? Cohort::first();
if (!$cohort) {
    die("Aucune cohorte trouvée.\n");
}

// Si le budget total est 0, on met un budget réaliste
if ($cohort->total_budget <= 0) {
    $cohort->update(['total_budget' => 20000000]); // 20 Millions FCFA
    echo "Budget total de la cohorte '{$cohort->title}' fixé à 20 000 000 FCFA.\n";
}

$seminar = Seminar::where('cohort_id', $cohort->id)->first();
$seminarId = $seminar ? $seminar->id : null;

// Catégories
$catMateriel = ExpenseCategory::firstOrCreate(['name' => 'Matériel & Équipements']);
$catTransport = ExpenseCategory::firstOrCreate(['name' => 'Transport & Logistique']);
$catRestauration = ExpenseCategory::firstOrCreate(['name' => 'Restauration & Hébergement']);
$catAdmin = ExpenseCategory::firstOrCreate(['name' => 'Frais Administratifs']);

// Nettoyer les anciennes dépenses de test sommaires si souhaité
Expense::whereIn('description', ['bic', 'BUS CARBURANT'])->delete();

$expensesToCreate = [
    // 1. Fournitures de bureau & Pédagogie
    [
        'description' => 'Achat de fournitures pédagogiques (lot de marqueurs effaçables rechargeables et feutres tableau blanc)',
        'amount' => 45000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(12),
    ],
    [
        'description' => 'Achat de stylos à bille (bics bleus/noirs), chemises cartonnées et blocs-notes pour les séminaristes',
        'amount' => 35000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(11),
    ],

    // 2. Tenues de travail & Équipements de protection individuelle (EPI)
    [
        'description' => 'Achat de tenues de travail d’atelier (combinaisons et blouses techniques haute résistance pour travaux pratiques)',
        'amount' => 380000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(9),
    ],
    [
        'description' => 'Achat de chaussures de sécurité coquées normées ISO pour les ateliers de structure métallique et froid',
        'amount' => 420000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(8),
    ],

    // 3. Équipements audiovisuels & Affichage interactif
    [
        'description' => 'Acquisition d’écrans de télévision UHD 55 pouces pour projection des schémas techniques et cours interactifs',
        'amount' => 750000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(7),
    ],

    // 4. Transport, Location de Bus & Carburant
    [
        'description' => 'Location de bus climatisé grand confort (35 places) pour le transport quotidien des séminaristes (navettes hôtel / CFPT)',
        'amount' => 450000,
        'expense_category_id' => $catTransport->id,
        'expense_date' => Carbon::now()->subDays(5),
    ],
    [
        'description' => 'Achat de carburant (essence / gasoil) pour les navettes logistiques et le groupe électrogène de secours des ateliers',
        'amount' => 180000,
        'expense_category_id' => $catTransport->id,
        'expense_date' => Carbon::now()->subDays(4),
    ],
    [
        'description' => 'Frais de transport et déplacement pour visites techniques de terrain en milieu industriel',
        'amount' => 120000,
        'expense_category_id' => $catTransport->id,
        'expense_date' => Carbon::now()->subDays(2),
    ],

    // 5. Petit matériel technique consommable
    [
        'description' => 'Achat de petits outillages et consommables de découpe / soudure pour les ateliers pratiques',
        'amount' => 260000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(1),
    ],
];

foreach ($expensesToCreate as $item) {
    Expense::create([
        'cohort_id' => $cohort->id,
        'seminar_id' => $seminarId,
        'description' => $item['description'],
        'amount' => $item['amount'],
        'expense_category_id' => $item['expense_category_id'],
        'expense_date' => $item['expense_date'],
        'receipt_path' => null,
    ]);
    echo "✓ Dépense créée : {$item['description']} (" . number_format($item['amount'], 0, ',', ' ') . " FCFA)\n";
}

$totalExp = Expense::where('cohort_id', $cohort->id)->sum('amount');
echo "\nTotal des dépenses enregistrées pour '{$cohort->title}' : " . number_format($totalExp, 0, ',', ' ') . " FCFA sur un budget de " . number_format($cohort->total_budget, 0, ',', ' ') . " FCFA.\n";
