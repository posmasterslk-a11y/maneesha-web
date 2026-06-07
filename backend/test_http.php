<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$request = Illuminate\Http\Request::create('/api/admin/products/1', 'POST', [
    'name' => 'Test',
    'base_price' => 3500,
    'stock' => 50,
    'is_active' => '1',
    'variants' => json_encode([['size' => 'M', 'price' => 3500, 'stock' => 50]])
]);
$request->headers->set('Accept', 'application/json');

$user = \App\Models\User::where('email', 'admin@maneeshafashion.lk')->first();
$app->make('auth')->guard('sanctum')->setUser($user);

$response = $app->handle($request);
echo $response->getStatusCode() . "\n";
echo $response->getContent() . "\n";
