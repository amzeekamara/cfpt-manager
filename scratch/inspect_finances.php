<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = App\Models\ExpenseCategory::all();
$cohorts = App\Models\Cohort::all();
$seminars = App\Models\Seminar::all();
$expenses = App\Models\Expense::all();

echo "=== CATEGORIES ===\n";
foreach ($categories as $c) {
    echo "ID: {$c->id} | Name: {$c->name}\n";
}

echo "\n=== COHORTS ===\n";
foreach ($cohorts as $co) {
    echo "ID: {$co->id} | Title: {$co->title} | Status: {$co->status} | Budget: " . number_format($co->allocated_budget, 0, ',', ' ') . "\n";
}

echo "\n=== SEMINARS ===\n";
foreach ($seminars as $s) {
    echo "ID: {$s->id} | Title: {$s->title} | Cohort ID: {$s->cohort_id}\n";
}

echo "\n=== EXISTING EXPENSES COUNT: " . $expenses->count() . " ===\n";
foreach ($expenses as $e) {
    echo "ID: {$e->id} | Desc: {$e->description} | Amount: " . number_format($e->amount, 0, ',', ' ') . " | Cat: " . ($e->category?->name ?? 'N/A') . "\n";
}
