<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cohort;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CohortFinanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cohort_calculates_expenses(): void
    {
        $cohort = Cohort::create([
            'title' => 'Édition 2026',
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-31',
            'total_budget' => 50000000,
            'status' => 'active',
        ]);

        Expense::create([
            'cohort_id' => $cohort->id,
            'amount' => 15000000,
            'description' => 'Achat matériel informatique',
            'expense_date' => '2026-09-10',
        ]);

        Expense::create([
            'cohort_id' => $cohort->id,
            'amount' => 5000000,
            'description' => 'Frais de restauration',
            'expense_date' => '2026-09-15',
        ]);

        $totalSpent = $cohort->expenses()->sum('amount');
        $remaining = $cohort->total_budget - $totalSpent;

        $this->assertEquals(20000000, $totalSpent);
        $this->assertEquals(30000000, $remaining);
    }
}
