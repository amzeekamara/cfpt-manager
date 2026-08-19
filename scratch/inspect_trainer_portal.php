<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trainers = App\Models\Trainer::with('seminars')->get();
echo "=== TRAINERS (" . $trainers->count() . ") ===\n";
foreach ($trainers as $t) {
    echo "Trainer #{$t->id}: {$t->name} ({$t->email}) | Seminars: " . $t->seminars->pluck('id')->join(', ') . "\n";
}

$seminars = App\Models\Seminar::withCount('registrations')->get();
echo "\n=== SEMINARS ===\n";
foreach ($seminars as $s) {
    $approvedCount = $s->registrations()->where('status', 'approved')->count();
    $totalCount = $s->registrations()->count();
    echo "Seminar #{$s->id}: {$s->title} | Total Regs: {$totalCount} | Approved: {$approvedCount}\n";
}

$attendances = App\Models\Attendance::all();
echo "\n=== ATTENDANCES COUNT: " . $attendances->count() . " ===\n";
foreach ($attendances->take(10) as $a) {
    echo "Attendance #{$a->id}: Reg #{$a->registration_id}, Date: {$a->session_date}, Status: {$a->status}\n";
}
