<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$reqDash = \Illuminate\Http\Request::create('/admin/dashboard', 'GET');
$respDash = $app->handle($reqDash);
echo "Dashboard Status: " . $respDash->getStatusCode() . "\n";

$reqCand = \Illuminate\Http\Request::create('/admin/candidates', 'GET');
$respCand = $app->handle($reqCand);
echo "Candidates Status: " . $respCand->getStatusCode() . "\n";
