<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Topic;
use App\Models\FormTemplate;

echo "=== TOPICS ===\n";
foreach (Topic::all() as $t) {
    echo "Topic #{$t->id}: {$t->title} (Seminar #{$t->seminar_id}, Code: {$t->code})\n";
}

echo "=== FORM TEMPLATES ===\n";
foreach (FormTemplate::all() as $f) {
    echo "Form #{$f->id}: {$f->title} (Seminar #{$f->seminar_id}, Slug: {$f->slug})\n";
}
