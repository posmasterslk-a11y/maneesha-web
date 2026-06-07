<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = \App\Models\Product::where('name', 'like', '%Casual Frock%')->first();
if ($p) {
    echo "Found: " . $p->name . "\n";
} else {
    echo "Not found.\n";
}
