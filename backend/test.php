<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $order = App\Models\Order::first();
    if ($order) {
        $order->orderItems()->delete();
        $order->delete();
        echo "Deleted order " . $order->id;
    } else {
        echo "No orders found";
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
