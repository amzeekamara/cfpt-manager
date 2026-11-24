<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Seminar::with('topics')->get() as $s) {
    echo "ID: {$s->id} | Title: {$s->title} | Start: {$s->start_date} | Status: {$s->status} | Open: " . ($s->isOpenForApplication() ? 'YES' : 'NO') . "\n";
    foreach ($s->topics as $t) {
        echo "   - Topic ID: {$t->id} | Name: {$t->name}\n";
    }
}
