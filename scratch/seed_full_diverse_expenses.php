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

$seminar = Seminar::where('cohort_id', $cohort->id)->first();
$seminarId = $seminar ? $seminar->id : null;

// S'assurer que toutes les catégories existent
$catRestauration = ExpenseCategory::firstOrCreate(['name' => 'Restauration & Hébergement']);
$catTransport = ExpenseCategory::firstOrCreate(['name' => 'Transport & Logistique']);
$catMateriel = ExpenseCategory::firstOrCreate(['name' => 'Matériel & Équipements']);
$catCommunication = ExpenseCategory::firstOrCreate(['name' => 'Communication & Marketing']);
$catAdmin = ExpenseCategory::firstOrCreate(['name' => 'Frais Administratifs']);

// Nettoyer les dépenses de la cohorte pour insérer un jeu de données complet, diversifié et réaliste
Expense::where('cohort_id', $cohort->id)->delete();

$diverseExpenses = [
    // 1. Restauration & Hébergement
    [
        'description' => 'Prestation traiteur et pauses-café pour les 4 semaines de séminaire (petit-déjeuner & déjeuners des formateurs)',
        'amount' => 1850000,
        'expense_category_id' => $catRestauration->id,
        'expense_date' => Carbon::now()->subDays(18),
    ],
    [
        'description' => 'Frais d’hébergement en résidence hôtelière partenaire pour les formateurs et experts régionaux',
        'amount' => 1400000,
        'expense_category_id' => $catRestauration->id,
        'expense_date' => Carbon::now()->subDays(16),
    ],
    [
        'description' => 'Cocktail officiel d’ouverture et d’accueil de la délégation ministérielle et JICA',
        'amount' => 350000,
        'expense_category_id' => $catRestauration->id,
        'expense_date' => Carbon::now()->subDays(15),
    ],

    // 2. Matériel & Équipements
    [
        'description' => 'Acquisition d’écrans de télévision UHD 55 pouces pour projection des schémas techniques et cours interactifs',
        'amount' => 750000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(14),
    ],
    [
        'description' => 'Achat de chaussures de sécurité coquées normées ISO pour les ateliers de structure métallique et froid',
        'amount' => 420000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(13),
    ],
    [
        'description' => 'Achat de tenues de travail d’atelier (combinaisons et blouses techniques haute résistance pour travaux pratiques)',
        'amount' => 380000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(12),
    ],
    [
        'description' => 'Achat de petits outillages et consommables de découpe / soudure pour les ateliers pratiques',
        'amount' => 260000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(11),
    ],
    [
        'description' => 'Achat de fournitures pédagogiques (lot de marqueurs effaçables rechargeables et feutres pour tableau blanc)',
        'amount' => 45000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(10),
    ],
    [
        'description' => 'Achat de stylos à bille (bics bleus/noirs), chemises cartonnées et blocs-notes pour les séminaristes',
        'amount' => 35000,
        'expense_category_id' => $catMateriel->id,
        'expense_date' => Carbon::now()->subDays(9),
    ],

    // 3. Transport & Logistique
    [
        'description' => 'Location de bus climatisé grand confort (35 places) pour le transport quotidien des séminaristes (navettes hôtel / CFPT)',
        'amount' => 450000,
        'expense_category_id' => $catTransport->id,
        'expense_date' => Carbon::now()->subDays(8),
    ],
    [
        'description' => 'Achat de carburant (essence / gasoil) pour les navettes logistiques et le groupe électrogène de secours',
        'amount' => 180000,
        'expense_category_id' => $catTransport->id,
        'expense_date' => Carbon::now()->subDays(7),
    ],
    [
        'description' => 'Frais de transport et déplacement pour visites techniques de terrain en milieu industriel',
        'amount' => 120000,
        'expense_category_id' => $catTransport->id,
        'expense_date' => Carbon::now()->subDays(6),
    ],

    // 4. Communication & Marketing
    [
        'description' => 'Impression des banderoles officielles, kakémonos et roll-ups événementiels du séminaire PFPT',
        'amount' => 160000,
        'expense_category_id' => $catCommunication->id,
        'expense_date' => Carbon::now()->subDays(5),
    ],
    [
        'description' => 'Conception et impression des badges magnétiques, pochettes et livrets d’accueil des participants',
        'amount' => 95000,
        'expense_category_id' => $catCommunication->id,
        'expense_date' => Carbon::now()->subDays(4),
    ],
    [
        'description' => 'Couverture médiatique et reportage photo / vidéo institutionnel de la cérémonie de remise des attestations',
        'amount' => 220000,
        'expense_category_id' => $catCommunication->id,
        'expense_date' => Carbon::now()->subDays(3),
    ],

    // 5. Frais Administratifs
    [
        'description' => 'Frais d’assurance responsabilité civile et couverture médicale d’urgence des stagiaires internationaux',
        'amount' => 280000,
        'expense_category_id' => $catAdmin->id,
        'expense_date' => Carbon::now()->subDays(2),
    ],
    [
        'description' => 'Fournitures d’impression administrative (toners d’imprimante haute capacité, rames de papier A4)',
        'amount' => 145000,
        'expense_category_id' => $catAdmin->id,
        'expense_date' => Carbon::now()->subDays(1),
    ],
    [
        'description' => 'Frais bancaires de virement et gestion financière de cohorte',
        'amount' => 45000,
        'expense_category_id' => $catAdmin->id,
        'expense_date' => Carbon::now(),
    ],
];

foreach ($diverseExpenses as $item) {
    Expense::create([
        'cohort_id' => $cohort->id,
        'seminar_id' => $seminarId,
        'description' => $item['description'],
        'amount' => $item['amount'],
        'expense_category_id' => $item['expense_category_id'],
        'expense_date' => $item['expense_date'],
        'receipt_path' => null,
    ]);
}

echo "✓ " . count($diverseExpenses) . " dépenses diversifiées enregistrées dans toutes les catégories !\n";
$totalExp = Expense::where('cohort_id', $cohort->id)->sum('amount');
echo "Total : " . number_format($totalExp, 0, ',', ' ') . " FCFA.\n";
