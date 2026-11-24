<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/', 'GET');
$response = $app->handle($request);
echo "Welcome page status: " . $response->getStatusCode() . "\n";

$requestForm = \Illuminate\Http\Request::create('/postuler?seminar_id=3', 'GET');
$responseForm = $app->handle($requestForm);
echo "Form page status: " . $responseForm->getStatusCode() . "\n";
