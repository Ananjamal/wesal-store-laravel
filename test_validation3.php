<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/login', 'POST', [
    'email' => 'wrong@email.com',
    'password' => 'wrong',
]);
$request->headers->set('X-Inertia', 'true');
$request->headers->set('X-Inertia-Version', '6ba5f829763b164f39bc010ab1f7e672');
$response = $kernel->handle($request);
$kernel->terminate($request, $response); // Save the session!

$cookies = $response->headers->getCookies();
$sessionCookie = null;
foreach ($cookies as $cookie) {
    if ($cookie->getName() === config('session.cookie')) {
        $sessionCookie = $cookie->getValue();
        break;
    }
}

$request2 = Illuminate\Http\Request::create('/login', 'GET');
$request2->headers->set('X-Inertia', 'true');
$request2->headers->set('X-Inertia-Version', '6ba5f829763b164f39bc010ab1f7e672');
if ($sessionCookie) {
    $request2->cookies->set(config('session.cookie'), $sessionCookie);
}
$response2 = $kernel->handle($request2);
$kernel->terminate($request2, $response2);
echo "Errors in GET: " . json_encode(json_decode($response2->getContent(), true)['props']['errors']) . "\n";
