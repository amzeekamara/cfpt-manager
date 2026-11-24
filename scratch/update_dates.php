<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s1 = \App\Models\Seminar::find(1);
if ($s1) {
    $s1->update([
        'start_date' => '2026-09-07 08:00:00',
        'end_date' => '2026-10-02 18:00:00',
        'status' => 'active'
    ]);
}

$s2 = \App\Models\Seminar::find(2);
if ($s2) {
    $s2->update([
        'start_date' => '2026-09-14 08:00:00',
        'end_date' => '2026-10-09 18:00:00',
        'status' => 'active'
    ]);
}

$s3 = \App\Models\Seminar::find(3);
if ($s3) {
    $s3->update([
        'start_date' => '2026-09-21 08:00:00',
        'end_date' => '2026-10-16 18:00:00',
        'status' => 'active'
    ]);
}

echo "Updated seminar dates.\n";
