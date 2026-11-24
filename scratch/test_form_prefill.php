<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$requestForm = \Illuminate\Http\Request::create('/postuler?seminar_id=3', 'GET');
$responseForm = $app->handle($requestForm);
$content = $responseForm->getContent();

// Check if selected attribute exists on seminar 3 option
if (preg_match('/<option value="3"\s+selected/', $content)) {
    echo "SUCCESS: Seminar 3 is selected in the select options!\n";
} else {
    echo "FAIL: Seminar 3 is NOT selected!\n";
}

// Check that top preview card is NOT present
if (strpos($content, 'Séminaire Sélectionné') === false) {
    echo "SUCCESS: Duplicate 'Séminaire Sélectionné' card has been removed!\n";
} else {
    echo "FAIL: 'Séminaire Sélectionné' is still present!\n";
}
