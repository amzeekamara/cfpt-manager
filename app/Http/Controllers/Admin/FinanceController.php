<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edition;
use App\Models\Expense;
use App\Models\Intervenant;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $editions = Edition::orderBy('start_date', 'desc')->get();
        $editionId = $request->get('edition_id');

        $selectedEdition = null;
        if ($editionId) {
            $selectedEdition = Edition::find($editionId);
        }

        if (!$selectedEdition) {
            $selectedEdition = Edition::where('status', 'active')->latest()->first() 
                ?? Edition::latest()->first();
        }
        
        $totalBudget = $selectedEdition ? $selectedEdition->total_budget : 0;
        
        $intervenantPay = Intervenant::whereHas('seminar', function($q) use ($selectedEdition) {
            if ($selectedEdition) $q->where('edition_id', $selectedEdition->id);
        })->sum('remuneration');

        $otherExpenses = Expense::where('edition_id', $selectedEdition ? $selectedEdition->id : null)->sum('amount');
        
        $totalSpent = $intervenantPay + $otherExpenses;
        $remainingBudget = $totalBudget - $totalSpent;

        $recentExpenses = Expense::with(['edition', 'seminar'])
            ->when($selectedEdition, function($q) use ($selectedEdition) {
                return $q->where('edition_id', $selectedEdition->id);
            })
            ->orderBy('expense_date', 'desc')
            ->take(10)
            ->get();

        $seminars = \App\Models\Seminar::when($selectedEdition, function($q) use ($selectedEdition) {
            return $q->where('edition_id', $selectedEdition->id);
        })->get();

        return view('admin.finances', compact(
            'selectedEdition', 
            'editions',
            'totalBudget', 
            'totalSpent', 
            'remainingBudget', 
            'intervenantPay', 
            'otherExpenses',
            'recentExpenses',
            'seminars'
        ));
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'edition_id' => 'required|exists:editions,id',
            'seminar_id' => 'nullable|exists:seminars,id',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::create($validated);

        \App\Helpers\ActivityLogger::log(
            'Nouvelle Dépense',
            "Une dépense de {$expense->amount} FCFA ('{$expense->description}') a été enregistrée.",
            $expense->edition
        );

        return back()->with('success', 'Dépense enregistrée avec succès !');
    }

    public function storeIntervenant(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'seminar_id' => 'required|exists:seminars,id',
            'remuneration' => 'required|numeric|min:0',
        ]);

        $intervenant = Intervenant::create($validated + ['payment_status' => 'pending']);

        \App\Helpers\ActivityLogger::log(
            'Paiement Intervenant',
            "Un intervenant ('{$intervenant->name}') a été ajouté pour un montant de {$intervenant->remuneration} FCFA.",
            $intervenant->seminar->edition
        );

        return back()->with('success', 'Intervenant ajouté avec succès !');
    }

    public function export(Request $request)
    {
        $editionId = $request->get('edition_id');
        $edition = Edition::findOrFail($editionId);
        
        $expenses = Expense::where('edition_id', $editionId)->get();
        $intervenants = Intervenant::whereHas('seminar', function($q) use ($editionId) {
            $q->where('edition_id', $editionId);
        })->get();

        return view('admin.finances.export', compact('edition', 'expenses', 'intervenants'));
    }
}
