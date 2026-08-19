<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\Expense;
use App\Models\Trainer;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreExpenseRequest;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $cohorts = Cohort::orderBy('start_date', 'desc')->get();
        $cohortId = $request->get('cohort_id');

        $selectedCohort = null;
        if ($cohortId) {
            $selectedCohort = Cohort::find($cohortId);
        }

        if (!$selectedCohort) {
            $selectedCohort = Cohort::where('status', 'active')->latest()->first() 
                ?? Cohort::latest()->first();
        }
        
        $totalBudget = $selectedCohort ? $selectedCohort->total_budget : 0;
        
$startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $seminarId = $request->get('seminar_id');

        $otherExpensesQuery = Expense::where('cohort_id', $selectedCohort ? $selectedCohort->id : null);
        if ($startDate) $otherExpensesQuery->where('expense_date', '>=', $startDate);
        if ($endDate) $otherExpensesQuery->where('expense_date', '<=', $endDate);
        if ($seminarId) $otherExpensesQuery->where('seminar_id', $seminarId);
        $otherExpenses = $otherExpensesQuery->sum('amount');
        
        $trainerPayQuery = \DB::table('seminar_trainer')
            ->join('seminars', 'seminar_trainer.seminar_id', '=', 'seminars.id');
        
        if ($selectedCohort) {
            $trainerPayQuery->where('seminars.cohort_id', $selectedCohort->id);
        }
        if ($seminarId) {
            $trainerPayQuery->where('seminar_trainer.seminar_id', $seminarId);
        }
        if ($startDate) $trainerPayQuery->where('seminar_trainer.created_at', '>=', $startDate);
        if ($endDate) $trainerPayQuery->where('seminar_trainer.created_at', '<=', $endDate);

        $trainerPay = $trainerPayQuery->sum('seminar_trainer.remuneration');

        $categories = \App\Models\ExpenseCategory::orderBy('name')->get();
        
        $totalSpent = $trainerPay + $otherExpenses;
        $remainingBudget = $totalBudget - $totalSpent;
        
        $recentExpensesQuery = Expense::with(['cohort', 'seminar', 'category'])
            ->when($selectedCohort, function($q) use ($selectedCohort) {
                return $q->where('cohort_id', $selectedCohort->id);
            });
            
        if ($request->filled('expense_category_id')) {
            $recentExpensesQuery->where('expense_category_id', $request->get('expense_category_id'));
        }
        if ($request->filled('expense_seminar_id')) {
            $recentExpensesQuery->where('seminar_id', $request->get('expense_seminar_id'));
        }
        if ($request->filled('expense_start_date')) {
            $recentExpensesQuery->where('expense_date', '>=', $request->get('expense_start_date'));
        }
        if ($request->filled('expense_end_date')) {
            $recentExpensesQuery->where('expense_date', '<=', $request->get('expense_end_date'));
        }

        match ($request->get('expense_sort')) {
            'highest' => $recentExpensesQuery->orderByDesc('amount'),
            'lowest'  => $recentExpensesQuery->orderBy('amount'),
            default   => $recentExpensesQuery->orderByDesc('expense_date'),
        };
        
        $recentExpenses = $recentExpensesQuery->take(50)->get();

        $recentTrainerPaymentsQuery = \DB::table('seminar_trainer')
            ->join('trainers', 'seminar_trainer.trainer_id', '=', 'trainers.id')
            ->join('seminars', 'seminar_trainer.seminar_id', '=', 'seminars.id')
            ->select('trainers.name as trainer_name', 'seminars.title as seminar_title', 'seminar_trainer.remuneration', 'seminar_trainer.created_at', 'seminar_trainer.payment_status')
            ->when($selectedCohort, fn($q) => $q->where('seminars.cohort_id', $selectedCohort->id))
            ->orderByDesc('seminar_trainer.created_at');

        if ($request->filled('expense_seminar_id')) {
            $recentTrainerPaymentsQuery->where('seminar_trainer.seminar_id', $request->get('expense_seminar_id'));
        }
        if ($request->filled('expense_start_date')) {
            $recentTrainerPaymentsQuery->where('seminar_trainer.created_at', '>=', $request->get('expense_start_date'));
        }
        if ($request->filled('expense_end_date')) {
            $recentTrainerPaymentsQuery->where('seminar_trainer.created_at', '<=', $request->get('expense_end_date'));
        }

        $recentTrainerPayments = $recentTrainerPaymentsQuery->take(50)->get();

        $seminars = \App\Models\Seminar::with('topics')->when($selectedCohort, fn($q) => $q->where('cohort_id', $selectedCohort->id))->get();

        // --- Chart Data ---
        $financeChartData = [
            'labels' => ['Trainers', 'Autres Dépenses', 'Budget Restant'],
            'data'   => [$trainerPay, $otherExpenses, max(0, $remainingBudget)],
        ];

        // Group expenses by seminar
        $expensesBySeminar = \App\Models\Expense::with('seminar')
            ->when($selectedCohort, fn($q) => $q->where('cohort_id', $selectedCohort->id))
            ->selectRaw('seminar_id, sum(amount) as total')
            ->groupBy('seminar_id')
            ->get();
            
        $barChartData = [
            'labels' => $expensesBySeminar->map(fn($e) => $e->seminar ? \Illuminate\Support\Str::limit($e->seminar->title, 20) : 'Général')->toArray(),
            'data'   => $expensesBySeminar->pluck('total')->toArray(),
        ];

        $allTrainers = Trainer::orderBy('name')->get();

        // Budget by Theme (Estimated)
        $allTopics = \App\Models\Topic::whereHas('seminar', fn($q) => $selectedCohort ? $q->where('cohort_id', $selectedCohort->id) : $q)->with('seminar')->get();
        $topicsWithExpenses = [];

        foreach ($allTopics as $topic) {
            $semId = $topic->seminar_id;
            $seminarExpense = $expensesBySeminar->firstWhere('seminar_id', $semId);
            $totalSemExp = $seminarExpense ? $seminarExpense->total : 0;
            $trainerPaySem = \DB::table('seminar_trainer')->where('seminar_id', $semId)->sum('remuneration');
            $totalSemCost = $totalSemExp + $trainerPaySem;

            $topicCountInSem = $allTopics->where('seminar_id', $semId)->count();
            if ($topicCountInSem > 0) {
                $topicsWithExpenses[$topic->name] = ($topicsWithExpenses[$topic->name] ?? 0) + ($totalSemCost / $topicCountInSem);
            }
        }

        arsort($topicsWithExpenses);
        
        $topTopics = array_slice($topicsWithExpenses, 0, 7);
        $themeChartData = !empty($topTopics) ? [
            'labels' => array_map(fn($name) => \Illuminate\Support\Str::limit($name, 20), array_keys($topTopics)),
            'data'   => array_map(fn($amt) => round($amt), array_values($topTopics)),
        ] : ['labels' => ['Aucun'], 'data' => [0]];

        return view('admin.finances', compact(
            'selectedCohort', 
            'cohorts',
            'totalBudget', 
            'totalSpent', 
            'remainingBudget', 
            'trainerPay', 
            'otherExpenses',
            'recentExpenses',
            'recentTrainerPayments',
            'seminars',
            'financeChartData',
            'barChartData',
            'themeChartData',
            'categories',
            'allTrainers'
        ));
    }

    public function storeExpense(StoreExpenseRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        $expense = Expense::create($validated);

        ActivityLogger::log(
            'Nouvelle Dépense',
            "Une dépense de {$expense->amount} FCFA ('{$expense->description}') a été enregistrée.",
            $expense->cohort
        );

        return back()->with('success', 'Dépense enregistrée avec succès !');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
        ]);
        \App\Models\ExpenseCategory::create($validated);
        return back()->with('success', 'Catégorie ajoutée avec succès !');
    }

    public function destroyCategory(\App\Models\ExpenseCategory $category)
    {
        // Nullify foreign keys before deleting if nullOnDelete wasn't set, but it is.
        $category->delete();
        return back()->with('success', 'Catégorie supprimée avec succès !');
    }

    public function storeTrainer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'seminar_id' => 'required|exists:seminars,id',
            'remuneration' => 'required|numeric|min:0',
            'topic_ids' => 'nullable|array',
            'topic_ids.*' => 'exists:topics,id',
            'hours_count' => 'nullable|integer|min:0',
        ]);

        $themesCount = isset($validated['topic_ids']) ? count($validated['topic_ids']) : null;

        $trainer = Trainer::firstOrCreate(['name' => $validated['name']]);
        $trainer->seminars()->syncWithoutDetaching([
            $validated['seminar_id'] => [
                'remuneration' => $validated['remuneration'],
                'themes_count' => $themesCount,
                'selected_topics' => isset($validated['topic_ids']) ? json_encode($validated['topic_ids']) : null,
                'hours_count' => $validated['hours_count'] ?? null,
                'payment_status' => 'unpaid'
            ]
        ]);

        $seminar = \App\Models\Seminar::find($validated['seminar_id']);

        ActivityLogger::log(
            'Paiement Trainer',
            "Une rémunération de {$validated['remuneration']} FCFA a été attribuée au formateur '{$trainer->name}'.",
            $seminar->cohort
        );

        return back()->with('success', 'Trainer et rémunération ajoutés avec succès !');
    }

    public function export(Request $request)
    {
        $cohortId = $request->get('cohort_id');
        $cohort = Cohort::findOrFail($cohortId);
        
        $expenses = Expense::where('cohort_id', $cohortId)->get();
        $trainers = Trainer::whereHas('seminars', function($q) use ($cohortId) {
            $q->where('cohort_id', $cohortId);
        })->with(['seminars' => function($q) use ($cohortId) {
            $q->where('cohort_id', $cohortId);
        }])->get();
        
        $participants = \App\Models\Registration::whereHas('seminar', function($q) use ($cohortId) {
            $q->where('cohort_id', $cohortId);
        })->with(['user', 'seminar'])->get();

        return view('admin.finances.export', compact('cohort', 'expenses', 'trainers', 'participants'));
    }
}
