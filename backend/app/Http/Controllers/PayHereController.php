<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class PayHereController extends Controller
{
    protected SmsService $sms;

    public function __construct(SmsService $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Listen to Instant Payment Notification (IPN) from PayHere Gateway
     */
    public function handleIpn(Request $request)
    {
        Log::info("PayHere IPN callback received: " . json_encode($request->all()));

        // Retrieve config/env parameters
        $merchantId = env('PAYHERE_MERCHANT_ID', '1236053'); 
        $merchantSecret = env('PAYHERE_MERCHANT_SECRET', 'MzU3NzU0Nzg4MzM3NDE3NDQzNjEzNTAyNDIyMzM4MDc0MTE0NTQ='); 

        $incomingMerchantId = $request->input('merchant_id');
        $orderId = $request->input('order_id');
        $payhereAmount = $request->input('payhere_amount');
        $payhereCurrency = $request->input('payhere_currency');
        $statusCode = $request->input('status_code');
        $md5sig = $request->input('md5sig');

        // Security check: Match merchant ID
        if ($incomingMerchantId !== $merchantId) {
            Log::error("PayHere IPN Failed: Merchant ID mismatch! Incoming: {$incomingMerchantId}");
            return response()->json(['error' => 'Merchant ID mismatch'], 400);
        }

        // Compute MD5 signature match
        $localMd5secret = strtoupper(md5($merchantSecret));
        $localMd5String = $merchantId . $orderId . $payhereAmount . $payhereCurrency . $statusCode . $localMd5secret;
        $localMd5sig = strtoupper(md5($localMd5String));

        if ($localMd5sig !== strtoupper($md5sig)) {
            Log::error("PayHere IPN Failed: MD5 Signature hash mismatch! Expected: {$localMd5sig} | Received: {$md5sig}");
            return response()->json(['error' => 'Signature mismatch'], 400);
        }

        // Payment status code "2" is successful authorization / completed payment
        if ($statusCode == 2) {
            Log::info("PayHere IPN Success: Order #{$orderId} payment verified.");

            // Update order status to paid / confirmed in DB (simulated)
            
            // Dispatch SMS notify to Owner
            $ownerPhone = env('OWNER_PHONE_NUMBER', '0771234567');
            $customerName = $request->input('custom_1', 'Valued Customer');
            $message = "Maneesha Fashion: New PayHere order #{$orderId} confirmed! Total LKR {$payhereAmount} paid by {$customerName}. Sizing details logged. Start stitching.";
            $this->sms->sendSms($ownerPhone, $message);

            return response()->json(['success' => true]);
        }

        Log::warning("PayHere IPN logged non-successful status: {$statusCode} for Order #{$orderId}");
        return response()->json(['status' => 'acknowledged']);
    }

    public function generateHash(Request $request)
    {
        $merchantId = trim(env('PAYHERE_MERCHANT_ID', '1236053'));
        $merchantSecret = trim(env('PAYHERE_MERCHANT_SECRET', 'MzU3NzU0Nzg4MzM3NDE3NDQzNjEzNTAyNDIyMzM4MDc0MTE0NTQ='));

        $orderId = trim($request->input('order_id'));
        $amount = (float) $request->input('amount');
        $currency = trim($request->input('currency', 'LKR'));

        $formattedAmount = number_format($amount, 2, '.', '');

        \Log::info("PayHere Hash Request", [
            'raw_amount' => $request->input('amount'),
            'parsed_amount' => $amount,
            'formatted_amount' => $formattedAmount,
            'order_id' => $orderId,
            'currency' => $currency,
            'merchant_id' => $merchantId,
            'merchant_secret' => $merchantSecret
        ]);

        $hash = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $formattedAmount . 
                $currency .  
                strtoupper(md5($merchantSecret)) 
            ) 
        );

        return response()->json([
            'hash' => $hash,
            'merchant_id' => $merchantId,
            'debug_secret' => $merchantSecret,
            'debug_order' => $orderId,
            'debug_amount' => $formattedAmount,
            'debug_currency' => $currency,
            'debug_all' => $request->all(),
            'debug_raw' => $request->getContent(),
        ]);
    }
}
