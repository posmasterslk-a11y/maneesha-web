<?php

$merchantId = '1236053';
$merchantSecret = 'MzU3NzU0Nzg4MzM3NDE3NDQzNjEzNTAyNDIyMzM4MDc0MTE0NTQ=';
$orderId = '123456';
$amount = '3200.00';
$currency = 'LKR';

$hash = strtoupper(
    md5(
        $merchantId . 
        $orderId . 
        $amount . 
        $currency .  
        strtoupper(md5($merchantSecret)) 
    ) 
);

echo "Hash: " . $hash . "\n";
