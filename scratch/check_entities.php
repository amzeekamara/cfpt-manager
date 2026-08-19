<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cohort;
use App\Models\Seminar;
use App\Models\Department;
use App\Models\Topic;
use App\Models\FormTemplate;

echo "=== CURRENT COHORTS ===\n";
foreach (Cohort::all() as $c) {
    echo "Cohort #{$c->id}: {$c->title} ({$c->status})\n";
}

echo "\n=== CURRENT SEMINARS ===\n";
foreach (Seminar::all() as $s) {
    echo "Seminar #{$s->id}: {$s->title} (Cohort #{$s->cohort_id}, Status: {$s->status})\n";
}

echo "\n=== CURRENT DEPARTMENTS ===\n";
foreach (Department::all() as $d) {
    echo "Dept #{$d->id}: {$d->name}\n";
}
