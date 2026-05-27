<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/login', 'POST', [
    'username' => 'superadmin',
    'password' => 'ChangeMe123!',
    '_token' => csrf_token(),
]);

$response = $kernel->handle($request);
echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
$location = $response->headers->get('Location');
echo 'Location: ' . ($location ?? 'none (same page)') . PHP_EOL;

$content = $response->getContent();
if (str_contains($content, 'These credentials')) {
    echo 'ERROR: Credentials still failing' . PHP_EOL;
} elseif ($location && str_contains($location, 'dashboard')) {
    echo 'SUCCESS: Would redirect to dashboard' . PHP_EOL;
}
