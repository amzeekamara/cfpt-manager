<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sessions = App\Models\TrainerSession::with(['trainer', 'seminar', 'topic'])->get();
echo "=== SESSIONS COUNT: " . $sessions->count() . " ===\n";
foreach ($sessions as $s) {
    echo "Session #{$s->id}: Trainer #{$s->trainer_id} ({$s->trainer?->name}), Seminar: {$s->seminar?->title}, Date: {$s->session_date}, Hours: {$s->hours_taught}h\n";
}

if ($sessions->count() === 0) {
    echo "Creating realistic sessions from attendances dates...\n";
    $trainer = App\Models\Trainer::first();
    if ($trainer) {
        $dates = App\Models\Attendance::distinct()->pluck('session_date');
        foreach ($dates as $d) {
            $formattedDate = \Carbon\Carbon::parse($d)->format('Y-m-d');
            App\Models\TrainerSession::firstOrCreate(
                [
                    'trainer_id' => $trainer->id,
                    'seminar_id' => $trainer->seminars->first()?->id ?? 1,
                    'session_date' => $formattedDate,
                ],
                [
                    'hours_taught' => 4,
                    'topic_id' => null,
                ]
            );
        }
        echo "Created " . App\Models\TrainerSession::count() . " sessions for Trainer #{$trainer->id}\n";
    }
}
