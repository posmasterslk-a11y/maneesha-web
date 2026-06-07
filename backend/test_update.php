<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $product = \App\Models\Product::first();
    $product->update(['name' => 'Test', 'base_price' => 3500, 'stock' => 50, 'is_active' => true]);
    $variants = [
        ['size' => 'XS', 'price' => 0, 'stock' => 10],
    ];
    $product->variants()->delete();
    foreach ($variants as $v) {
        $product->variants()->create([
            'size'      => $v['size'],
            'price'     => $v['price'] ?? $product->base_price,
            'stock'     => $v['stock'] ?? 0,
            'is_active' => true,
        ]);
    }
    echo "Success!";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
