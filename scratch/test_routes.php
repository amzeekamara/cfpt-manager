<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$req1 = Illuminate\Http\Request::create('/', 'GET');
$res1 = $kernel->handle($req1);
echo "Welcome page (/) HTTP status : " . $res1->getStatusCode() . "\n";

$req2 = Illuminate\Http\Request::create('/postuler', 'GET', ['seminar_id' => 1]);
$res2 = $kernel->handle($req2);
echo "Registration page (/postuler?seminar_id=1) HTTP status : " . $res2->getStatusCode() . "\n";
